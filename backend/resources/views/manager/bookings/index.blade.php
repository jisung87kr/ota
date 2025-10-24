@extends('layouts.app')

@section('title', '예약 관리 - OTA Service')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">예약 관리</h1>
        <p class="mt-2 text-gray-600">숙소의 예약 내역을 확인하고 관리하세요</p>
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

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('manager.bookings.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Accommodation Filter -->
            <div>
                <label for="accommodation_id" class="block text-sm font-medium text-gray-700 mb-2">숙소</label>
                <select name="accommodation_id" id="accommodation_id"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">전체 숙소</option>
                    @foreach($accommodations as $acc)
                        <option value="{{ $acc->id }}" {{ request('accommodation_id') == $acc->id ? 'selected' : '' }}>
                            {{ $acc->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">예약 상태</label>
                <select name="status" id="status"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">전체 상태</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>대기중</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>확정</option>
                    <option value="checked_in" {{ request('status') === 'checked_in' ? 'selected' : '' }}>체크인</option>
                    <option value="checked_out" {{ request('status') === 'checked_out' ? 'selected' : '' }}>체크아웃</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>취소</option>
                </select>
            </div>

            <!-- Date Filter -->
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">체크인 날짜</label>
                <select name="date" id="date"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">전체 날짜</option>
                    <option value="today" {{ request('date') === 'today' ? 'selected' : '' }}>오늘</option>
                    <option value="week" {{ request('date') === 'week' ? 'selected' : '' }}>이번 주</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="flex items-end">
                <button type="submit"
                        class="w-full px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                    필터 적용
                </button>
            </div>
        </form>
    </div>

    <!-- Bookings List -->
    @if($bookings->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            예약번호
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            숙소/객실
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            고객
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            체크인/아웃
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            금액
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            상태
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            작업
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $booking->booking_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="font-semibold">{{ $booking->accommodation->name }}</div>
                                <div class="text-gray-500">{{ $booking->room->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>{{ $booking->user->name }}</div>
                                <div class="text-gray-500">{{ $booking->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>{{ $booking->check_in_date->format('Y-m-d') }}</div>
                                <div class="text-gray-500">{{ $booking->check_out_date->format('Y-m-d') }}</div>
                                <div class="text-xs text-gray-400">{{ $booking->nights }}박</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                ₩{{ number_format($booking->total_price) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($booking->status->value === 'confirmed') bg-green-100 text-green-800
                                    @elseif($booking->status->value === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status->value === 'checked_in') bg-blue-100 text-blue-800
                                    @elseif($booking->status->value === 'checked_out') bg-gray-100 text-gray-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $booking->status->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('manager.bookings.show', $booking->id) }}"
                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                    상세보기
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $bookings->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">예약 내역이 없습니다</h3>
            <p class="text-gray-600">필터 조건을 변경하거나 새로운 예약을 기다려주세요</p>
        </div>
    @endif
</div>
@endsection
