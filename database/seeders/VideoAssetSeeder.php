<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\VideoAsset;
use Illuminate\Database\Seeder;

class VideoAssetSeeder extends Seeder
{
    public function run(): void
    {
        $slugs = [
            'sample-feature-one',
            'sample-feature-two',
            'sample-documentary',
            'sample-short-film',
        ];

        foreach ($slugs as $slug) {
            $movie = Movie::where('slug', $slug)->firstOrFail();

            VideoAsset::firstOrCreate(
                ['movie_id' => $movie->id, 'quality' => '1080p'],
                [
                    // Placeholder key — NOT a real uploaded file.
                    // Replace with a real Supabase path when uploading actual content.
                    'storage_key' => "{$movie->id}/1080p.mp4",
                    'format'      => 'mp4',
                    'size_mb'     => null,
                ]
            );
        }
    }
}
