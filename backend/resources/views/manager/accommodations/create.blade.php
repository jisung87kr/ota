@extends('layouts.app')

@section('title', '새 숙소 등록 - OTA Service')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">새 숙소 등록</h1>
        <p class="mt-2 text-gray-600">숙소 정보를 입력하고 등록하세요</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('manager.accommodations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Basic Information -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">기본 정보</h2>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            숙소명 <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            카테고리 <span class="text-red-500">*</span>
                        </label>
                        <select name="category" id="category" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('category') border-red-500 @enderror">
                            <option value="">선택하세요</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>
                                    {{ ucfirst($cat) }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            숙소 설명 <span class="text-red-500">*</span> <span class="text-gray-500">(최소 50자)</span>
                        </label>
                        <textarea name="description" id="description" rows="5" required
                                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Main Image -->
                    <div>
                        <label for="main_image" class="block text-sm font-medium text-gray-700 mb-2">
                            대표 이미지 <span class="text-gray-500">(최대 2MB)</span>
                        </label>
                        <input type="file" name="main_image" id="main_image" accept="image/*"
                               class="w-full px-4 py-2 border rounded-lg @error('main_image') border-red-500 @enderror">
                        @error('main_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Location Information -->
            <div class="mb-8 border-t pt-8">
                <h2 class="text-xl font-semibold mb-4">위치 정보</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- City -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                            도시 <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="city" id="city" value="{{ old('city') }}" required
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('city') border-red-500 @enderror"
                               placeholder="예: 서울, 부산, 제주">
                        @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            상세 주소 <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="address" id="address" value="{{ old('address') }}" required
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror">
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="mb-8 border-t pt-8">
                <h2 class="text-xl font-semibold mb-4">연락처 정보</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            전화번호 <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror"
                               placeholder="예: 02-1234-5678">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            이메일 <span class="text-gray-500">(선택)</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Amenities -->
            <div class="mb-8 border-t pt-8">
                <h2 class="text-xl font-semibold mb-4">편의시설</h2>
                <p class="text-sm text-gray-600 mb-4">숙소에서 제공하는 편의시설을 선택하세요</p>

                @foreach($amenities as $category => $items)
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase">{{ $category }}</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach($items as $amenity)
                                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                    <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}"
                                           {{ in_array($amenity->id, old('amenities', [])) ? 'checked' : '' }}
                                           class="rounded text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">{{ $amenity->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-4 border-t pt-6">
                <a href="{{ route('manager.accommodations.index') }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    취소
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                    등록하기
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
