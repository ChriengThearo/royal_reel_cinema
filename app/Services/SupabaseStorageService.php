<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\User;
use App\Models\VideoAsset;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class SupabaseStorageService
{
    // ── Upload helpers ────────────────────────────────────────────────────────

    /**
     * Upload a poster image for a movie.
     * Key convention: {movieId}.jpg  in the 'posters' bucket.
     * Returns the public URL.
     */
    public function uploadPoster(int $movieId, UploadedFile $file): string
    {
        $path = "{$movieId}.jpg";

        Storage::disk('posters')->put($path, $file->getContent(), 'public');

        return Storage::disk('posters')->url($path);
    }

    /**
     * Upload a backdrop image for a movie.
     * Key convention: {movieId}.jpg  in the 'posters' bucket under a 'backdrops/' prefix.
     * Returns the public URL.
     *
     * Note: backdrops live in the 'posters' bucket (public), not a separate one.
     * Key: backdrops/{movieId}.jpg
     */
    public function uploadBackdrop(int $movieId, UploadedFile $file): string
    {
        $path = "backdrops/{$movieId}.jpg";

        Storage::disk('posters')->put($path, $file->getContent(), 'public');

        return Storage::disk('posters')->url($path);
    }

    /**
     * Upload a user avatar.
     * Key convention: {userId}.jpg  in the 'avatars' bucket.
     * Returns the public URL.
     */
    public function uploadAvatar(int $userId, UploadedFile $file): string
    {
        $path = "{$userId}.jpg";

        Storage::disk('avatars')->put($path, $file->getContent(), 'public');

        return Storage::disk('avatars')->url($path);
    }

    /**
     * Upload a video file for a movie at a specific quality level.
     * Key convention: {movieId}/{quality}.mp4  in the 'videos' bucket (private).
     * Creates or updates the VideoAsset row and returns the model.
     */
    public function uploadVideo(int $movieId, string $quality, UploadedFile $file): VideoAsset
    {
        $path   = "{$movieId}/{$quality}.mp4";
        $sizeMb = (int) ceil($file->getSize() / 1_048_576);

        Storage::disk('videos')->put($path, $file->getContent(), 'private');

        return VideoAsset::updateOrCreate(
            ['movie_id' => $movieId, 'quality' => $quality],
            [
                'storage_key' => $path,
                'format'      => 'mp4',
                'size_mb'     => $sizeMb,
            ]
        );
    }

    // ── Signed URL ────────────────────────────────────────────────────────────

    /**
     * Generate a temporary signed URL for streaming a video.
     *
     * Access check is performed here — do not rely on callers having already verified.
     *
     * @throws AccessDeniedHttpException  if the user may not watch the movie
     * @throws \RuntimeException          if no matching VideoAsset is found
     */
    public function getSignedVideoUrl(
        Movie $movie,
        ?User $user,
        string $quality = '1080p',
        int $expiresInSeconds = 7200
    ): string {
        // Always enforce access control inside this method
        if (!$movie->canBeWatchedBy($user)) {
            throw new AccessDeniedHttpException(
                'You do not have an active subscription to watch this movie.'
            );
        }

        // Fall back to the best available quality if the exact one doesn't exist
        $asset = $movie->videoAssets()->where('quality', $quality)->first()
            ?? $movie->videoAssets()->first();

        if ($asset === null) {
            throw new \RuntimeException(
                "No video asset found for movie ID {$movie->id}."
            );
        }

        return Storage::disk('videos')->temporaryUrl(
            $asset->storage_key,
            now()->addSeconds($expiresInSeconds)
        );
    }
}
