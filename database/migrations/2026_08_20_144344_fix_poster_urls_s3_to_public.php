<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Replace the S3 API path with the Supabase public CDN path in poster_url.
     *
     * Old: https://{ref}.supabase.co/storage/v1/s3/posters/{id}.jpg
     * New: https://{ref}.supabase.co/storage/v1/object/public/posters/{id}.jpg
     */
    public function up(): void
    {
        DB::table('movies')
            ->whereNotNull('poster_url')
            ->where('poster_url', 'like', '%/storage/v1/s3/%')
            ->get(['id', 'poster_url'])
            ->each(function ($movie) {
                $newUrl = str_replace('/storage/v1/s3/', '/storage/v1/object/public/', $movie->poster_url);
                DB::table('movies')->where('id', $movie->id)->update(['poster_url' => $newUrl]);
            });
    }

    public function down(): void
    {
        DB::table('movies')
            ->whereNotNull('poster_url')
            ->where('poster_url', 'like', '%/storage/v1/object/public/%')
            ->get(['id', 'poster_url'])
            ->each(function ($movie) {
                $oldUrl = str_replace('/storage/v1/object/public/', '/storage/v1/s3/', $movie->poster_url);
                DB::table('movies')->where('id', $movie->id)->update(['poster_url' => $oldUrl]);
            });
    }
};
