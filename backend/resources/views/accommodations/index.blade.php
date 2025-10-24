@extends('layouts.app')

@section('title', '숙소 검색 결과 - OTA Service')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Search Form -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8 sticky top-0 z-10">
        <form method="GET" action="{{ route('accommodations.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- City -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">목적지</label>
                    <input type="text"
                           name="city"
                           value="{{ request('city') }}"
                           placeholder="어디로 떠나시나요?"
                           class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Check-in Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">체크인</label>
                    <input type="date"
                           name="check_in"
                           value="{{ request('check_in') }}"
                           min="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Check-out Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">체크아웃</label>
                    <input type="date"
                           name="check_out"
                           value="{{ request('check_out') }}"
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

            <!-- Preserve filter parameters -->
            @if(request('min_price'))
                <input type="hidden" name="min_price" value="{{ request('min_price') }}">
            @endif
            @if(request('max_price'))
                <input type="hidden" name="max_price" value="{{ request('max_price') }}">
            @endif
            @if(request('rating'))
                <input type="hidden" name="rating" value="{{ request('rating') }}">
            @endif
            @if(request('amenities'))
                @foreach(request('amenities') as $amenity)
                    <input type="hidden" name="amenities[]" value="{{ $amenity }}">
                @endforeach
            @endif
            @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif
        </form>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Filters Sidebar -->
        <aside class="lg:w-1/4">
            <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                <h2 class="text-xl font-bold text-gray-900 mb-4">필터</h2>

                <form method="GET" action="{{ route('accommodations.index') }}">
                    @if(request('city'))
                        <input type="hidden" name="city" value="{{ request('city') }}">
                    @endif
                    @if(request('check_in'))
                        <input type="hidden" name="check_in" value="{{ request('check_in') }}">
                    @endif
                    @if(request('check_out'))
                        <input type="hidden" name="check_out" value="{{ request('check_out') }}">
                    @endif

                    <!-- Price Range -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-3">가격 범위</h3>
                        <div class="flex gap-2">
                            <input type="number"
                                   name="min_price"
                                   placeholder="최소"
                                   value="{{ request('min_price') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                            <span class="self-center">-</span>
                            <input type="number"
                                   name="max_price"
                                   placeholder="최대"
                                   value="{{ request('max_price') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        </div>
                    </div>

                    <!-- Rating -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-3">평점</h3>
                        <div class="space-y-2">
                            @foreach([4.5, 4.0, 3.5, 3.0] as $rating)
                                <label class="flex items-center">
                                    <input type="radio"
                                           name="rating"
                                           value="{{ $rating }}"
                                           {{ request('rating') == $rating ? 'checked' : '' }}
                                           class="mr-2">
                                    <span class="text-sm">{{ $rating }}점 이상</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Amenities -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-3">편의시설</h3>
                        <div class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach($amenities as $amenity)
                                <label class="flex items-center">
                                    <input type="checkbox"
                                           name="amenities[]"
                                           value="{{ $amenity->id }}"
                                           {{ in_array($amenity->id, request('amenities', [])) ? 'checked' : '' }}
                                           class="mr-2">
                                    <span class="text-sm">{{ $amenity->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700">
                        필터 적용
                    </button>
                    <a href="{{ route('accommodations.index', request()->except(['rating', 'min_price', 'max_price', 'amenities'])) }}"
                       class="block w-full text-center mt-2 text-sm text-gray-600 hover:text-gray-900">
                        필터 초기화
                    </a>
                </form>
            </div>
        </aside>

        <!-- Results -->
        <main class="lg:w-3/4">
            <!-- Search Summary and Sort -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        @if(request('city'))
                            {{ request('city') }} 숙소
                        @else
                            전체 숙소
                        @endif
                    </h1>
                    <p class="text-gray-600 mt-1">{{ $accommodations->total() }}개의 숙소</p>
                </div>

                <div>
                    <form method="GET" action="{{ route('accommodations.index') }}" class="inline">
                        @foreach(request()->except('sort') as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $v)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <select name="sort"
                                onchange="this.form.submit()"
                                class="px-4 py-2 border border-gray-300 rounded-md">
                            <option value="recommended" {{ request('sort') == 'recommended' ? 'selected' : '' }}>추천순</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>낮은 가격순</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>높은 가격순</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>평점 높은 순</option>
                            <option value="reviews" {{ request('sort') == 'reviews' ? 'selected' : '' }}>리뷰 많은 순</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Accommodation Cards -->
            @if($accommodations->count() > 0)
                <div class="space-y-4">
                    @foreach($accommodations as $accommodation)
                        <a href="{{ route('accommodations.show', ['id' => $accommodation->id, 'check_in' => request('check_in'), 'check_out' => request('check_out'), 'guests' => request('guests')]) }}"
                           class="block bg-white rounded-lg shadow hover:shadow-lg transition-shadow overflow-hidden">
                            <div class="flex flex-col sm:flex-row">
                                <!-- Image -->
                                <div class="sm:w-1/3 h-48 sm:h-auto bg-gray-200">
                                    @if($accommodation->main_image)
                                        <img src="{{ asset('storage/' . $accommodation->main_image) }}"
                                             alt="{{ $accommodation->name }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Info -->
                                <div class="sm:w-2/3 p-6">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $accommodation->name }}</h3>
                                            <p class="text-sm text-gray-600 mb-2">{{ $accommodation->city }}</p>

                                            <!-- Rating -->
                                            @if($accommodation->average_rating > 0)
                                                <div class="flex items-center mb-2">
                                                    <span class="text-yellow-500 mr-1">★</span>
                                                    <span class="font-semibold">{{ number_format($accommodation->average_rating, 1) }}</span>
                                                    <span class="text-gray-600 text-sm ml-1">({{ $accommodation->total_reviews }}개 리뷰)</span>
                                                </div>
                                            @endif

                                            <!-- Amenities -->
                                            @if($accommodation->amenities->count() > 0)
                                                <div class="flex flex-wrap gap-2 mb-3">
                                                    @foreach($accommodation->amenities->take(3) as $amenity)
                                                        <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ $amenity->name }}</span>
                                                    @endforeach
                                                    @if($accommodation->amenities->count() > 3)
                                                        <span class="text-xs text-gray-500">+{{ $accommodation->amenities->count() - 3 }}개</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Price -->
                                        <div class="text-right ml-4">
                                            <p class="text-2xl font-bold text-blue-600">
                                                ₩{{ number_format($accommodation->min_price) }}
                                            </p>
                                            <p class="text-sm text-gray-600">/ 1박</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $accommodations->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">검색 결과가 없습니다</h3>
                    <p class="text-gray-600 mb-4">다른 조건으로 검색해보세요</p>
                    <a href="{{ route('accommodations.search') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                        새로 검색하기
                    </a>
                </div>
            @endif
        </main>
    </div>
</div>
@endsection
