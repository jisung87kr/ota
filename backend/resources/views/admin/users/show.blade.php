@extends('layouts.admin')

@section('title', '사용자 상세')
@section('header', '사용자 상세')

@section('content')
    <div class="max-w-4xl">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">사용자 정보</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        수정
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        목록
                    </a>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="space-y-6">
                    <!-- Basic Info -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-3">기본 정보</h4>
                        <dl class="grid grid-cols-1 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">이름</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">이메일</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">전화번호</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->phone ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">역할</dt>
                                <dd class="mt-1">
                                    <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">
                                        {{ $user->role->label() }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">상태</dt>
                                <dd class="mt-1">
                                    <span class="px-2 py-1 text-xs rounded bg-{{ $user->status->color() }}-100 text-{{ $user->status->color() }}-800">
                                        {{ $user->status->label() }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Business Info (for accommodation managers) -->
                    @if($user->isAccommodationManager() && $user->business_info)
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-3">사업자 정보</h4>
                            <div class="p-4 bg-gray-50 rounded">
                                <pre class="text-sm text-gray-900 whitespace-pre-wrap">{{ $user->business_info }}</pre>
                            </div>
                        </div>
                    @endif

                    <!-- Approval Info -->
                    @if($user->approved_at)
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-3">승인 정보</h4>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">승인일</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->approved_at->format('Y-m-d H:i:s') }}</dd>
                                </div>
                                @if($user->approver)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">승인자</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $user->approver->name }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    @endif

                    <!-- Timestamps -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-3">기타 정보</h4>
                        <dl class="grid grid-cols-1 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">가입일</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('Y-m-d H:i:s') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">마지막 수정일</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('Y-m-d H:i:s') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Actions -->
                    <div class="pt-6 border-t border-gray-200">
                        <div class="flex space-x-4">
                            @if($user->isPending())
                                <form action="{{ route('admin.users.approve', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                        승인
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.reject', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                                            onclick="return confirm('거부하시겠습니까?')">
                                        거부
                                    </button>
                                </form>
                            @endif

                            @if($user->isActive() && $user->id !== auth()->id())
                                <form action="{{ route('admin.users.suspend', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700"
                                            onclick="return confirm('정지하시겠습니까?')">
                                        정지
                                    </button>
                                </form>
                            @endif

                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                                            onclick="return confirm('삭제하시겠습니까?')">
                                        삭제
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
