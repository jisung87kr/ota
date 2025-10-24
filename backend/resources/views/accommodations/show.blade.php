@extends('layouts.app')

@section('title', $accommodation->name . ' - OTA Service')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ url()->previous() }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            검색 결과로 돌아가기
        </a>
    </div>

    <!-- Accommodation Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $accommodation->name }}</h1>
        <div class="flex items-center text-gray-600">
            @if($accommodation->average_rating > 0)
                <span class="text-yellow-500 mr-1">★</span>
                <span class="font-semibold mr-1">{{ number_format($accommodation->average_rating, 1) }}</span>
                <span class="mr-3">({{ $accommodation->total_reviews }}개 리뷰)</span>
            @endif
            <span>{{ $accommodation->address }}</span>
        </div>
    </div>

    <!-- Image Gallery -->
    <div class="mb-8">
        @if($accommodation->images->count() > 0)
            <div class="grid grid-cols-4 gap-2 h-96">
                <div class="col-span-2 row-span-2">
                    <img src="{{ asset('storage/' . $accommodation->images->first()->path) }}"
                         alt="{{ $accommodation->name }}"
                         class="w-full h-full object-cover rounded-l-lg">
                </div>
                @foreach($accommodation->images->skip(1)->take(4) as $image)
                    <div class="{{ $loop->last ? 'rounded-tr-lg' : '' }} {{ $loop->iteration == 4 ? 'rounded-br-lg' : '' }}">
                        <img src="{{ asset('storage/' . $image->path) }}"
                             alt="{{ $accommodation->name }}"
                             class="w-full h-full object-cover {{ $loop->last ? 'rounded-tr-lg' : '' }} {{ $loop->iteration == 4 ? 'rounded-br-lg' : '' }}">
                    </div>
                @endforeach
            </div>
        @else
            <div class="h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                <div class="text-center text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p>이미지가 없습니다</p>
                </div>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Description -->
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">숙소 소개</h2>
                <p class="text-gray-700 leading-relaxed">{{ $accommodation->description }}</p>
            </section>

            <!-- Amenities -->
            @if($accommodation->amenities->count() > 0)
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">편의시설</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($accommodation->amenities as $amenity)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">{{ $amenity->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- Rooms -->
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">객실 선택</h2>

                @if($accommodation->activeRooms->count() > 0)
                    <div class="space-y-4">
                        @foreach($accommodation->activeRooms as $room)
                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow">
                                <div class="flex flex-col md:flex-row gap-6">
                                    <!-- Room Image -->
                                    <div class="md:w-1/3">
                                        @if($room->main_image)
                                            <img src="{{ asset('storage/' . $room->main_image) }}"
                                                 alt="{{ $room->name }}"
                                                 class="w-full h-48 object-cover rounded-lg">
                                        @else
                                            <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Room Info -->
                                    <div class="md:w-2/3 flex flex-col justify-between">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $room->name }}</h3>
                                            @if($room->description)
                                                <p class="text-gray-600 text-sm mb-3">{{ $room->description }}</p>
                                            @endif

                                            <div class="flex flex-wrap gap-4 text-sm text-gray-700 mb-3">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                    최대 {{ $room->max_occupancy }}명
                                                </div>
                                                @if($room->size)
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                                        </svg>
                                                        {{ $room->size }}㎡
                                                    </div>
                                                @endif
                                                @if($room->bed_type)
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                                        </svg>
                                                        {{ $room->bed_type }} {{ $room->bed_count }}개
                                                    </div>
                                                @endif
                                            </div>

                                            @if($room->amenities->count() > 0)
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($room->amenities->take(5) as $amenity)
                                                        <span class="text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded">{{ $amenity->name }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex items-end justify-between mt-4">
                                            <div>
                                                <p class="text-2xl font-bold text-blue-600">₩{{ number_format($room->base_price) }}</p>
                                                <p class="text-sm text-gray-600">/ 1박</p>
                                            </div>
                                            @auth
                                                <a href="{{ route('bookings.create', ['accommodation' => $accommodation->id, 'room' => $room->id, 'check_in' => $checkIn, 'check_out' => $checkOut, 'guests' => $guests]) }}"
                                                   class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 inline-block">
                                                    예약하기
                                                </a>
                                            @else
                                                <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                                                    로그인 후 예약
                                                </a>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <p class="text-gray-600">현재 예약 가능한 객실이 없습니다</p>
                    </div>
                @endif
            </section>

            <!-- Location -->
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">위치</h2>
                <div class="bg-gray-200 h-64 rounded-lg flex items-center justify-center">
                    <p class="text-gray-600">지도가 여기에 표시됩니다</p>
                    <!-- TODO: Integrate Google Maps or Kakao Maps -->
                </div>
                <p class="mt-2 text-gray-700">{{ $accommodation->address }}</p>
            </section>
        </div>

        <!-- Sidebar - Booking Card -->
        <div class="lg:col-span-1">
            <div class="bg-white border border-gray-200 rounded-lg p-6 sticky top-4">
                <div class="mb-4">
                    <p class="text-sm text-gray-600">1박당</p>
                    <p class="text-3xl font-bold text-blue-600">₩{{ number_format($accommodation->min_price) }}</p>
                </div>

                @if($accommodation->average_rating > 0)
                    <div class="flex items-center mb-4 pb-4 border-b">
                        <span class="text-yellow-500 mr-1">★</span>
                        <span class="font-semibold mr-1">{{ number_format($accommodation->average_rating, 1) }}</span>
                        <span class="text-gray-600 text-sm">({{ $accommodation->total_reviews }}개 리뷰)</span>
                    </div>
                @endif

                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">체크인</label>
                        <input type="date"
                               value="{{ $checkIn }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">체크아웃</label>
                        <input type="date"
                               value="{{ $checkOut }}"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">투숙객</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="1" {{ $guests == 1 ? 'selected' : '' }}>1명</option>
                            <option value="2" {{ $guests == 2 ? 'selected' : '' }}>2명</option>
                            <option value="3" {{ $guests == 3 ? 'selected' : '' }}>3명</option>
                            <option value="4" {{ $guests == 4 ? 'selected' : '' }}>4명</option>
                            <option value="5" {{ $guests >= 5 ? 'selected' : '' }}>5명 이상</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4 pb-4 border-b">
                    <div class="flex justify-between text-sm mb-2">
                        <span>객실 선택 후</span>
                        <span>가격이 표시됩니다</span>
                    </div>
                </div>

                <p class="text-xs text-gray-600 text-center">
                    아래 객실 목록에서 원하는 객실을 선택하세요
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
