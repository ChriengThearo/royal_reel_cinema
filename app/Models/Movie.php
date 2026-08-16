<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'release_date',
        'duration_minutes',
        'poster_url',
        'backdrop_url',
        'trailer_url',
        'age_rating',
        'status',
        'access_type',
        'required_plan_id',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'release_date'     => 'date',
            'duration_minutes' => 'integer',
        ];
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'movie_genres', 'movie_id', 'genre_id');
    }

    public function videoAssets(): HasMany
    {
        return $this->hasMany(VideoAsset::class);
    }

    public function watchHistories(): HasMany
    {
        return $this->hasMany(WatchHistory::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * The specific plan required to watch this movie (null = any active plan).
     */
    public function requiredPlan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'required_plan_id');
    }

    /**
     * The user who created this movie entry.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Access control ────────────────────────────────────────────────────────

    /**
     * Determine whether a given user (or guest) can watch this movie.
     *
     * Rules:
     *   1. access_type = 'free'          → always allowed
     *   2. access_type = 'subscription'  → user must have an active subscription
     *      where end_date > now() AND
     *      (required_plan_id IS NULL  OR  subscription.plan_id = required_plan_id)
     */
    public function canBeWatchedBy(?User $user): bool
    {
        if ($this->access_type === 'free') {
            return true;
        }

        if ($user === null) {
            return false;
        }

        return $user->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->when(
                $this->required_plan_id !== null,
                fn (Builder $q) => $q->where('plan_id', $this->required_plan_id)
            )
            ->exists();
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Genre names as a human-readable string, e.g. "Action · Drama".
     * Assumes genres are already loaded.
     */
    public function genreLabel(): string
    {
        return $this->genres->pluck('name')->join(' · ');
    }

    /**
     * Release year from release_date, or null.
     */
    public function releaseYear(): ?int
    {
        return $this->release_date?->year;
    }
}
