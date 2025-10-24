<?php

namespace App\Http\Controllers\Manager;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingManagementController extends Controller
{
    /**
     * Display bookings for manager's accommodations
     */
    public function index(Request $request)
    {
        $accommodationIds = Auth::user()->accommodations()->pluck('id');

        $query = Booking::with(['accommodation', 'room', 'user'])
            ->whereIn('accommodation_id', $accommodationIds)
            ->latest();

        // Filter by accommodation
        if ($request->filled('accommodation_id')) {
            $query->where('accommodation_id', $request->accommodation_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        // Filter by date
        if ($request->filled('date')) {
            $date = $request->date;
            if ($date === 'today') {
                $query->whereDate('check_in_date', today());
            } elseif ($date === 'week') {
                $query->whereBetween('check_in_date', [today(), today()->addWeek()]);
            }
        }

        $bookings = $query->paginate(20);

        $accommodations = Auth::user()->accommodations;

        return view('manager.bookings.index', compact('bookings', 'accommodations'));
    }

    /**
     * Show booking details
     */
    public function show($id)
    {
        $accommodationIds = Auth::user()->accommodations()->pluck('id');

        $booking = Booking::with(['accommodation', 'room', 'user'])
            ->whereIn('accommodation_id', $accommodationIds)
            ->findOrFail($id);

        return view('manager.bookings.show', compact('booking'));
    }

    /**
     * Update booking status
     */
    public function updateStatus(Request $request, $id)
    {
        $accommodationIds = Auth::user()->accommodations()->pluck('id');

        $booking = Booking::whereIn('accommodation_id', $accommodationIds)->findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:confirmed,checked_in,checked_out'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $newStatus = BookingStatus::from($validated['status']);

        // Validate status transition
        if ($newStatus === BookingStatus::CHECKED_IN && !$booking->status->canBeCheckedIn()) {
            return redirect()
                ->back()
                ->with('error', '체크인할 수 없는 상태입니다.');
        }

        if ($newStatus === BookingStatus::CHECKED_OUT && !$booking->status->canBeCheckedOut()) {
            return redirect()
                ->back()
                ->with('error', '체크아웃할 수 없는 상태입니다.');
        }

        // Update status
        if ($newStatus === BookingStatus::CONFIRMED) {
            $booking->confirm();
        } elseif ($newStatus === BookingStatus::CHECKED_IN) {
            $booking->checkIn();
        } elseif ($newStatus === BookingStatus::CHECKED_OUT) {
            $booking->checkOut();
        }

        return redirect()
            ->route('manager.bookings.show', $booking->id)
            ->with('success', '예약 상태가 업데이트되었습니다.');
    }
}
