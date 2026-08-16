<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['email' => 'jane@example.com',  'slug' => 'sample-feature-one', 'score' => 8],
            ['email' => 'john@example.com',  'slug' => 'sample-feature-two', 'score' => 6],
            ['email' => 'alice@example.com', 'slug' => 'sample-documentary', 'score' => 9],
            ['email' => 'jane@example.com',  'slug' => 'sample-short-film',  'score' => 7],
        ];

        foreach ($rows as $row) {
            $user  = User::where('email', $row['email'])->firstOrFail();
            $movie = Movie::where('slug', $row['slug'])->firstOrFail();

            Rating::firstOrCreate(
                ['user_id' => $user->id, 'movie_id' => $movie->id],
                ['score' => $row['score']]
            );
        }
    }
}
