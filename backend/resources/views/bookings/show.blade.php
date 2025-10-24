@extends('layouts.app')

@section('title', '예약 상세 - ' . $booking->booking_number)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('bookings.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            예약 목록으로 돌아가기
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8">
        <!-- Header -->
        <div class="flex justify-between items-start mb-8 pb-6 border-b">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">예약 번호: {{ $booking->booking_number }}</h1>
                <span class="px-4 py-2 rounded-full text-sm font-medium bg-{{ $booking->status->color() }}-100 text-{{ $booking->status->color() }}-800">
                    {{ $booking->status->label() }}
                </span>
            </div>
            <p class="text-sm text-gray-500">예약일: {{ $booking->created_at->format('Y-m-d') }}</p>
        </div>

        <!-- Accommodation Info -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">숙소 정보</h2>
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $booking->accommodation->name }}</h3>
                <p class="text-gray-600 mb-2">{{ $booking->accommodation->address }}</p>
                <p class="text-sm text-gray-500">{{ $booking->accommodation->phone }}</p>
            </div>
        </div>

        <!-- Booking Details -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">예약 상세</h2>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">객실</p>
                    <p class="font-medium">{{ $booking->room->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">투숙객</p>
                    <p class="font-medium">{{ $booking->guest_name }} ({{ $booking->guests }}명)</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">체크인</p>
                    <p class="font-medium">{{ $booking->check_in_date->format('Y년 m월 d일') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">체크아웃</p>
                    <p class="font-medium">{{ $booking->check_out_date->format('Y년 m월 d일') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">숙박 기간</p>
                    <p class="font-medium">{{ $booking->nights }}박</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">연락처</p>
                    <p class="font-medium">{{ $booking->guest_phone }}</p>
                </div>
            </div>

            @if($booking->special_requests)
                <div class="mt-6">
                    <p class="text-sm text-gray-500 mb-1">특별 요청사항</p>
                    <p class="text-gray-700 bg-gray-50 rounded p-3">{{ $booking->special_requests }}</p>
                </div>
            @endif
        </div>

        <!-- Payment Details -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">결제 정보</h2>
            <div class="bg-gray-50 rounded-lg p-6">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">객실 요금 (₩{{ number_format($booking->room_price) }} x {{ $booking->nights }}박)</span>
                    <span>₩{{ number_format($booking->room_price * $booking->nights) }}</span>
                </div>
                <div class="flex justify-between pt-4 border-t font-bold text-lg">
                    <span>총 금액</span>
                    <span class="text-blue-600">₩{{ number_format($booking->total_price) }}</span>
                </div>
                @if($booking->paid_amount > 0)
                    <div class="flex justify-between mt-2 text-green-600">
                        <span>결제 완료</span>
                        <span>₩{{ number_format($booking->paid_amount) }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Cancellation Info -->
        @if($booking->status->value === 'cancelled')
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-4">취소 정보</h2>
                <div class="bg-red-50 rounded-lg p-6">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-700">취소일</span>
                        <span>{{ $booking->cancelled_at->format('Y-m-d H:i') }}</span>
                    </div>
                    @if($booking->refund_amount > 0)
                        <div class="flex justify-between font-semibold">
                            <span class="text-gray-700">환불 금액</span>
                            <span class="text-green-600">₩{{ number_format($booking->refund_amount) }}</span>
                        </div>
                    @endif
                    @if($booking->cancellation_reason)
                        <div class="mt-3 pt-3 border-t border-red-200">
                            <p class="text-sm text-gray-500 mb-1">취소 사유</p>
                            <p class="text-gray-700">{{ $booking->cancellation_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="flex gap-4">
            @if($booking->canWriteReview())
                <a href="{{ route('reviews.create', $booking->id) }}"
                   class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 font-semibold text-center">
                    리뷰 작성하기
                </a>
            @endif

            @if($booking->canBeCancelled())
                <form method="POST" action="{{ route('bookings.cancel', $booking->id) }}" class="flex-1"
                      onsubmit="return confirm('정말 예약을 취소하시겠습니까?\n\n취소 정책에 따라 환불 금액이 결정됩니다.\n예상 환불액: ₩{{ number_format($booking->calculateRefundAmount()) }}');">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 text-white px-6 py-3 rounded-md hover:bg-red-700 font-semibold">
                        예약 취소하기
                    </button>
                </form>
            @endif

            <a href="{{ route('accommodations.show', $booking->accommodation_id) }}"
               class="flex-1 bg-white text-blue-600 border-2 border-blue-600 px-6 py-3 rounded-md hover:bg-blue-50 font-semibold text-center">
                숙소 정보 보기
            </a>
        </div>

        <!-- Cancellation Policy -->
        @if($booking->status->canBeCancelled())
            <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
                <h3 class="font-semibold text-gray-900 mb-2">취소 정책</h3>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>• 체크인 7일 전 취소: 100% 환불</li>
                    <li>• 체크인 3-6일 전 취소: 50% 환불</li>
                    <li>• 체크인 3일 이내 취소: 환불 불가</li>
                </ul>
                <p class="mt-2 text-sm font-medium text-yellow-800">
                    현재 예상 환불액: ₩{{ number_format($booking->calculateRefundAmount()) }}
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
