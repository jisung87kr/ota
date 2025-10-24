<?php

namespace App\Http\Controllers\Web;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Show booking form for a specific room
     */
    public function create(Request $request, $accommodationId, $roomId)
    {
        $accommodation = Accommodation::with(['owner', 'amenities'])->findOrFail($accommodationId);
        $room = Room::with('amenities')->findOrFail($roomId);

        // Validate dates
        $checkIn = $request->input('check_in');
        $checkOut = $request->input('check_out');
        $guests = $request->input('guests', 2);

        if (!$checkIn || !$checkOut) {
            return redirect()
                ->route('accommodations.show', $accommodationId)
                ->with('error', '체크인 및 체크아웃 날짜를 선택해주세요.');
        }

        // Check availability
        if (!$room->isAvailable($checkIn, $checkOut)) {
            return redirect()
                ->route('accommodations.show', $accommodationId)
                ->with('error', '선택하신 날짜에 예약 가능한 객실이 없습니다.');
        }

        $checkInDate = \Carbon\Carbon::parse($checkIn);
        $checkOutDate = \Carbon\Carbon::parse($checkOut);
        $nights = $checkInDate->diffInDays($checkOutDate);
        $totalPrice = $room->base_price * $nights;

        return view('bookings.create', compact(
            'accommodation',
            'room',
            'checkIn',
            'checkOut',
            'guests',
            'nights',
            'totalPrice'
        ));
    }

    /**
     * Store a new booking
     */
    public function store(Request $request, $accommodationId, $roomId)
    {
        $validated = $request->validate([
            'guest_name' => ['required', 'string', 'max:255'],
            'guest_email' => ['required', 'email', 'max:255'],
            'guest_phone' => ['required', 'string', 'max:20'],
            'special_requests' => ['nullable', 'string', 'max:1000'],
            'check_in_date' => ['required', 'date', 'after_or_equal:today'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'guests' => ['required', 'integer', 'min:1'],
        ], [
            'guest_name.required' => '투숙객 이름을 입력해주세요.',
            'guest_email.required' => '이메일을 입력해주세요.',
            'guest_email.email' => '올바른 이메일 형식이 아닙니다.',
            'guest_phone.required' => '전화번호를 입력해주세요.',
            'check_in_date.required' => '체크인 날짜를 선택해주세요.',
            'check_in_date.after_or_equal' => '체크인 날짜는 오늘 이후여야 합니다.',
            'check_out_date.required' => '체크아웃 날짜를 선택해주세요.',
            'check_out_date.after' => '체크아웃 날짜는 체크인 날짜 이후여야 합니다.',
        ]);

        $room = Room::findOrFail($roomId);

        // Double-check availability
        if (!$room->isAvailable($validated['check_in_date'], $validated['check_out_date'])) {
            return redirect()
                ->back()
                ->with('error', '죄송합니다. 선택하신 날짜에 예약이 마감되었습니다.')
                ->withInput();
        }

        // Calculate pricing
        $checkIn = \Carbon\Carbon::parse($validated['check_in_date']);
        $checkOut = \Carbon\Carbon::parse($validated['check_out_date']);
        $nights = $checkIn->diffInDays($checkOut);
        $totalPrice = $room->base_price * $nights;

        try {
            DB::beginTransaction();

            // Create booking
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'accommodation_id' => $accommodationId,
                'room_id' => $roomId,
                'guest_name' => $validated['guest_name'],
                'guest_email' => $validated['guest_email'],
                'guest_phone' => $validated['guest_phone'],
                'special_requests' => $validated['special_requests'],
                'check_in_date' => $validated['check_in_date'],
                'check_out_date' => $validated['check_out_date'],
                'nights' => $nights,
                'guests' => $validated['guests'],
                'room_price' => $room->base_price,
                'total_price' => $totalPrice,
                'status' => BookingStatus::PENDING,
            ]);

            DB::commit();

            // TODO: In Epic 5, redirect to payment page
            // For now, just confirm the booking
            $booking->confirm();

            return redirect()
                ->route('bookings.confirmation', $booking->id)
                ->with('success', '예약이 완료되었습니다!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', '예약 처리 중 오류가 발생했습니다. 다시 시도해주세요.')
                ->withInput();
        }
    }

    /**
     * Show booking confirmation
     */
    public function confirmation($id)
    {
        $booking = Booking::with(['accommodation', 'room', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('bookings.confirmation', compact('booking'));
    }

    /**
     * Show all bookings for the authenticated user
     */
    public function index(Request $request)
    {
        $query = Auth::user()->bookings()->with(['accommodation', 'room'])->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        $bookings = $query->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show specific booking details
     */
    public function show($id)
    {
        $booking = Booking::with(['accommodation', 'room', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('bookings.show', compact('booking'));
    }

    /**
     * Cancel a booking
     */
    public function cancel(Request $request, $id)
    {
        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);

        if (!$booking->canBeCancelled()) {
            return redirect()
                ->route('bookings.show', $id)
                ->with('error', '이 예약은 취소할 수 없습니다.');
        }

        $validated = $request->validate([
            'cancellation_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $refundAmount = $booking->calculateRefundAmount();

        if ($booking->cancel($validated['cancellation_reason'])) {
            return redirect()
                ->route('bookings.show', $id)
                ->with('success', "예약이 취소되었습니다. 환불 금액: ₩" . number_format($refundAmount));
        }

        return redirect()
            ->route('bookings.show', $id)
            ->with('error', '예약 취소 중 오류가 발생했습니다.');
    }
}
