@extends('layouts.app')

@section('title', '숙소 검색 - OTA Service')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Hero Section with Search -->
    <div class="text-center py-16 bg-gradient-to-r from-blue-500 to-blue-600 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 rounded-lg mb-8">
        <h1 class="text-4xl font-bold text-white mb-4">
            완벽한 숙소를 찾아보세요
        </h1>
        <p class="text-xl text-blue-100 mb-8">
            전국의 다양한 숙박시설을 검색하고 비교하세요
        </p>

        <!-- Search Form -->
        <form action="{{ route('accommodations.index') }}" method="GET" class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Destination -->
                    <div class="text-left">
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">목적지</label>
                        <input type="text"
                               id="city"
                               name="city"
                               placeholder="도시명 입력"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Check-in -->
                    <div class="text-left">
                        <label for="check_in" class="block text-sm font-medium text-gray-700 mb-2">체크인</label>
                        <input type="date"
                               id="check_in"
                               name="check_in"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Check-out -->
                    <div class="text-left">
                        <label for="check_out" class="block text-sm font-medium text-gray-700 mb-2">체크아웃</label>
                        <input type="date"
                               id="check_out"
                               name="check_out"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Guests -->
                    <div class="text-left">
                        <label for="guests" class="block text-sm font-medium text-gray-700 mb-2">투숙객</label>
                        <select id="guests"
                                name="guests"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="1">1명</option>
                            <option value="2" selected>2명</option>
                            <option value="3">3명</option>
                            <option value="4">4명</option>
                            <option value="5">5명 이상</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full md:w-auto px-8 py-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        검색
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Popular Destinations -->
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">인기 여행지</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('accommodations.index', ['city' => '서울']) }}" class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow p-4">
                <div class="text-center">
                    <div class="text-4xl mb-2">🏙️</div>
                    <h3 class="font-semibold text-gray-900">서울</h3>
                </div>
            </a>
            <a href="{{ route('accommodations.index', ['city' => '부산']) }}" class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow p-4">
                <div class="text-center">
                    <div class="text-4xl mb-2">🌊</div>
                    <h3 class="font-semibold text-gray-900">부산</h3>
                </div>
            </a>
            <a href="{{ route('accommodations.index', ['city' => '제주']) }}" class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow p-4">
                <div class="text-center">
                    <div class="text-4xl mb-2">🏝️</div>
                    <h3 class="font-semibold text-gray-900">제주</h3>
                </div>
            </a>
            <a href="{{ route('accommodations.index', ['city' => '강릉']) }}" class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow p-4">
                <div class="text-center">
                    <div class="text-4xl mb-2">🏖️</div>
                    <h3 class="font-semibold text-gray-900">강릉</h3>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
