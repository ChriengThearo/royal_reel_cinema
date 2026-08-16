<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser  = User::where('email', 'admin@example.com')->firstOrFail();
        $premiumPlan = Plan::where('name', 'Premium')->firstOrFail();

        $genres = Genre::all()->keyBy('name');

        $movies = [
            [
                'title'            => 'Sample Feature One',
                'slug'             => 'sample-feature-one',
                'description'      => 'A placeholder action movie for testing.',
                'access_type'      => 'free',
                'required_plan_id' => null,
                'status'           => 'published',
                'poster_url'       => null,
                'genres'           => ['Action'],
            ],
            [
                'title'            => 'Sample Feature Two',
                'slug'             => 'sample-feature-two',
                'description'      => 'A placeholder drama movie for testing.',
                'access_type'      => 'subscription',
                'required_plan_id' => null,     // any active plan
                'status'           => 'published',
                'poster_url'       => null,
                'genres'           => ['Drama', 'Comedy'],
            ],
            [
                'title'            => 'Sample Documentary',
                'slug'             => 'sample-documentary',
                'description'      => 'A placeholder sci-fi documentary.',
                'access_type'      => 'subscription',
                'required_plan_id' => $premiumPlan->id,
                'status'           => 'published',
                'poster_url'       => null,
                'genres'           => ['Sci-Fi'],
            ],
            [
                'title'            => 'Sample Short Film',
                'slug'             => 'sample-short-film',
                'description'      => 'A placeholder comedy short film.',
                'access_type'      => 'free',
                'required_plan_id' => null,
                'status'           => 'published',
                'poster_url'       => null,
                'genres'           => ['Comedy'],
            ],
        ];

        foreach ($movies as $data) {
            $genreNames = $data['genres'];
            unset($data['genres']);

            $movie = Movie::firstOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['created_by' => $adminUser->id])
            );

            $genreIds = collect($genreNames)
                ->map(fn ($name) => $genres[$name]?->id)
                ->filter()
                ->values()
                ->all();

            $movie->genres()->syncWithoutDetaching($genreIds);
        }
    }
}
