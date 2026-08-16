<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    // Only created_at, no updated_at
    public $timestamps = false;

    protected $fillable = [
        'subscription_id',
        'amount',
        'currency',
        'method',
        'status',
        'stripe_payment_intent_id',
        'stripe_invoice_id',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount'  => 'decimal:2',
            'paid_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
