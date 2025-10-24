@extends('layouts.app')

@section('title', '결제하기 - OTA Service')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">결제하기</h1>
        <p class="mt-2 text-gray-600">예약 정보를 확인하고 결제를 진행하세요</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Payment Information -->
        <div class="lg:col-span-2">
            <!-- Booking Summary -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">예약 정보</h2>

                <div class="space-y-4">
                    <div class="flex items-start">
                        @if($booking->accommodation->main_image)
                            <img src="{{ asset('storage/' . $booking->accommodation->main_image) }}"
                                 alt="{{ $booking->accommodation->name }}"
                                 class="w-32 h-24 object-cover rounded-lg mr-4">
                        @endif
                        <div>
                            <h3 class="text-lg font-semibold">{{ $booking->accommodation->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $booking->room->name }}</p>
                            <p class="text-sm text-gray-500 mt-1">
                                <svg class="inline w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $booking->accommodation->city }}
                            </p>
                        </div>
                    </div>

                    <div class="border-t pt-4">
                        <dl class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <dt class="text-gray-600">체크인</dt>
                                <dd class="font-semibold">{{ $booking->check_in_date->format('Y-m-d (D)') }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-600">체크아웃</dt>
                                <dd class="font-semibold">{{ $booking->check_out_date->format('Y-m-d (D)') }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-600">숙박 일수</dt>
                                <dd class="font-semibold">{{ $booking->nights }}박</dd>
                            </div>
                            <div>
                                <dt class="text-gray-600">투숙 인원</dt>
                                <dd class="font-semibold">{{ $booking->guests }}명</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="border-t pt-4">
                        <h4 class="font-semibold mb-2">투숙객 정보</h4>
                        <dl class="space-y-2 text-sm">
                            <div>
                                <dt class="text-gray-600 inline">이름:</dt>
                                <dd class="inline ml-2">{{ $booking->guest_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-600 inline">이메일:</dt>
                                <dd class="inline ml-2">{{ $booking->guest_email }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-600 inline">연락처:</dt>
                                <dd class="inline ml-2">{{ $booking->guest_phone }}</dd>
                            </div>
                        </dl>
                    </div>

                    @if($booking->special_requests)
                        <div class="border-t pt-4">
                            <h4 class="font-semibold mb-2">특별 요청사항</h4>
                            <p class="text-sm text-gray-700">{{ $booking->special_requests }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Method -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">결제 수단</h2>
                <p class="text-sm text-gray-600 mb-4">
                    안전한 결제를 위해 PortOne 결제 시스템을 이용합니다.
                </p>
                <div class="flex items-center p-4 border rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold">신용/체크카드</p>
                        <p class="text-xs text-gray-500">모든 카드사 지원</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Summary Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                <h2 class="text-xl font-semibold mb-4">결제 금액</h2>

                <div class="space-y-3 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">객실 요금 ({{ $booking->nights }}박)</span>
                        <span>₩{{ number_format($booking->room_price * $booking->nights) }}</span>
                    </div>
                    <!-- Add more fee breakdowns here if needed -->
                </div>

                <div class="border-t pt-4 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold">총 결제 금액</span>
                        <span class="text-2xl font-bold text-blue-600">₩{{ number_format($booking->total_price) }}</span>
                    </div>
                </div>

                <!-- Payment Button -->
                <button id="payment-button"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold text-lg">
                    결제하기
                </button>

                <div class="mt-4 text-xs text-gray-500 space-y-1">
                    <p>• 결제 후 예약이 확정됩니다.</p>
                    <p>• 취소 정책에 따라 환불이 가능합니다.</p>
                    <p>• 체크인 7일 전: 100% 환불</p>
                    <p>• 체크인 3-6일 전: 50% 환불</p>
                    <p>• 체크인 3일 이내: 환불 불가</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PortOne SDK -->
<script src="https://cdn.iamport.kr/v1/iamport.js"></script>
<script>
    const IMP = window.IMP;
    IMP.init('{{ config("services.portone.imp_code") }}');

    document.getElementById('payment-button').addEventListener('click', function() {
        IMP.request_pay({
            pg: 'html5_inicis', // PG사 (테스트: html5_inicis)
            pay_method: 'card',
            merchant_uid: '{{ $payment->merchant_uid }}',
            name: '{{ $booking->accommodation->name }} - {{ $booking->room->name }}',
            amount: {{ $booking->total_price }},
            buyer_email: '{{ $booking->guest_email }}',
            buyer_name: '{{ $booking->guest_name }}',
            buyer_tel: '{{ $booking->guest_phone }}',
        }, function(rsp) {
            if (rsp.success) {
                // Payment successful - send to server for verification
                window.location.href = '{{ route("payment.callback") }}?imp_uid=' + rsp.imp_uid + '&merchant_uid=' + rsp.merchant_uid;
            } else {
                // Payment failed
                alert('결제에 실패했습니다: ' + rsp.error_msg);
            }
        });
    });
</script>
@endsection
