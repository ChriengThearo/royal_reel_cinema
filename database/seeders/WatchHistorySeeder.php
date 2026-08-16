<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\User;
use App\Models\WatchHistory;
use Illuminate\Database\Seeder;

class WatchHistorySeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['email' => 'jane@example.com',  'slug' => 'sample-feature-one', 'progress_seconds' => 5400, 'completed' => true],
            ['email' => 'john@example.com',  'slug' => 'sample-feature-two', 'progress_seconds' => 1200, 'completed' => false],
            ['email' => 'alice@example.com', 'slug' => 'sample-documentary', 'progress_seconds' => 3000, 'completed' => false],
            ['email' => 'jane@example.com',  'slug' => 'sample-short-film',  'progress_seconds' =>  900, 'completed' => true],
        ];

        foreach ($rows as $row) {
            $user  = User::where('email', $row['email'])->firstOrFail();
            $movie = Movie::where('slug', $row['slug'])->firstOrFail();

            WatchHistory::firstOrCreate(
                ['user_id' => $user->id, 'movie_id' => $movie->id],
                [
                    'progress_seconds' => $row['progress_seconds'],
                    'completed'        => $row['completed'],
                    'last_watched_at'  => now(),
                ]
            );
        }
    }
}
