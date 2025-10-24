@extends('layouts.app')

@section('title', '숙소 관리자 대시보드 - OTA Service')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">숙소 관리자 대시보드</h1>
        <p class="mt-2 text-gray-600">안녕하세요, {{ Auth::user()->name }}님!</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Stats Cards -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">등록된 숙소</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_accommodations'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">총 객실</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_rooms'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">총 예약</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_bookings'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">오늘 체크인</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['today_checkins'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">오늘 체크아웃</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['today_checkouts'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">이번 달 매출</p>
                    <p class="text-2xl font-bold text-gray-900">₩{{ number_format($stats['month_revenue']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Bookings -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">최근 예약</h2>
                <a href="{{ route('manager.bookings.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    전체 보기 →
                </a>
            </div>
            <div class="divide-y">
                @forelse($recentBookings as $booking)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-semibold">{{ $booking->accommodation->name }}</p>
                                <p class="text-sm text-gray-600">{{ $booking->room->name }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($booking->status->value === 'confirmed') bg-green-100 text-green-800
                                @elseif($booking->status->value === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($booking->status->value === 'checked_in') bg-blue-100 text-blue-800
                                @elseif($booking->status->value === 'checked_out') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $booking->status->label() }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-1">{{ $booking->user->name }}</p>
                        <p class="text-sm text-gray-500">
                            {{ $booking->check_in_date->format('Y-m-d') }} ~ {{ $booking->check_out_date->format('Y-m-d') }}
                        </p>
                        <p class="text-sm font-semibold mt-1">₩{{ number_format($booking->total_price) }}</p>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        예약 내역이 없습니다.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Accommodations -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">내 숙소</h2>
                <a href="{{ route('manager.accommodations.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    전체 보기 →
                </a>
            </div>
            <div class="divide-y">
                @forelse($accommodations as $accommodation)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-semibold">{{ $accommodation->name }}</p>
                                <p class="text-sm text-gray-600">{{ $accommodation->city }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full {{ $accommodation->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $accommodation->is_active ? '활성' : '비활성' }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500">객실 수: {{ $accommodation->rooms_count }}</p>
                        <a href="{{ route('manager.accommodations.show', $accommodation->id) }}" class="text-sm text-blue-600 hover:text-blue-800 mt-2 inline-block">
                            상세 보기 →
                        </a>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        <p class="mb-4">등록된 숙소가 없습니다.</p>
                        <a href="{{ route('manager.accommodations.create') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            첫 숙소 등록하기
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
