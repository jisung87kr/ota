<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\Role;
use App\Enums\UserStatus;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_customers' => User::where('role', Role::CUSTOMER)->count(),
            'total_managers' => User::where('role', Role::ACCOMMODATION_MANAGER)->count(),
            'pending_approvals' => User::where('status', UserStatus::PENDING)->count(),
            'active_users' => User::where('status', UserStatus::ACTIVE)->count(),
        ];

        $recent_users = User::latest()->take(10)->get();
        $pending_managers = User::where('role', Role::ACCOMMODATION_MANAGER)
            ->where('status', UserStatus::PENDING)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'pending_managers'));
    }
}
