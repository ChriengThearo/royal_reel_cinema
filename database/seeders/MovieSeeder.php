<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds a small set of clearly placeholder movies (no real titles, no copyrighted images).
 * poster_url is intentionally left null on some entries to exercise the fallback image logic.
 */
class MovieSeeder extends Seeder
{
    public function run(): void
    {
        // ── Plans ─────────────────────────────────────────────────────────────
        $basic    = Plan::firstOrCreate(['name' => 'Basic'],    $this->planData('Basic',    4.99, '720p',  1));
        $standard = Plan::firstOrCreate(['name' => 'Standard'], $this->planData('Standard', 9.99, '1080p', 2));
        $premium  = Plan::firstOrCreate(['name' => 'Premium'],  $this->planData('Premium', 14.99, '4k',    4));

        // ── Genres ────────────────────────────────────────────────────────────
        $genres = collect([
            'Action', 'Adventure', 'Animation', 'Comedy',
            'Drama', 'Horror', 'Romance', 'Sci-Fi', 'Thriller',
        ])->mapWithKeys(fn ($name) => [
            $name => Genre::firstOrCreate(['name' => $name]),
        ]);

        // ── Movies ────────────────────────────────────────────────────────────
        $movies = [
            [
                'title'            => 'Sample Feature One',
                'description'      => 'A gripping drama about an unlikely group of strangers who must work together to survive a cross-country journey.',
                'release_date'     => '2023-03-15',
                'duration_minutes' => 112,
                'poster_url'       => null,   // exercises fallback placeholder
                'access_type'      => 'free',
                'status'           => 'published',
                'age_rating'       => 'PG-13',
                'genres'           => ['Drama', 'Adventure'],
            ],
            [
                'title'            => 'Sample Feature Two',
                'description'      => 'A fast-paced action thriller where a former spy is pulled back into the field for one last mission.',
                'release_date'     => '2023-07-20',
                'duration_minutes' => 98,
                'poster_url'       => null,   // exercises fallback placeholder
                'access_type'      => 'subscription',
                'status'           => 'published',
                'age_rating'       => 'R',
                'genres'           => ['Action', 'Thriller'],
            ],
            [
                'title'            => 'Sample Feature Three',
                'description'      => 'An animated comedy that follows a clumsy young inventor whose gadgets keep going hilariously wrong.',
                'release_date'     => '2024-01-10',
                'duration_minutes' => 85,
                'poster_url'       => null,
                'access_type'      => 'free',
                'status'           => 'published',
                'age_rating'       => 'G',
                'genres'           => ['Animation', 'Comedy'],
            ],
            [
                'title'            => 'Sample Feature Four',
                'description'      => 'A sci-fi epic set 300 years in the future, where humanity faces its first contact with an alien civilisation.',
                'release_date'     => '2024-04-05',
                'duration_minutes' => 143,
                'poster_url'       => null,   // exercises fallback placeholder
                'access_type'      => 'subscription',
                'status'           => 'published',
                'age_rating'       => 'PG-13',
                'genres'           => ['Sci-Fi', 'Adventure'],
                'required_plan'    => 'Premium',
            ],
            [
                'title'            => 'Sample Feature Five',
                'description'      => 'A heartfelt romance set against the backdrop of a small coastal town where two strangers meet by chance.',
                'release_date'     => '2024-06-14',
                'duration_minutes' => 105,
                'poster_url'       => null,
                'access_type'      => 'subscription',
                'status'           => 'published',
                'age_rating'       => 'PG',
                'genres'           => ['Romance', 'Drama'],
            ],
            [
                'title'            => 'Sample Horror Short',
                'description'      => 'A chilling anthology of three short horror stories with an unexpected shared ending.',
                'release_date'     => '2023-10-31',
                'duration_minutes' => 72,
                'poster_url'       => null,
                'access_type'      => 'subscription',
                'status'           => 'published',
                'age_rating'       => 'R',
                'genres'           => ['Horror'],
                'required_plan'    => 'Standard',
            ],
            [
                'title'            => 'Unreleased Draft Film',
                'description'      => 'This movie is still in draft and should not appear on the homepage.',
                'release_date'     => null,
                'duration_minutes' => null,
                'poster_url'       => null,
                'access_type'      => 'free',
                'status'           => 'draft', // intentionally draft — should NOT appear in published queries
                'age_rating'       => null,
                'genres'           => [],
            ],
        ];

        foreach ($movies as $data) {
            $requiredPlan = null;
            if (!empty($data['required_plan'])) {
                $requiredPlan = match ($data['required_plan']) {
                    'Basic'    => $basic,
                    'Standard' => $standard,
                    'Premium'  => $premium,
                    default    => null,
                };
            }

            $slug  = Str::slug($data['title']);
            $movie = Movie::firstOrCreate(
                ['slug' => $slug],
                [
                    'title'            => $data['title'],
                    'description'      => $data['description'],
                    'release_date'     => $data['release_date'],
                    'duration_minutes' => $data['duration_minutes'],
                    'poster_url'       => $data['poster_url'],
                    'backdrop_url'     => null,
                    'trailer_url'      => null,
                    'age_rating'       => $data['age_rating'],
                    'status'           => $data['status'],
                    'access_type'      => $data['access_type'],
                    'required_plan_id' => $requiredPlan?->id,
                ]
            );

            if (!empty($data['genres'])) {
                $genreIds = collect($data['genres'])
                    ->map(fn ($name) => $genres[$name]->id)
                    ->all();
                $movie->genres()->syncWithoutDetaching($genreIds);
            }
        }
    }

    private function planData(string $name, float $price, string $quality, int $screens): array
    {
        return [
            'price'           => $price,
            'currency'        => 'USD',
            'billing_cycle'   => 'monthly',
            'max_quality'     => $quality,
            'screens_allowed' => $screens,
            'is_active'       => true,
        ];
    }
}
