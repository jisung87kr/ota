@extends('layouts.app')

@section('title', '홈 - OTA Service')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Hero Section -->
    <div class="text-center py-16">
        <h1 class="text-4xl font-bold text-gray-900 sm:text-5xl md:text-6xl">
            완벽한 숙소를 찾아보세요
        </h1>
        <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
            전국의 다양한 숙박시설을 한눈에 비교하고 예약하세요
        </p>

        <!-- Quick Search Bar -->
        <div class="mt-8 max-w-4xl mx-auto">
            <form action="{{ route('accommodations.index') }}" method="GET" class="bg-white rounded-lg shadow-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="text"
                           name="city"
                           placeholder="어디로 떠나시나요?"
                           class="px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <input type="date"
                           name="check_in"
                           min="{{ date('Y-m-d') }}"
                           placeholder="체크인"
                           class="px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <input type="date"
                           name="check_out"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           placeholder="체크아웃"
                           class="px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 font-semibold">
                        검색
                    </button>
                </div>
            </form>
            <div class="mt-4 text-center">
                <a href="{{ route('accommodations.search') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    상세 검색 옵션 보기 →
                </a>
            </div>
        </div>

        @guest
            <div class="mt-8 flex justify-center gap-4">
                <a href="{{ route('register') }}" class="bg-white text-blue-600 border-2 border-blue-600 px-8 py-3 rounded-md hover:bg-blue-50 text-lg font-medium">
                    회원가입
                </a>
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-8 py-3 rounded-md text-lg font-medium">
                    로그인
                </a>
            </div>
        @endguest
    </div>

    <!-- Features Section -->
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Feature 1 -->
                <div class="text-center">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-600 text-white mx-auto">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">간편한 검색</h3>
                    <p class="mt-2 text-base text-gray-500">
                        원하는 지역과 날짜로 쉽고 빠르게 숙소를 찾아보세요
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="text-center">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-600 text-white mx-auto">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">안전한 예약</h3>
                    <p class="mt-2 text-base text-gray-500">
                        검증된 숙소만을 제공하여 안심하고 예약하실 수 있습니다
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="text-center">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-600 text-white mx-auto">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">최저가 보장</h3>
                    <p class="mt-2 text-base text-gray-500">
                        합리적인 가격으로 최고의 숙박 경험을 제공합니다
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommended Accommodations Section -->
    @if($recommendedAccommodations->count() > 0)
        <div class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-gray-900">추천 숙소</h2>
                    <p class="mt-2 text-gray-600">높은 평점을 받은 인기 숙소를 확인해보세요</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($recommendedAccommodations as $accommodation)
                        <a href="{{ route('accommodations.show', $accommodation->id) }}"
                           class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <!-- Image -->
                            <div class="h-48 bg-gray-200 relative">
                                @if($accommodation->main_image)
                                    <img src="{{ asset('storage/' . $accommodation->main_image) }}"
                                         alt="{{ $accommodation->name }}"
                                         class="w-full h-full object-cover">
                                @elseif($accommodation->images->first())
                                    <img src="{{ asset('storage/' . $accommodation->images->first()->path) }}"
                                         alt="{{ $accommodation->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Rating Badge -->
                                @if($accommodation->average_rating > 0)
                                    <div class="absolute top-3 right-3 bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        {{ number_format($accommodation->average_rating, 1) }}
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="p-5">
                                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1">{{ $accommodation->name }}</h3>

                                <div class="flex items-center text-sm text-gray-600 mb-2">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $accommodation->city }}
                                </div>

                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $accommodation->description }}</p>

                                <div class="flex items-center justify-between pt-3 border-t">
                                    <div>
                                        @if($accommodation->activeRooms->count() > 0)
                                            <span class="text-sm text-gray-500">최저가</span>
                                            <p class="text-xl font-bold text-blue-600">
                                                ₩{{ number_format($accommodation->activeRooms->min('base_price')) }}
                                            </p>
                                            <span class="text-xs text-gray-500">/ 1박</span>
                                        @else
                                            <span class="text-sm text-gray-500">가격 문의</span>
                                        @endif
                                    </div>

                                    @if($accommodation->total_reviews > 0)
                                        <div class="text-right">
                                            <div class="flex items-center text-yellow-500">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= round($accommodation->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}"
                                                         fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="text-xs text-gray-500">{{ $accommodation->total_reviews }}개 리뷰</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="text-center mt-10">
                    <a href="{{ route('accommodations.index') }}"
                       class="inline-block bg-blue-600 text-white px-8 py-3 rounded-md hover:bg-blue-700 font-semibold">
                        모든 숙소 보기
                    </a>
                </div>
            </div>
        </div>
    @endif

    @auth
        <div class="mt-12 bg-blue-50 rounded-lg p-8 text-center">
            <h2 class="text-2xl font-bold text-gray-900">환영합니다, {{ Auth::user()->name }}님!</h2>
            <p class="mt-2 text-gray-600">이제 다양한 숙소를 검색하고 예약할 수 있습니다.</p>
            <div class="mt-6">
                @if(Auth::user()->isCustomer())
                    <a href="{{ route('customer.dashboard') }}" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700">
                        내 대시보드 가기
                    </a>
                @elseif(Auth::user()->isAccommodationManager())
                    <a href="{{ route('manager.dashboard') }}" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700">
                        숙소 관리하기
                    </a>
                @elseif(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700">
                        관리자 대시보드
                    </a>
                @endif
            </div>
        </div>
    @endauth
</div>
@endsection
