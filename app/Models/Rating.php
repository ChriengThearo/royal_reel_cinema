<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    // Only created_at, no updated_at
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'movie_id',
        'score',
    ];

    protected function casts(): array
    {
        return [
            'score'      => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}
