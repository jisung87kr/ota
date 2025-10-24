@extends('layouts.app')

@section('title', $accommodation->name . ' - OTA Service')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-start">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-3xl font-bold text-gray-900">{{ $accommodation->name }}</h1>
                <span class="px-3 py-1 rounded-full text-sm {{ $accommodation->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $accommodation->is_active ? '활성' : '비활성' }}
                </span>
            </div>
            <p class="text-gray-600">
                <svg class="inline w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ $accommodation->city }} - {{ $accommodation->address }}
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('manager.accommodations.edit', $accommodation->id) }}"
               class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                수정
            </a>
            <a href="{{ route('manager.accommodations.index') }}"
               class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                목록으로
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Main Image -->
    @if($accommodation->main_image)
        <div class="mb-8">
            <img src="{{ asset('storage/' . $accommodation->main_image) }}"
                 alt="{{ $accommodation->name }}"
                 class="w-full h-96 object-cover rounded-lg shadow">
        </div>
    @endif

    <!-- Information Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Main Info -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">숙소 정보</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">카테고리</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($accommodation->category) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">전화번호</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $accommodation->phone }}</dd>
                    </div>
                    @if($accommodation->email)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">이메일</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $accommodation->email }}</dd>
                        </div>
                    @endif
                    @if($accommodation->rating)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">평점</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ number_format($accommodation->rating, 1) }} / 5.0</dd>
                        </div>
                    @endif
                </dl>
                <div class="mt-6">
                    <dt class="text-sm font-medium text-gray-500 mb-2">설명</dt>
                    <dd class="text-sm text-gray-900 whitespace-pre-line">{{ $accommodation->description }}</dd>
                </div>
            </div>

            <!-- Amenities -->
            @if($accommodation->amenities->count() > 0)
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">편의시설</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($accommodation->amenities as $amenity)
                            <div class="flex items-center text-sm text-gray-700">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $amenity->name }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Rooms -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">객실 목록</h2>
                    <a href="{{ route('manager.accommodations.rooms.create', $accommodation->id) }}"
                       class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                        + 객실 추가
                    </a>
                </div>

                @if($accommodation->rooms->count() > 0)
                    <div class="space-y-4">
                        @foreach($accommodation->rooms as $room)
                            <div class="border rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <h3 class="text-lg font-semibold">{{ $room->name }}</h3>
                                            <span class="px-2 py-1 text-xs rounded {{ $room->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $room->is_active ? '활성' : '비활성' }}
                                            </span>
                                        </div>
                                        @if($room->description)
                                            <p class="text-sm text-gray-600 mb-2">{{ $room->description }}</p>
                                        @endif
                                        <div class="flex flex-wrap gap-4 text-sm text-gray-700">
                                            <span>최대 인원: {{ $room->max_occupancy }}명</span>
                                            @if($room->size)
                                                <span>크기: {{ $room->size }}㎡</span>
                                            @endif
                                            @if($room->bed_type)
                                                <span>침대: {{ ucfirst($room->bed_type) }} × {{ $room->bed_count }}</span>
                                            @endif
                                            <span>총 객실 수: {{ $room->total_rooms }}개</span>
                                        </div>
                                        <p class="text-lg font-bold text-blue-600 mt-2">
                                            ₩{{ number_format($room->base_price) }} / 박
                                        </p>
                                    </div>
                                    <div class="flex flex-col gap-2 ml-4">
                                        <a href="{{ route('manager.accommodations.rooms.edit', [$accommodation->id, $room->id]) }}"
                                           class="px-4 py-1 bg-gray-600 text-white rounded hover:bg-gray-700 text-sm text-center">
                                            수정
                                        </a>
                                        <form action="{{ route('manager.accommodations.rooms.destroy', [$accommodation->id, $room->id]) }}"
                                              method="POST"
                                              onsubmit="return confirm('정말로 삭제하시겠습니까?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-full px-4 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                                                삭제
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p class="mb-4">등록된 객실이 없습니다.</p>
                        <a href="{{ route('manager.accommodations.rooms.create', $accommodation->id) }}"
                           class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            첫 객실 등록하기
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Recent Bookings -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">최근 예약</h2>
                    <a href="{{ route('manager.bookings.index', ['accommodation_id' => $accommodation->id]) }}"
                       class="text-blue-600 hover:text-blue-800 text-sm">
                        전체 →
                    </a>
                </div>

                @if($accommodation->bookings->count() > 0)
                    <div class="space-y-3">
                        @foreach($accommodation->bookings as $booking)
                            <div class="border-l-4 {{ $booking->status->value === 'confirmed' ? 'border-green-500' : ($booking->status->value === 'pending' ? 'border-yellow-500' : 'border-gray-300') }} pl-3 py-2">
                                <p class="text-sm font-semibold">{{ $booking->room->name }}</p>
                                <p class="text-xs text-gray-600">{{ $booking->user->name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $booking->check_in_date->format('m/d') }} - {{ $booking->check_out_date->format('m/d') }}
                                </p>
                                <p class="text-xs font-semibold text-blue-600 mt-1">
                                    ₩{{ number_format($booking->total_price) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 text-center py-4">예약 내역이 없습니다.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
