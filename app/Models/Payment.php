<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'method',
        'amount_paid',
        'change_amount',
        'status',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    /**
     * Get the order for this payment.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get formatted amount paid.
     */
    public function getFormattedAmountPaidAttribute(): string
    {
        return 'Rp ' . number_format($this->amount_paid, 0, ',', '.');
    }

    /**
     * Get formatted change amount.
     */
    public function getFormattedChangeAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->change_amount, 0, ',', '.');
    }

    /**
     * Check if payment is completed.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if payment method is cash.
     */
    public function isCash(): bool
    {
        return $this->method === 'cash';
    }

    /**
     * Check if payment method is QRIS.
     */
    public function isQris(): bool
    {
        return $this->method === 'qris';
    }

    /**
     * Scope paid payments.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}
