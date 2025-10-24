<?php

namespace App\Http\Controllers\Manager;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display manager dashboard with statistics
     */
    public function index()
    {
        $user = Auth::user();

        // Get accommodation IDs
        $accommodationIds = $user->accommodations()->pluck('id');

        // Statistics
        $stats = [
            'total_accommodations' => $user->accommodations()->count(),
            'total_rooms' => $user->accommodations()->withCount('rooms')->get()->sum('rooms_count'),
            'total_bookings' => \App\Models\Booking::whereIn('accommodation_id', $accommodationIds)->count(),
            'today_checkins' => \App\Models\Booking::whereIn('accommodation_id', $accommodationIds)
                ->where('check_in_date', today())
                ->whereIn('status', [BookingStatus::CONFIRMED->value, BookingStatus::CHECKED_IN->value])
                ->count(),
            'today_checkouts' => \App\Models\Booking::whereIn('accommodation_id', $accommodationIds)
                ->where('check_out_date', today())
                ->where('status', BookingStatus::CHECKED_IN->value)
                ->count(),
            'month_revenue' => \App\Models\Booking::whereIn('accommodation_id', $accommodationIds)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('status', '!=', BookingStatus::CANCELLED->value)
                ->sum('total_price'),
        ];

        // Recent bookings
        $recentBookings = \App\Models\Booking::with(['accommodation', 'room', 'user'])
            ->whereIn('accommodation_id', $accommodationIds)
            ->latest()
            ->limit(5)
            ->get();

        // Accommodations with room count
        $accommodations = $user->accommodations()
            ->withCount('rooms')
            ->latest()
            ->limit(5)
            ->get();

        return view('manager.dashboard', compact('stats', 'recentBookings', 'accommodations'));
    }
}
