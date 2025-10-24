@extends('layouts.app')

@section('title', $accommodation->name . ' - OTA Service')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Search Form -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6 sticky top-0 z-10">
        <form method="GET" action="{{ route('accommodations.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- City -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">목적지</label>
                    <input type="text"
                           name="city"
                           value="{{ $accommodation->city }}"
                           placeholder="어디로 떠나시나요?"
                           class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Check-in Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">체크인</label>
                    <input type="date"
                           name="check_in"
                           value="{{ request('check_in', $checkIn) }}"
                           min="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Check-out Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">체크아웃</label>
                    <input type="date"
                           name="check_out"
                           value="{{ request('check_out', $checkOut) }}"
                           min="{{ request('check_in', date('Y-m-d', strtotime('+1 day'))) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Search Button -->
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 font-semibold flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        검색
                    </button>
                </div>
            </div>
        </form>
    </div>

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

            <!-- Reviews Section -->
            @if($accommodation->total_reviews > 0)
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">리뷰 {{ number_format($accommodation->total_reviews) }}개</h2>

                    <!-- Review Statistics -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Overall Rating -->
                            <div class="flex items-center">
                                <div class="mr-6">
                                    <div class="text-5xl font-bold text-gray-900">{{ number_format($accommodation->average_rating, 1) }}</div>
                                    <div class="flex items-center mt-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= round($accommodation->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}"
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                    </div>
                                    <div class="text-sm text-gray-600 mt-1">{{ number_format($accommodation->total_reviews) }}개 리뷰</div>
                                </div>
                            </div>

                            <!-- Rating Distribution -->
                            <div class="space-y-2">
                                @foreach([5, 4, 3, 2, 1] as $rating)
                                    @php
                                        $count = $ratingDistribution[$rating] ?? 0;
                                        $percentage = $accommodation->total_reviews > 0
                                            ? round(($count / $accommodation->total_reviews) * 100)
                                            : 0;
                                    @endphp
                                    <div class="flex items-center text-sm">
                                        <span class="w-8 text-gray-700">{{ $rating }}점</span>
                                        <div class="flex-1 mx-3 bg-gray-200 rounded-full h-2">
                                            <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="w-12 text-right text-gray-600">{{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Review Filters and Sort -->
                    <div class="mb-6 flex flex-wrap gap-3 items-center justify-between">
                        <div class="flex flex-wrap gap-2">
                            <!-- Rating Filter -->
                            <select onchange="window.location.href=this.value"
                                    class="px-4 py-2 border rounded-lg text-sm">
                                <option value="{{ request()->fullUrlWithQuery(['review_rating' => null]) }}"
                                        {{ !request('review_rating') ? 'selected' : '' }}>
                                    모든 평점
                                </option>
                                @foreach([5, 4, 3, 2, 1] as $rating)
                                    <option value="{{ request()->fullUrlWithQuery(['review_rating' => $rating]) }}"
                                            {{ request('review_rating') == $rating ? 'selected' : '' }}>
                                        {{ $rating }}점
                                    </option>
                                @endforeach
                            </select>

                            <!-- Photos Filter -->
                            <a href="{{ request()->fullUrlWithQuery(['with_photos' => request('with_photos') === '1' ? null : '1']) }}"
                               class="px-4 py-2 border rounded-lg text-sm {{ request('with_photos') === '1' ? 'bg-blue-50 border-blue-500 text-blue-700' : 'hover:bg-gray-50' }}">
                                📷 사진 리뷰만
                            </a>
                        </div>

                        <!-- Sort Options -->
                        <select onchange="window.location.href=this.value"
                                class="px-4 py-2 border rounded-lg text-sm">
                            <option value="{{ request()->fullUrlWithQuery(['review_sort' => 'newest']) }}"
                                    {{ request('review_sort', 'newest') === 'newest' ? 'selected' : '' }}>
                                최신순
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['review_sort' => 'helpful']) }}"
                                    {{ request('review_sort') === 'helpful' ? 'selected' : '' }}>
                                도움된 순
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['review_sort' => 'rating_high']) }}"
                                    {{ request('review_sort') === 'rating_high' ? 'selected' : '' }}>
                                평점 높은 순
                            </option>
                            <option value="{{ request()->fullUrlWithQuery(['review_sort' => 'rating_low']) }}"
                                    {{ request('review_sort') === 'rating_low' ? 'selected' : '' }}>
                                평점 낮은 순
                            </option>
                        </select>
                    </div>

                    <!-- Review List -->
                    <div class="space-y-6">
                        @forelse($reviews as $review)
                            <div class="border-b border-gray-200 pb-6 last:border-b-0">
                                <!-- Review Header -->
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                            {{ mb_substr($review->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $review->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $review->created_at->format('Y-m-d') }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Review Title -->
                                @if($review->title)
                                    <h3 class="font-semibold text-gray-900 mb-2">{{ $review->title }}</h3>
                                @endif

                                <!-- Review Content -->
                                <p class="text-gray-700 mb-3 leading-relaxed">{{ $review->content }}</p>

                                <!-- Category Ratings -->
                                @if($review->cleanliness_rating || $review->service_rating || $review->location_rating || $review->value_rating)
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-3 text-sm">
                                        @if($review->cleanliness_rating)
                                            <div class="flex items-center text-gray-600">
                                                <span class="mr-1">청결도</span>
                                                <span class="font-semibold">{{ $review->cleanliness_rating }}</span>
                                            </div>
                                        @endif
                                        @if($review->service_rating)
                                            <div class="flex items-center text-gray-600">
                                                <span class="mr-1">서비스</span>
                                                <span class="font-semibold">{{ $review->service_rating }}</span>
                                            </div>
                                        @endif
                                        @if($review->location_rating)
                                            <div class="flex items-center text-gray-600">
                                                <span class="mr-1">위치</span>
                                                <span class="font-semibold">{{ $review->location_rating }}</span>
                                            </div>
                                        @endif
                                        @if($review->value_rating)
                                            <div class="flex items-center text-gray-600">
                                                <span class="mr-1">가성비</span>
                                                <span class="font-semibold">{{ $review->value_rating }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Review Photos -->
                                @if($review->photos && count($review->photos) > 0)
                                    <div class="flex gap-2 mb-3 overflow-x-auto">
                                        @foreach($review->photos as $photo)
                                            <img src="{{ asset('storage/' . $photo) }}"
                                                 alt="리뷰 사진"
                                                 class="w-24 h-24 object-cover rounded-lg cursor-pointer hover:opacity-75"
                                                 onclick="window.open(this.src)">
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Accommodation Response -->
                                @if($review->response)
                                    <div class="mt-3 bg-blue-50 rounded-lg p-4">
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                            </svg>
                                            <div class="flex-1">
                                                <div class="font-semibold text-blue-900 mb-1">숙소 측 답변</div>
                                                <p class="text-blue-800 text-sm">{{ $review->response }}</p>
                                                @if($review->responded_at)
                                                    <p class="text-xs text-blue-600 mt-1">{{ $review->responded_at->format('Y-m-d') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Helpful Button -->
                                <div class="mt-3 flex items-center">
                                    @auth
                                        <form action="{{ route('reviews.helpful', $review->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-sm text-gray-600 hover:text-blue-600 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                                </svg>
                                                도움됨 ({{ $review->helpful_count }})
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-sm text-gray-500 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                            </svg>
                                            도움됨 ({{ $review->helpful_count }})
                                        </span>
                                    @endauth
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                선택한 조건에 맞는 리뷰가 없습니다.
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($reviews->hasPages())
                        <div class="mt-6">
                            {{ $reviews->links() }}
                        </div>
                    @endif
                </section>
            @else
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">리뷰</h2>
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        <p class="text-gray-600">아직 작성된 리뷰가 없습니다.</p>
                        <p class="text-sm text-gray-500 mt-1">첫 번째 리뷰를 작성해보세요!</p>
                    </div>
                </section>
            @endif
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
                               id="check_in_input"
                               value="{{ $checkIn }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">체크아웃</label>
                        <input type="date"
                               id="check_out_input"
                               value="{{ $checkOut }}"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">투숙객</label>
                        <select id="guests_input" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="1" {{ $guests == 1 ? 'selected' : '' }}>1명</option>
                            <option value="2" {{ $guests == 2 ? 'selected' : '' }}>2명</option>
                            <option value="3" {{ $guests == 3 ? 'selected' : '' }}>3명</option>
                            <option value="4" {{ $guests == 4 ? 'selected' : '' }}>4명</option>
                            <option value="5" {{ $guests >= 5 ? 'selected' : '' }}>5명 이상</option>
                        </select>
                    </div>
                    <button onclick="updateBookingLinks()" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 font-semibold">
                        날짜 적용
                    </button>
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

<script>
function updateBookingLinks() {
    const checkIn = document.getElementById('check_in_input').value;
    const checkOut = document.getElementById('check_out_input').value;
    const guests = document.getElementById('guests_input').value;

    // Update URL with new parameters
    const url = new URL(window.location);
    url.searchParams.set('check_in', checkIn);
    url.searchParams.set('check_out', checkOut);
    url.searchParams.set('guests', guests);

    // Reload page with new parameters
    window.location.href = url.toString();
}
</script>
@endsection
