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

        @guest
            <div class="mt-8 flex justify-center gap-4">
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-8 py-3 rounded-md hover:bg-blue-700 text-lg font-medium">
                    지금 시작하기
                </a>
                <a href="{{ route('login') }}" class="bg-white text-blue-600 border-2 border-blue-600 px-8 py-3 rounded-md hover:bg-blue-50 text-lg font-medium">
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
