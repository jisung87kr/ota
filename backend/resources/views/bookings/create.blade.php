@extends('layouts.app')

@section('title', '예약하기 - ' . $accommodation->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('accommodations.show', $accommodation->id) }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            숙소 상세로 돌아가기
        </a>
    </div>

    <h1 class="text-3xl font-bold text-gray-900 mb-8">예약 정보 입력</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Booking Form -->
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('bookings.store', [$accommodation->id, $room->id]) }}" class="bg-white rounded-lg shadow p-6">
                @csrf

                <input type="hidden" name="check_in_date" value="{{ $checkIn }}">
                <input type="hidden" name="check_out_date" value="{{ $checkOut }}">
                <input type="hidden" name="guests" value="{{ $guests }}">

                <h2 class="text-xl font-bold text-gray-900 mb-6">투숙객 정보</h2>

                <!-- Guest Name -->
                <div class="mb-4">
                    <label for="guest_name" class="block text-sm font-medium text-gray-700 mb-2">투숙객 이름 *</label>
                    <input type="text"
                           id="guest_name"
                           name="guest_name"
                           value="{{ old('guest_name', Auth::user()->name) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 @error('guest_name') border-red-500 @enderror"
                           required>
                    @error('guest_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Guest Email -->
                <div class="mb-4">
                    <label for="guest_email" class="block text-sm font-medium text-gray-700 mb-2">이메일 *</label>
                    <input type="email"
                           id="guest_email"
                           name="guest_email"
                           value="{{ old('guest_email', Auth::user()->email) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 @error('guest_email') border-red-500 @enderror"
                           required>
                    @error('guest_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Guest Phone -->
                <div class="mb-4">
                    <label for="guest_phone" class="block text-sm font-medium text-gray-700 mb-2">전화번호 *</label>
                    <input type="tel"
                           id="guest_phone"
                           name="guest_phone"
                           value="{{ old('guest_phone', Auth::user()->phone) }}"
                           placeholder="010-1234-5678"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 @error('guest_phone') border-red-500 @enderror"
                           required>
                    @error('guest_phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Special Requests -->
                <div class="mb-6">
                    <label for="special_requests" class="block text-sm font-medium text-gray-700 mb-2">특별 요청사항 (선택)</label>
                    <textarea id="special_requests"
                              name="special_requests"
                              rows="4"
                              placeholder="예: 고층 객실 희망, 조용한 객실 희망 등"
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">{{ old('special_requests') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">요청사항은 숙소 측에 전달되지만, 보장되지 않을 수 있습니다.</p>
                </div>

                <!-- Terms Agreement -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-semibold text-gray-900 mb-2">취소 정책</h3>
                    <ul class="text-sm text-gray-700 space-y-1">
                        <li>• 체크인 7일 전 취소: 100% 환불</li>
                        <li>• 체크인 3-6일 전 취소: 50% 환불</li>
                        <li>• 체크인 3일 이내 취소: 환불 불가</li>
                    </ul>
                    <label class="flex items-start mt-4">
                        <input type="checkbox" required class="mt-1 mr-2">
                        <span class="text-sm text-gray-700">취소 정책 및 이용약관에 동의합니다.</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-blue-600 text-white py-3 px-6 rounded-md hover:bg-blue-700 font-semibold">
                    예약 확정하기
                </button>
            </form>
        </div>

        <!-- Booking Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                <h2 class="text-xl font-bold text-gray-900 mb-4">예약 요약</h2>

                <div class="mb-4">
                    <h3 class="font-semibold text-gray-900">{{ $accommodation->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $accommodation->city }}</p>
                </div>

                <div class="mb-4 pb-4 border-b">
                    <h4 class="font-semibold text-gray-900 mb-2">{{ $room->name }}</h4>
                    <div class="text-sm text-gray-700 space-y-1">
                        <p>최대 {{ $room->max_occupancy }}명</p>
                        @if($room->bed_type)
                            <p>{{ $room->bed_type }} {{ $room->bed_count }}개</p>
                        @endif
                    </div>
                </div>

                <div class="space-y-3 mb-4 pb-4 border-b">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">체크인</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($checkIn)->format('Y년 m월 d일') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">체크아웃</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($checkOut)->format('Y년 m월 d일') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">숙박 기간</span>
                        <span class="font-medium">{{ $nights }}박</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">투숙객</span>
                        <span class="font-medium">{{ $guests }}명</span>
                    </div>
                </div>

                <div class="space-y-2 mb-4 pb-4 border-b">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">₩{{ number_format($room->base_price) }} x {{ $nights }}박</span>
                        <span>₩{{ number_format($room->base_price * $nights) }}</span>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold">총 금액</span>
                    <span class="text-2xl font-bold text-blue-600">₩{{ number_format($totalPrice) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
