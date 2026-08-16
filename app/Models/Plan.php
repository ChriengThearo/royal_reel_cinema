<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'price',
        'currency',
        'billing_cycle',
        'max_quality',
        'screens_allowed',
        'is_active',
        'stripe_product_id',
        'stripe_price_id',
    ];

    protected function casts(): array
    {
        return [
            'price'          => 'decimal:2',
            'screens_allowed' => 'integer',
            'is_active'      => 'boolean',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function movies(): HasMany
    {
        return $this->hasMany(Movie::class, 'required_plan_id');
    }
}
