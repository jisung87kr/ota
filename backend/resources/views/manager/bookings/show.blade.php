@extends('layouts.app')

@section('title', '예약 상세 - OTA Service')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">예약 상세</h1>
            <p class="mt-2 text-gray-600">예약번호: {{ $booking->booking_number }}</p>
        </div>
        <a href="{{ route('manager.bookings.index') }}"
           class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
            목록으로
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Booking Status Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">예약 상태</h2>
                    <span class="px-4 py-2 text-sm rounded-full font-semibold
                        @if($booking->status->value === 'confirmed') bg-green-100 text-green-800
                        @elseif($booking->status->value === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($booking->status->value === 'checked_in') bg-blue-100 text-blue-800
                        @elseif($booking->status->value === 'checked_out') bg-gray-100 text-gray-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ $booking->status->label() }}
                    </span>
                </div>

                <!-- Status Update Form -->
                @if(in_array($booking->status->value, ['pending', 'confirmed', 'checked_in']))
                    <form action="{{ route('manager.bookings.update-status', $booking->id) }}" method="POST" class="border-t pt-4">
                        @csrf
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                    상태 변경
                                </label>
                                <select name="status" id="status" required
                                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                    @if($booking->status->value === 'pending')
                                        <option value="confirmed">확정</option>
                                    @endif
                                    @if(in_array($booking->status->value, ['pending', 'confirmed']))
                                        <option value="checked_in">체크인</option>
                                    @endif
                                    @if($booking->status->value === 'checked_in')
                                        <option value="checked_out">체크아웃</option>
                                    @endif
                                </select>
                            </div>
                            <div>
                                <label for="note" class="block text-sm font-medium text-gray-700 mb-2">
                                    메모 <span class="text-gray-500">(선택)</span>
                                </label>
                                <textarea name="note" id="note" rows="2"
                                          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                          placeholder="상태 변경 관련 메모를 입력하세요"></textarea>
                            </div>
                            <div>
                                <button type="submit"
                                        class="w-full px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                                    상태 업데이트
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>

            <!-- Accommodation & Room Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">숙소 및 객실 정보</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $booking->accommodation->name }}</h3>
                        <p class="text-sm text-gray-600">
                            <svg class="inline w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $booking->accommodation->city }} - {{ $booking->accommodation->address }}
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            <svg class="inline w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            {{ $booking->accommodation->phone }}
                        </p>
                    </div>

                    <div class="border-t pt-4">
                        <h4 class="font-semibold text-gray-900 mb-2">{{ $booking->room->name }}</h4>
                        @if($booking->room->description)
                            <p class="text-sm text-gray-600 mb-2">{{ $booking->room->description }}</p>
                        @endif
                        <div class="flex flex-wrap gap-4 text-sm text-gray-700">
                            <span>최대 인원: {{ $booking->room->max_occupancy }}명</span>
                            @if($booking->room->size)
                                <span>크기: {{ $booking->room->size }}㎡</span>
                            @endif
                            @if($booking->room->bed_type)
                                <span>침대: {{ ucfirst($booking->room->bed_type) }} × {{ $booking->room->bed_count }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">고객 정보</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">이름</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $booking->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">이메일</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $booking->user->email }}</dd>
                    </div>
                    @if($booking->user->phone)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">연락처</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $booking->user->phone }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">투숙 인원</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $booking->guests }}명</dd>
                    </div>
                </dl>
                @if($booking->special_requests)
                    <div class="mt-4 border-t pt-4">
                        <dt class="text-sm font-medium text-gray-500 mb-2">특별 요청사항</dt>
                        <dd class="text-sm text-gray-900 whitespace-pre-line">{{ $booking->special_requests }}</dd>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Booking Summary -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">예약 요약</h2>
                <dl class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <dt class="text-gray-600">체크인</dt>
                        <dd class="font-semibold text-gray-900">{{ $booking->check_in_date->format('Y-m-d') }}</dd>
                    </div>
                    <div class="flex justify-between text-sm">
                        <dt class="text-gray-600">체크아웃</dt>
                        <dd class="font-semibold text-gray-900">{{ $booking->check_out_date->format('Y-m-d') }}</dd>
                    </div>
                    <div class="flex justify-between text-sm">
                        <dt class="text-gray-600">숙박 일수</dt>
                        <dd class="font-semibold text-gray-900">{{ $booking->nights }}박</dd>
                    </div>
                    <div class="border-t pt-3 flex justify-between">
                        <dt class="text-lg font-semibold text-gray-900">총 금액</dt>
                        <dd class="text-lg font-bold text-blue-600">₩{{ number_format($booking->total_price) }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Booking Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">예약 정보</h2>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-gray-600">예약 일시</dt>
                        <dd class="font-semibold text-gray-900">{{ $booking->created_at->format('Y-m-d H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-600">예약번호</dt>
                        <dd class="font-semibold text-gray-900 font-mono">{{ $booking->booking_number }}</dd>
                    </div>
                    @if($booking->cancelled_at)
                        <div class="border-t pt-3">
                            <dt class="text-gray-600">취소 일시</dt>
                            <dd class="font-semibold text-red-600">{{ $booking->cancelled_at->format('Y-m-d H:i') }}</dd>
                        </div>
                        @if($booking->cancellation_reason)
                            <div>
                                <dt class="text-gray-600">취소 사유</dt>
                                <dd class="text-gray-900">{{ $booking->cancellation_reason }}</dd>
                            </div>
                        @endif
                        @if($booking->refund_amount)
                            <div>
                                <dt class="text-gray-600">환불 금액</dt>
                                <dd class="font-semibold text-gray-900">₩{{ number_format($booking->refund_amount) }}</dd>
                            </div>
                        @endif
                    @endif
                </dl>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">빠른 작업</h2>
                <div class="space-y-2">
                    <a href="{{ route('manager.accommodations.show', $booking->accommodation->id) }}"
                       class="block w-full px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 text-center text-sm">
                        숙소 상세보기
                    </a>
                    <a href="{{ route('manager.bookings.index', ['accommodation_id' => $booking->accommodation->id]) }}"
                       class="block w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-center text-sm">
                        이 숙소의 다른 예약
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
