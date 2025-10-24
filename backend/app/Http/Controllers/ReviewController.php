<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * Show form to create review for a booking
     */
    public function create($bookingId)
    {
        $booking = Booking::with(['accommodation', 'room'])
            ->where('user_id', Auth::id())
            ->findOrFail($bookingId);

        // Check if can write review
        if (!$booking->canWriteReview()) {
            return redirect()->route('bookings.show', $booking->id)
                ->with('error', '이 예약에 대한 리뷰를 작성할 수 없습니다.');
        }

        return view('reviews.create', compact('booking'));
    }

    /**
     * Store a new review
     */
    public function store(Request $request, $bookingId)
    {
        $booking = Booking::with(['accommodation', 'room'])
            ->where('user_id', Auth::id())
            ->findOrFail($bookingId);

        if (!$booking->canWriteReview()) {
            return back()->with('error', '이 예약에 대한 리뷰를 작성할 수 없습니다.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:20', 'max:2000'],
            'rating' => ['required', 'numeric', 'min:1', 'max:5'],
            'cleanliness_rating' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'service_rating' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'location_rating' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'value_rating' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['image', 'max:2048'], // Max 2MB per image
        ], [
            'title.required' => '제목을 입력해주세요.',
            'content.required' => '리뷰 내용을 입력해주세요.',
            'content.min' => '리뷰는 최소 20자 이상 작성해주세요.',
            'rating.required' => '전체 평점을 선택해주세요.',
            'rating.min' => '평점은 1점 이상이어야 합니다.',
            'rating.max' => '평점은 5점 이하여야 합니다.',
            'photos.max' => '사진은 최대 5장까지 업로드할 수 있습니다.',
            'photos.*.image' => '올바른 이미지 파일을 선택해주세요.',
            'photos.*.max' => '이미지 크기는 2MB를 초과할 수 없습니다.',
        ]);

        try {
            DB::transaction(function () use ($request, $booking, $validated) {
                // Upload photos if any
                $photoPaths = [];
                if ($request->hasFile('photos')) {
                    foreach ($request->file('photos') as $photo) {
                        $photoPaths[] = $photo->store('reviews', 'public');
                    }
                }

                // Create review
                Review::create([
                    'booking_id' => $booking->id,
                    'user_id' => Auth::id(),
                    'accommodation_id' => $booking->accommodation_id,
                    'title' => $validated['title'],
                    'content' => $validated['content'],
                    'rating' => $validated['rating'],
                    'cleanliness_rating' => $validated['cleanliness_rating'] ?? null,
                    'service_rating' => $validated['service_rating'] ?? null,
                    'location_rating' => $validated['location_rating'] ?? null,
                    'value_rating' => $validated['value_rating'] ?? null,
                    'photos' => !empty($photoPaths) ? $photoPaths : null,
                ]);
            });

            return redirect()->route('accommodations.show', $booking->accommodation_id)
                ->with('success', '리뷰가 성공적으로 등록되었습니다!');

        } catch (\Exception $e) {
            return back()
                ->with('error', '리뷰 등록 중 오류가 발생했습니다.')
                ->withInput();
        }
    }

    /**
     * Show reviews for an accommodation
     */
    public function index(Request $request, $accommodationId)
    {
        $accommodation = \App\Models\Accommodation::with(['visibleReviews' => function($query) use ($request) {
            // Apply filters
            if ($request->filled('rating')) {
                $query->byRating($request->rating);
            }

            if ($request->filled('with_photos')) {
                $query->withPhotos();
            }

            // Apply sorting
            if ($request->input('sort') === 'helpful') {
                $query->mostHelpful();
            } else {
                $query->newest();
            }
        }])->findOrFail($accommodationId);

        $reviews = $accommodation->visibleReviews()->with('user')->paginate(10);

        return view('reviews.index', compact('accommodation', 'reviews'));
    }

    /**
     * Toggle helpful vote on a review
     */
    public function toggleHelpful($reviewId)
    {
        $review = Review::findOrFail($reviewId);

        $isHelpful = $review->toggleHelpful();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_helpful' => $isHelpful,
                'helpful_count' => $review->helpful_count,
            ]);
        }

        return back()->with('success', $isHelpful ? '도움이 됨으로 표시했습니다.' : '도움이 됨을 취소했습니다.');
    }

    /**
     * Admin: Hide review
     */
    public function hide(Request $request, $reviewId)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $review = Review::findOrFail($reviewId);
        $review->hide($validated['reason']);

        return back()->with('success', '리뷰가 숨겨졌습니다.');
    }

    /**
     * Admin: Unhide review
     */
    public function unhide($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $review->unhide();

        return back()->with('success', '리뷰가 다시 표시됩니다.');
    }

    /**
     * Manager: Add response to review
     */
    public function respond(Request $request, $reviewId)
    {
        $validated = $request->validate([
            'response' => ['required', 'string', 'max:1000'],
        ]);

        $review = Review::with('accommodation')->findOrFail($reviewId);

        // Check if user is the accommodation owner
        if ($review->accommodation->user_id !== Auth::id()) {
            abort(403, '권한이 없습니다.');
        }

        $review->addResponse($validated['response']);

        return back()->with('success', '답변이 등록되었습니다.');
    }
}
