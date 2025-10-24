<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Amenity;
use Illuminate\Http\Request;

class AccommodationController extends Controller
{
    /**
     * Display a listing of accommodations with search and filters
     */
    public function index(Request $request)
    {
        $query = Accommodation::with(['activeRooms', 'amenities', 'images'])
            ->active();

        // Search by city/destination
        if ($request->filled('city')) {
            $query->searchByCity($request->city);
        }

        // Filter by amenities
        if ($request->filled('amenities')) {
            $amenityIds = is_array($request->amenities)
                ? $request->amenities
                : explode(',', $request->amenities);
            $query->withAmenities($amenityIds);
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->minRating($request->rating);
        }

        // Filter by price range
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->priceRange($request->min_price, $request->max_price);
        }

        // Sorting
        $sortBy = $request->get('sort', 'recommended');
        switch ($sortBy) {
            case 'price_low':
                $query->join('rooms', 'accommodations.id', '=', 'rooms.accommodation_id')
                    ->select('accommodations.*')
                    ->groupBy('accommodations.id')
                    ->orderByRaw('MIN(rooms.base_price) ASC');
                break;
            case 'price_high':
                $query->join('rooms', 'accommodations.id', '=', 'rooms.accommodation_id')
                    ->select('accommodations.*')
                    ->groupBy('accommodations.id')
                    ->orderByRaw('MIN(rooms.base_price) DESC');
                break;
            case 'rating':
                $query->orderBy('average_rating', 'desc');
                break;
            case 'reviews':
                $query->orderBy('total_reviews', 'desc');
                break;
            default:
                // Recommended: combination of rating and reviews
                $query->orderByRaw('(average_rating * 0.7 + (total_reviews / 100) * 0.3) DESC');
        }

        $accommodations = $query->paginate(20)->withQueryString();

        // Get all amenities for filter options
        $amenities = Amenity::orderBy('name')->get();

        return view('accommodations.index', compact('accommodations', 'amenities'));
    }

    /**
     * Display the specified accommodation
     */
    public function show($id, Request $request)
    {
        $accommodation = Accommodation::with([
            'activeRooms.amenities',
            'activeRooms.images',
            'amenities',
            'images',
            'owner'
        ])->findOrFail($id);

        // Get check-in and check-out dates from request (for availability check)
        // If not provided, default to tomorrow and day after
        $checkIn = $request->get('check_in', now()->addDay()->format('Y-m-d'));
        $checkOut = $request->get('check_out', now()->addDays(2)->format('Y-m-d'));
        $guests = $request->get('guests', 2);

        // Get reviews with filtering and sorting
        $reviewsQuery = $accommodation->reviews()->visible()->with('user');

        // Filter by rating
        if ($request->filled('review_rating')) {
            $reviewsQuery->byRating($request->review_rating);
        }

        // Filter by photos
        if ($request->get('with_photos') === '1') {
            $reviewsQuery->withPhotos();
        }

        // Sorting
        $sortBy = $request->get('review_sort', 'newest');
        switch ($sortBy) {
            case 'helpful':
                $reviewsQuery->mostHelpful();
                break;
            case 'rating_high':
                $reviewsQuery->orderBy('rating', 'desc');
                break;
            case 'rating_low':
                $reviewsQuery->orderBy('rating', 'asc');
                break;
            default: // newest
                $reviewsQuery->newest();
        }

        $reviews = $reviewsQuery->paginate(10)->withQueryString();

        // Get rating distribution
        $ratingDistribution = $accommodation->getRatingDistribution();

        return view('accommodations.show', compact(
            'accommodation',
            'checkIn',
            'checkOut',
            'guests',
            'reviews',
            'ratingDistribution'
        ));
    }

    /**
     * Show search page
     */
    public function search(Request $request)
    {
        $amenities = Amenity::orderBy('name')->get();

        return view('accommodations.search', compact('amenities'));
    }
}
