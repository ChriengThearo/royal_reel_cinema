<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\User;
use App\Models\VideoAsset;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class SupabaseStorageService
{
    /**
     * Build the Supabase public CDN URL for a file in a public bucket.
     * Format: {SUPABASE_URL}/storage/v1/object/public/{bucket}/{path}
     */
    private function publicUrl(string $bucket, string $path): string
    {
        // Derive the project base URL from the S3 endpoint.
        // SUPABASE_STORAGE_ENDPOINT = https://{ref}.supabase.co/storage/v1/s3
        // Public CDN base           = https://{ref}.supabase.co/storage/v1/object/public
        $endpoint = rtrim(config('filesystems.disks.' . $bucket . '.endpoint'), '/');
        $base     = preg_replace('#/s3$#', '', $endpoint); // strip trailing /s3
        return "{$base}/object/public/{$bucket}/{$path}";
    }

    // ── Upload helpers ────────────────────────────────────────────────────────

    /**
     * Upload a poster image for a movie.
     * Key convention: {movieId}.jpg  in the 'posters' bucket.
     * Returns the public URL.
     */
    public function uploadPoster(int $movieId, UploadedFile $file): string
    {
        $path = "{$movieId}.jpg";

        Storage::disk('posters')->put($path, $file->getContent(), [
            'visibility'  => 'public',
            'ContentType' => $file->getMimeType() ?? 'image/jpeg',
        ]);

        return $this->publicUrl('posters', $path);
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

        Storage::disk('posters')->put($path, $file->getContent(), [
            'visibility'  => 'public',
            'ContentType' => $file->getMimeType() ?? 'image/jpeg',
        ]);

        return $this->publicUrl('posters', $path);
    }

    /**
     * Upload a user avatar.
     * Key convention: {userId}.jpg  in the 'avatars' bucket.
     * Returns the public URL.
     */
    public function uploadAvatar(int $userId, UploadedFile $file): string
    {
        $path = "{$userId}.jpg";

        Storage::disk('avatars')->put($path, $file->getContent(), [
            'visibility'  => 'public',
            'ContentType' => $file->getMimeType() ?? 'image/jpeg',
        ]);

        return $this->publicUrl('avatars', $path);
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

        // Supabase Storage rejects overwriting an existing object via multipart upload.
        // Delete the old file first so the upload always starts fresh.
        if (Storage::disk('videos')->exists($path)) {
            Storage::disk('videos')->delete($path);
        }

        // Explicit options help with S3-compatible endpoints
        $options = [
            'visibility'  => 'private',
            'ContentType' => $file->getMimeType() ?? 'video/mp4',
        ];

        // Stream the file instead of loading it entirely into memory
        $stream = fopen($file->getRealPath(), 'rb');
        Storage::disk('videos')->put($path, $stream, $options);
        if (is_resource($stream)) {
            fclose($stream);
        }

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
