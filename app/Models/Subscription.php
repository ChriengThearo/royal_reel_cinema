<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'start_date',
        'end_date',
        'auto_renew',
        'stripe_subscription_id',
        'stripe_status',
        'cancel_at_period_end',
    ];

    protected function casts(): array
    {
        return [
            'start_date'           => 'datetime',
            'end_date'             => 'datetime',
            'auto_renew'           => 'boolean',
            'cancel_at_period_end' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Whether this subscription is currently active and not yet expired.
     */
    public function isActive(): bool
    {
        return $this->status === 'active'
            && $this->end_date !== null
            && $this->end_date->isFuture();
    }
}
