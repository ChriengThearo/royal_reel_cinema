<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WatchHistory extends Model
{
    // Only last_watched_at, no updated_at
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'movie_id',
        'progress_seconds',
        'completed',
        'last_watched_at',
    ];

    protected function casts(): array
    {
        return [
            'progress_seconds' => 'integer',
            'completed'        => 'boolean',
            'last_watched_at'  => 'datetime',
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

    /**
     * Progress as a percentage (0–100), or null if duration is unknown.
     */
    public function progressPercent(): ?int
    {
        $duration = $this->movie?->duration_minutes;
        if (!$duration || $duration <= 0) {
            return null;
        }

        return (int) min(100, round(($this->progress_seconds / ($duration * 60)) * 100));
    }
}
