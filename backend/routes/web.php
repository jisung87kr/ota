<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Manager\AccommodationController as ManagerAccommodationController;
use App\Http\Controllers\Manager\BookingManagementController;
use App\Http\Controllers\Manager\DashboardController;
use App\Http\Controllers\Manager\RoomController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Web\AccommodationController;
use App\Http\Controllers\Web\BookingController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', function () {
    return view('home');
})->name('home');

// Accommodation Search and Browsing (Public)
Route::get('/search', [AccommodationController::class, 'search'])->name('accommodations.search');
Route::get('/accommodations', [AccommodationController::class, 'index'])->name('accommodations.index');
Route::get('/accommodations/{id}', [AccommodationController::class, 'show'])->name('accommodations.show');

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Register
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Password Reset
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

// Logout (authenticated users only)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Booking Routes (Authenticated Users)
Route::middleware('auth')->group(function () {
    // Create booking
    Route::get('/accommodations/{accommodation}/rooms/{room}/book', [BookingController::class, 'create'])
        ->name('bookings.create');
    Route::post('/accommodations/{accommodation}/rooms/{room}/book', [BookingController::class, 'store'])
        ->name('bookings.store');

    // View bookings
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{id}/confirmation', [BookingController::class, 'confirmation'])
        ->name('bookings.confirmation');

    // Cancel booking
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Payment Routes
    Route::get('/payments/{booking}/prepare', [PaymentController::class, 'prepare'])->name('payment.prepare');
    Route::get('/payments/callback', [PaymentController::class, 'callback'])->name('payment.callback');
    Route::post('/payments/{booking}/refund', [PaymentController::class, 'refund'])->name('payment.refund');
});

// Payment Webhook (no auth required)
Route::post('/payments/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');

// Protected Routes - Customer
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', function () {
        return view('customer.dashboard');
    })->name('dashboard');
});

// Protected Routes - Accommodation Manager
Route::middleware(['auth', 'role:accommodation_manager'])->prefix('manager')->name('manager.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Accommodations
    Route::resource('accommodations', ManagerAccommodationController::class);

    // Rooms
    Route::get('/accommodations/{accommodation}/rooms/create', [RoomController::class, 'create'])
        ->name('accommodations.rooms.create');
    Route::post('/accommodations/{accommodation}/rooms', [RoomController::class, 'store'])
        ->name('accommodations.rooms.store');
    Route::get('/accommodations/{accommodation}/rooms/{room}/edit', [RoomController::class, 'edit'])
        ->name('accommodations.rooms.edit');
    Route::put('/accommodations/{accommodation}/rooms/{room}', [RoomController::class, 'update'])
        ->name('accommodations.rooms.update');
    Route::delete('/accommodations/{accommodation}/rooms/{room}', [RoomController::class, 'destroy'])
        ->name('accommodations.rooms.destroy');

    // Booking Management
    Route::get('/bookings', [BookingManagementController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}', [BookingManagementController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{id}/status', [BookingManagementController::class, 'updateStatus'])
        ->name('bookings.update-status');
});

// Protected Routes - Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});
