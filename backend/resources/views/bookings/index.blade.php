@extends('layouts.app')

@section('title', '내 예약 목록')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">내 예약 목록</h1>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="{{ route('bookings.index') }}"
               class="{{ !request('status') ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                전체
            </a>
            <a href="{{ route('bookings.index', ['status' => 'confirmed']) }}"
               class="{{ request('status') === 'confirmed' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                예정된 예약
            </a>
            <a href="{{ route('bookings.index', ['status' => 'checked_out']) }}"
               class="{{ request('status') === 'checked_out' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                완료된 예약
            </a>
            <a href="{{ route('bookings.index', ['status' => 'cancelled']) }}"
               class="{{ request('status') === 'cancelled' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                취소된 예약
            </a>
        </nav>
    </div>

    <!-- Bookings List -->
    @if($bookings->count() > 0)
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $booking->accommodation->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $booking->room->name }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-{{ $booking->status->color() }}-100 text-{{ $booking->status->color() }}-800">
                                {{ $booking->status->label() }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <p class="text-xs text-gray-500">예약 번호</p>
                                <p class="font-medium">{{ $booking->booking_number }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">체크인 - 체크아웃</p>
                                <p class="font-medium">{{ $booking->check_in_date->format('m/d') }} - {{ $booking->check_out_date->format('m/d') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">금액</p>
                                <p class="font-medium text-blue-600">₩{{ number_format($booking->total_price) }}</p>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('bookings.show', $booking->id) }}"
                               class="text-blue-600 hover:text-blue-800 font-medium">
                                상세 보기 →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $bookings->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">예약 내역이 없습니다</h3>
            <p class="text-gray-600 mb-4">지금 바로 숙소를 검색하고 예약해보세요!</p>
            <a href="{{ route('accommodations.search') }}"
               class="inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                숙소 검색하기
            </a>
        </div>
    @endif
</div>
@endsection
