@extends('layouts.app')

@section('title', '예약 완료')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
        <!-- Success Icon -->
        <div class="mb-6">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-2">예약이 완료되었습니다!</h1>
        <p class="text-gray-600 mb-8">예약 확인 정보가 이메일로 전송되었습니다.</p>

        <!-- Booking Number -->
        <div class="bg-blue-50 rounded-lg p-6 mb-8">
            <p class="text-sm text-gray-600 mb-1">예약 번호</p>
            <p class="text-2xl font-bold text-blue-600">{{ $booking->booking_number }}</p>
        </div>

        <!-- Booking Details -->
        <div class="text-left mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">예약 정보</h2>

            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">숙소명</span>
                    <span class="font-medium">{{ $booking->accommodation->name }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">객실</span>
                    <span class="font-medium">{{ $booking->room->name }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">체크인</span>
                    <span class="font-medium">{{ $booking->check_in_date->format('Y년 m월 d일') }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">체크아웃</span>
                    <span class="font-medium">{{ $booking->check_out_date->format('Y년 m월 d일') }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">투숙객</span>
                    <span class="font-medium">{{ $booking->guest_name }} ({{ $booking->guests }}명)</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">총 금액</span>
                    <span class="text-xl font-bold text-blue-600">₩{{ number_format($booking->total_price) }}</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('bookings.show', $booking->id) }}"
               class="bg-blue-600 text-white px-8 py-3 rounded-md hover:bg-blue-700 font-semibold">
                예약 상세 보기
            </a>
            <a href="{{ route('bookings.index') }}"
               class="bg-white text-blue-600 border-2 border-blue-600 px-8 py-3 rounded-md hover:bg-blue-50 font-semibold">
                내 예약 목록
            </a>
        </div>
    </div>
</div>
@endsection
