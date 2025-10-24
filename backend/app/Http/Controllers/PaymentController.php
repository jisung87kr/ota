<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Prepare payment for a booking
     */
    public function prepare(Request $request, $bookingId)
    {
        $booking = Booking::with(['accommodation', 'room', 'user'])
            ->findOrFail($bookingId);

        // Check if user owns this booking
        if ($booking->user_id !== auth()->id()) {
            abort(403, '권한이 없습니다.');
        }

        // Check if already paid
        if ($booking->isPaid()) {
            return redirect()->route('bookings.confirmation', $booking->id)
                ->with('info', '이미 결제가 완료된 예약입니다.');
        }

        // Create or get existing payment
        $payment = $booking->payment ?? Payment::create([
            'booking_id' => $booking->id,
            'merchant_uid' => Payment::generateMerchantUid(),
            'payment_method' => 'card', // Default
            'amount' => $booking->total_price,
            'currency' => 'KRW',
            'status' => 'pending',
        ]);

        return view('payments.prepare', compact('booking', 'payment'));
    }

    /**
     * Process payment callback from client
     */
    public function callback(Request $request)
    {
        $impUid = $request->input('imp_uid');
        $merchantUid = $request->input('merchant_uid');

        try {
            // Verify payment with PortOne API
            $verified = $this->verifyPayment($impUid);

            if (!$verified) {
                return redirect()->route('home')
                    ->with('error', '결제 검증에 실패했습니다.');
            }

            $payment = Payment::where('merchant_uid', $merchantUid)->firstOrFail();
            $booking = $payment->booking;

            DB::transaction(function () use ($payment, $booking, $verified) {
                // Mark payment as paid
                $payment->markAsPaid($verified);

                // Confirm booking
                $booking->confirm();
            });

            // TODO: Send confirmation email

            return redirect()->route('bookings.confirmation', $booking->id)
                ->with('success', '결제가 성공적으로 완료되었습니다!');

        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage());

            return redirect()->route('home')
                ->with('error', '결제 처리 중 오류가 발생했습니다.');
        }
    }

    /**
     * Webhook handler for PortOne
     */
    public function webhook(Request $request)
    {
        try {
            $impUid = $request->input('imp_uid');
            $merchantUid = $request->input('merchant_uid');
            $status = $request->input('status');

            Log::info('Payment webhook received', [
                'imp_uid' => $impUid,
                'merchant_uid' => $merchantUid,
                'status' => $status,
            ]);

            // Verify payment
            $verified = $this->verifyPayment($impUid);

            if (!$verified) {
                return response()->json(['error' => 'Payment verification failed'], 400);
            }

            $payment = Payment::where('merchant_uid', $merchantUid)->first();

            if (!$payment) {
                Log::error('Payment not found', ['merchant_uid' => $merchantUid]);
                return response()->json(['error' => 'Payment not found'], 404);
            }

            DB::transaction(function () use ($payment, $verified, $status) {
                if ($status === 'paid') {
                    $payment->markAsPaid($verified);
                    $payment->booking->confirm();
                } elseif ($status === 'failed') {
                    $payment->markAsFailed($verified['fail_reason'] ?? 'Unknown error');
                }
            });

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Verify payment with PortOne API
     */
    private function verifyPayment(string $impUid)
    {
        try {
            // Get PortOne access token
            $tokenResponse = Http::post('https://api.iamport.kr/users/getToken', [
                'imp_key' => config('services.portone.api_key'),
                'imp_secret' => config('services.portone.api_secret'),
            ])->json();

            if (!isset($tokenResponse['response']['access_token'])) {
                Log::error('Failed to get PortOne access token');
                return false;
            }

            $accessToken = $tokenResponse['response']['access_token'];

            // Get payment details
            $paymentResponse = Http::withToken($accessToken)
                ->get("https://api.iamport.kr/payments/{$impUid}")
                ->json();

            if ($paymentResponse['code'] !== 0) {
                Log::error('PortOne API error', ['response' => $paymentResponse]);
                return false;
            }

            $paymentData = $paymentResponse['response'];

            // Verify payment status
            if ($paymentData['status'] !== 'paid') {
                return false;
            }

            return [
                'imp_uid' => $paymentData['imp_uid'],
                'merchant_uid' => $paymentData['merchant_uid'],
                'pg_tid' => $paymentData['pg_tid'],
                'amount' => $paymentData['amount'],
                'card_name' => $paymentData['card_name'] ?? null,
                'card_number' => $paymentData['card_number'] ?? null,
                'paid_at' => $paymentData['paid_at'],
                'status' => $paymentData['status'],
            ];

        } catch (\Exception $e) {
            Log::error('Payment verification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Request refund via PortOne API
     */
    public function refund(Request $request, $bookingId)
    {
        $booking = Booking::with('payment')->findOrFail($bookingId);

        // Check authorization
        if ($booking->user_id !== auth()->id()) {
            abort(403, '권한이 없습니다.');
        }

        if (!$booking->payment || !$booking->payment->isPaid()) {
            return back()->with('error', '환불할 결제 내역이 없습니다.');
        }

        $refundAmount = $booking->calculateRefundAmount();

        if ($refundAmount <= 0) {
            return back()->with('error', '환불 가능한 금액이 없습니다.');
        }

        // Get cancellation reason from session or request
        $reason = session('cancellation_reason') ?? $request->input('reason', '고객 요청');

        try {
            DB::transaction(function () use ($booking, $refundAmount, $reason) {
                $payment = $booking->payment;

                // Request refund from PortOne
                $refundResult = $this->requestRefundFromPG(
                    $payment->imp_uid,
                    $refundAmount,
                    $reason
                );

                if (!$refundResult) {
                    throw new \Exception('환불 요청이 실패했습니다.');
                }

                // Update payment
                $payment->processRefund($refundAmount, $reason);

                // Cancel booking
                $booking->cancel($reason);
            });

            // TODO: Send refund confirmation email

            return redirect()->route('bookings.show', $booking->id)
                ->with('success', '환불이 성공적으로 처리되었습니다. 환불 금액: ₩' . number_format($refundAmount));

        } catch (\Exception $e) {
            Log::error('Refund error: ' . $e->getMessage());

            return back()->with('error', '환불 처리 중 오류가 발생했습니다.');
        }
    }

    /**
     * Request refund from PortOne
     */
    private function requestRefundFromPG(string $impUid, float $amount, string $reason)
    {
        try {
            // Get access token
            $tokenResponse = Http::post('https://api.iamport.kr/users/getToken', [
                'imp_key' => config('services.portone.api_key'),
                'imp_secret' => config('services.portone.api_secret'),
            ])->json();

            if (!isset($tokenResponse['response']['access_token'])) {
                return false;
            }

            $accessToken = $tokenResponse['response']['access_token'];

            // Request refund
            $refundResponse = Http::withToken($accessToken)
                ->post('https://api.iamport.kr/payments/cancel', [
                    'imp_uid' => $impUid,
                    'amount' => $amount,
                    'reason' => $reason,
                ])
                ->json();

            return $refundResponse['code'] === 0;

        } catch (\Exception $e) {
            Log::error('PG refund request error: ' . $e->getMessage());
            return false;
        }
    }
}
