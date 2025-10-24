@extends('layouts.app')

@section('title', '내 숙소 관리 - OTA Service')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">내 숙소 관리</h1>
            <p class="mt-2 text-gray-600">등록한 숙소를 관리하고 새로운 숙소를 추가하세요</p>
        </div>
        <a href="{{ route('manager.accommodations.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold">
            + 새 숙소 등록
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    @if($accommodations->count() > 0)
        <div class="grid grid-cols-1 gap-6">
            @foreach($accommodations as $accommodation)
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
                    <div class="flex">
                        <!-- Image -->
                        <div class="w-64 flex-shrink-0">
                            @if($accommodation->main_image)
                                <img src="{{ asset('storage/' . $accommodation->main_image) }}"
                                     alt="{{ $accommodation->name }}"
                                     class="w-full h-48 object-cover rounded-l-lg">
                            @else
                                <div class="w-full h-48 bg-gray-200 rounded-l-lg flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1 p-6">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-xl font-bold text-gray-900">{{ $accommodation->name }}</h3>
                                        <span class="px-3 py-1 text-xs rounded-full {{ $accommodation->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $accommodation->is_active ? '활성' : '비활성' }}
                                        </span>
                                        <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                            {{ ucfirst($accommodation->category) }}
                                        </span>
                                    </div>
                                    <p class="text-gray-600 mb-3">
                                        <svg class="inline w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $accommodation->city }}
                                    </p>
                                    <p class="text-gray-700 mb-4">{{ Str::limit($accommodation->description, 150) }}</p>
                                    <div class="flex gap-6 text-sm text-gray-600">
                                        <span>
                                            <strong>객실:</strong> {{ $accommodation->rooms_count }}개
                                        </span>
                                        <span>
                                            <strong>예약:</strong> {{ $accommodation->bookings_count }}건
                                        </span>
                                        @if($accommodation->rating)
                                            <span>
                                                <strong>평점:</strong> {{ number_format($accommodation->rating, 1) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex flex-col gap-2 ml-4">
                                    <a href="{{ route('manager.accommodations.show', $accommodation->id) }}"
                                       class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-center text-sm">
                                        상세보기
                                    </a>
                                    <a href="{{ route('manager.accommodations.edit', $accommodation->id) }}"
                                       class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 text-center text-sm">
                                        수정
                                    </a>
                                    <form action="{{ route('manager.accommodations.destroy', $accommodation->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('정말로 삭제하시겠습니까? 활성화된 예약이 있는 경우 삭제할 수 없습니다.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-full px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                                            삭제
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $accommodations->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">아직 등록된 숙소가 없습니다</h3>
            <p class="text-gray-600 mb-6">첫 숙소를 등록하고 고객들에게 제공하세요!</p>
            <a href="{{ route('manager.accommodations.create') }}"
               class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                첫 숙소 등록하기
            </a>
        </div>
    @endif
</div>
@endsection
