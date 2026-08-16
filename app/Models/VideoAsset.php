<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoAsset extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'movie_id',
        'quality',
        'storage_key',
        'format',
        'size_mb',
    ];

    protected function casts(): array
    {
        return [
            'size_mb' => 'integer',
        ];
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}
