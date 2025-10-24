@extends('layouts.app')

@section('title', '리뷰 작성 - OTA Service')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">리뷰 작성</h1>
        <p class="mt-2 text-gray-600">숙박 경험을 공유해주세요</p>
    </div>

    <!-- Booking Summary -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-start">
            @if($booking->accommodation->main_image)
                <img src="{{ asset('storage/' . $booking->accommodation->main_image) }}"
                     alt="{{ $booking->accommodation->name }}"
                     class="w-24 h-24 object-cover rounded-lg mr-4">
            @endif
            <div>
                <h3 class="text-lg font-semibold">{{ $booking->accommodation->name }}</h3>
                <p class="text-sm text-gray-600">{{ $booking->room->name }}</p>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $booking->check_in_date->format('Y-m-d') }} ~ {{ $booking->check_out_date->format('Y-m-d') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Review Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('reviews.store', $booking->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Overall Rating -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    전체 평점 <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-2">
                    @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="{{ $i }}" required
                                   {{ old('rating') == $i ? 'checked' : '' }}
                                   class="sr-only peer">
                            <svg class="w-10 h-10 text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-300 transition"
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </label>
                    @endfor
                </div>
                @error('rating')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category Ratings -->
            <div class="mb-6 border-t pt-6">
                <h3 class="text-lg font-semibold mb-4">항목별 평가 <span class="text-sm text-gray-500">(선택사항)</span></h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Cleanliness -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">청결도</label>
                        <select name="cleanliness_rating" class="w-full px-4 py-2 border rounded-lg">
                            <option value="">선택하세요</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ old('cleanliness_rating') == $i ? 'selected' : '' }}>{{ $i }}점</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Service -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">서비스</label>
                        <select name="service_rating" class="w-full px-4 py-2 border rounded-lg">
                            <option value="">선택하세요</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ old('service_rating') == $i ? 'selected' : '' }}>{{ $i }}점</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">위치</label>
                        <select name="location_rating" class="w-full px-4 py-2 border rounded-lg">
                            <option value="">선택하세요</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ old('location_rating') == $i ? 'selected' : '' }}>{{ $i }}점</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Value -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">가성비</label>
                        <select name="value_rating" class="w-full px-4 py-2 border rounded-lg">
                            <option value="">선택하세요</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ old('value_rating') == $i ? 'selected' : '' }}>{{ $i }}점</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <!-- Title -->
            <div class="mb-6 border-t pt-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    제목 <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                       placeholder="리뷰 제목을 입력하세요">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    리뷰 내용 <span class="text-red-500">*</span> <span class="text-gray-500">(최소 20자)</span>
                </label>
                <textarea name="content" id="content" rows="6" required
                          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('content') border-red-500 @enderror"
                          placeholder="숙박 경험을 자세히 알려주세요">{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Photos -->
            <div class="mb-6">
                <label for="photos" class="block text-sm font-medium text-gray-700 mb-2">
                    사진 첨부 <span class="text-gray-500">(선택, 최대 5장)</span>
                </label>
                <input type="file" name="photos[]" id="photos" multiple accept="image/*"
                       class="w-full px-4 py-2 border rounded-lg @error('photos') border-red-500 @enderror">
                <p class="mt-1 text-xs text-gray-500">각 사진은 최대 2MB까지 업로드 가능합니다</p>
                @error('photos')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-4 border-t pt-6">
                <a href="{{ route('bookings.show', $booking->id) }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    취소
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                    리뷰 등록
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
