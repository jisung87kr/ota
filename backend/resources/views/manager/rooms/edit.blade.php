@extends('layouts.app')

@section('title', '객실 정보 수정 - OTA Service')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">객실 정보 수정</h1>
        <p class="mt-2 text-gray-600">{{ $accommodation->name }} - {{ $room->name }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('manager.accommodations.rooms.update', [$accommodation->id, $room->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">기본 정보</h2>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            객실명 <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $room->name) }}" required
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                               placeholder="예: 디럭스 트윈룸">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            객실 설명 <span class="text-gray-500">(선택)</span>
                        </label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                                  placeholder="객실에 대한 자세한 설명을 입력하세요">{{ old('description', $room->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Image -->
                    @if($room->main_image)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">현재 객실 이미지</label>
                            <img src="{{ asset('storage/' . $room->main_image) }}"
                                 alt="{{ $room->name }}"
                                 class="w-64 h-48 object-cover rounded-lg">
                        </div>
                    @endif

                    <!-- Main Image -->
                    <div>
                        <label for="main_image" class="block text-sm font-medium text-gray-700 mb-2">
                            객실 이미지 변경 <span class="text-gray-500">(최대 2MB, 변경 시에만 선택)</span>
                        </label>
                        <input type="file" name="main_image" id="main_image" accept="image/*"
                               class="w-full px-4 py-2 border rounded-lg @error('main_image') border-red-500 @enderror">
                        @error('main_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $room->is_active) ? 'checked' : '' }}
                                   class="rounded text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">활성 상태 (체크 해제 시 예약 불가능)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Room Details -->
            <div class="mb-8 border-t pt-8">
                <h2 class="text-xl font-semibold mb-4">객실 세부 정보</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Max Occupancy -->
                    <div>
                        <label for="max_occupancy" class="block text-sm font-medium text-gray-700 mb-2">
                            최대 인원 <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="max_occupancy" id="max_occupancy" value="{{ old('max_occupancy', $room->max_occupancy) }}" required min="1"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('max_occupancy') border-red-500 @enderror">
                        @error('max_occupancy')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Size -->
                    <div>
                        <label for="size" class="block text-sm font-medium text-gray-700 mb-2">
                            객실 크기 (㎡) <span class="text-gray-500">(선택)</span>
                        </label>
                        <input type="number" name="size" id="size" value="{{ old('size', $room->size) }}" min="1"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('size') border-red-500 @enderror">
                        @error('size')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bed Type -->
                    <div>
                        <label for="bed_type" class="block text-sm font-medium text-gray-700 mb-2">
                            침대 타입 <span class="text-gray-500">(선택)</span>
                        </label>
                        <select name="bed_type" id="bed_type"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('bed_type') border-red-500 @enderror">
                            <option value="">선택하세요</option>
                            @foreach($bedTypes as $bedType)
                                <option value="{{ $bedType }}" {{ old('bed_type', $room->bed_type) === $bedType ? 'selected' : '' }}>
                                    {{ ucfirst($bedType) }}
                                </option>
                            @endforeach
                        </select>
                        @error('bed_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bed Count -->
                    <div>
                        <label for="bed_count" class="block text-sm font-medium text-gray-700 mb-2">
                            침대 수 <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="bed_count" id="bed_count" value="{{ old('bed_count', $room->bed_count) }}" required min="1"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('bed_count') border-red-500 @enderror">
                        @error('bed_count')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Pricing and Inventory -->
            <div class="mb-8 border-t pt-8">
                <h2 class="text-xl font-semibold mb-4">가격 및 재고</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Base Price -->
                    <div>
                        <label for="base_price" class="block text-sm font-medium text-gray-700 mb-2">
                            1박 기본 가격 (₩) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="base_price" id="base_price" value="{{ old('base_price', $room->base_price) }}" required min="0" step="1000"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('base_price') border-red-500 @enderror"
                               placeholder="예: 100000">
                        @error('base_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total Rooms -->
                    <div>
                        <label for="total_rooms" class="block text-sm font-medium text-gray-700 mb-2">
                            총 객실 수 <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="total_rooms" id="total_rooms" value="{{ old('total_rooms', $room->total_rooms) }}" required min="1"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('total_rooms') border-red-500 @enderror"
                               placeholder="예: 5">
                        <p class="mt-1 text-xs text-gray-500">이 타입의 객실이 총 몇 개인지 입력하세요</p>
                        @error('total_rooms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Amenities -->
            <div class="mb-8 border-t pt-8">
                <h2 class="text-xl font-semibold mb-4">객실 편의시설</h2>
                <p class="text-sm text-gray-600 mb-4">이 객실에서 제공하는 편의시설을 선택하세요</p>

                @foreach($amenities as $category => $items)
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase">{{ $category }}</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach($items as $amenity)
                                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded">
                                    <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}"
                                           {{ in_array($amenity->id, old('amenities', $room->amenities->pluck('id')->toArray())) ? 'checked' : '' }}
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
                <a href="{{ route('manager.accommodations.show', $accommodation->id) }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    취소
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                    수정 완료
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
