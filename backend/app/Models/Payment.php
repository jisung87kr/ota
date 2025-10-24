<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'merchant_uid',
        'imp_uid',
        'pg_tid',
        'payment_method',
        'amount',
        'currency',
        'status',
        'card_name',
        'card_number',
        'refund_amount',
        'refunded_at',
        'refund_reason',
        'pg_response',
        'fail_reason',
        'paid_at',
        'failed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'failed_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Generate unique merchant UID
     */
    public static function generateMerchantUid(): string
    {
        return 'PAY_' . date('Ymd') . '_' . strtoupper(Str::random(10));
    }

    /**
     * Mark payment as paid
     */
    public function markAsPaid(array $pgData = []): bool
    {
        $this->status = 'paid';
        $this->paid_at = now();

        if (!empty($pgData)) {
            $this->imp_uid = $pgData['imp_uid'] ?? $this->imp_uid;
            $this->pg_tid = $pgData['pg_tid'] ?? $this->pg_tid;
            $this->card_name = $pgData['card_name'] ?? $this->card_name;
            $this->card_number = $pgData['card_number'] ?? $this->card_number;
            $this->pg_response = json_encode($pgData);
        }

        return $this->save();
    }

    /**
     * Mark payment as failed
     */
    public function markAsFailed(string $reason): bool
    {
        $this->status = 'failed';
        $this->failed_at = now();
        $this->fail_reason = $reason;

        return $this->save();
    }

    /**
     * Process refund
     */
    public function processRefund(float $amount, string $reason = null): bool
    {
        if ($this->status !== 'paid') {
            return false;
        }

        $totalRefunded = $this->refund_amount + $amount;

        if ($totalRefunded > $this->amount) {
            return false; // Cannot refund more than paid amount
        }

        $this->refund_amount = $totalRefunded;
        $this->refund_reason = $reason;
        $this->refunded_at = now();

        // Update status
        if ($totalRefunded >= $this->amount) {
            $this->status = 'refunded';
        } else {
            $this->status = 'partially_refunded';
        }

        return $this->save();
    }

    /**
     * Check if payment is successful
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if payment is refunded
     */
    public function isRefunded(): bool
    {
        return in_array($this->status, ['refunded', 'partially_refunded']);
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment failed
     */
    public function hasFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Get remaining refundable amount
     */
    public function getRefundableAmount(): float
    {
        return $this->amount - $this->refund_amount;
    }

    /**
     * Get status label in Korean
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => '결제 대기',
            'paid' => '결제 완료',
            'failed' => '결제 실패',
            'cancelled' => '결제 취소',
            'refunded' => '환불 완료',
            'partially_refunded' => '부분 환불',
            default => '알 수 없음',
        };
    }

    /**
     * Get payment method label in Korean
     */
    public function getPaymentMethodLabel(): string
    {
        return match($this->payment_method) {
            'card' => '신용/체크카드',
            'bank_transfer' => '무통장입금',
            'vbank' => '가상계좌',
            'phone' => '휴대폰 소액결제',
            'kakao' => '카카오페이',
            'naver' => '네이버페이',
            default => $this->payment_method,
        };
    }

    /**
     * Scope: Get paid payments
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope: Get pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get failed payments
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
