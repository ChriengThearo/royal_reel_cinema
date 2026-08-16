<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMovieRequest;
use App\Http\Requests\Admin\UpdateMovieRequest;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Plan;
use App\Models\VideoAsset;
use App\Services\SupabaseStorageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    public function __construct(private readonly SupabaseStorageService $storage) {}

    public function index()
    {
        $movies = Movie::with('genres')->latest()->paginate(20);
        return view('admin.movies.index', compact('movies'));
    }

    public function create()
    {
        $genres = Genre::orderBy('name')->get();
        $plans  = Plan::where('is_active', true)->orderBy('name')->get();
        return view('admin.movies.create', compact('genres', 'plans'));
    }

    public function store(StoreMovieRequest $request)
    {
        // ── Step 1: save metadata inside a transaction ────────────────────────
        DB::beginTransaction();
        try {
            $data             = $request->validated();
            $data['slug']     = filled($data['slug'] ?? null) ? $data['slug'] : Str::slug($data['title']);
            $data['created_by'] = auth()->id();

            $genreIds     = $data['genre_ids'] ?? [];
            $posterFile   = $request->file('poster');
            $videoFile    = $request->file('video');
            $videoQuality = $data['video_quality'] ?? '1080p';

            unset($data['genre_ids'], $data['poster'], $data['video'], $data['video_quality']);

            $movie = Movie::create($data);
            $movie->genres()->sync($genreIds);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to save movie: ' . $e->getMessage());
        }

        // ── Step 2: attempt file uploads independently (don't block on failure) ─
        $uploadWarnings = [];

        if ($posterFile) {
            try {
                $posterUrl = $this->storage->uploadPoster($movie->id, $posterFile);
                $movie->update(['poster_url' => $posterUrl]);
            } catch (\Throwable $e) {
                $uploadWarnings[] = 'Poster upload failed: ' . $e->getMessage();
            }
        }

        if ($videoFile) {
            try {
                $this->storage->uploadVideo($movie->id, $videoQuality, $videoFile);
            } catch (\Throwable $e) {
                $uploadWarnings[] = 'Video upload failed: ' . $e->getMessage();
            }
        }

        $message = "Movie \"{$movie->title}\" created.";
        if ($uploadWarnings) {
            return redirect()->route('admin.movies.index')
                ->with('success', $message)
                ->with('warning', implode(' | ', $uploadWarnings));
        }

        return redirect()->route('admin.movies.index')->with('success', $message);
    }

    public function edit(Movie $movie)
    {
        $movie->load(['genres', 'videoAssets']);
        $genres = Genre::orderBy('name')->get();
        $plans  = Plan::where('is_active', true)->orderBy('name')->get();
        return view('admin.movies.edit', compact('movie', 'genres', 'plans'));
    }

    public function update(UpdateMovieRequest $request, Movie $movie)
    {
        // ── Step 1: save metadata inside a transaction ────────────────────────
        DB::beginTransaction();
        try {
            $data         = $request->validated();
            $data['slug'] = filled($data['slug'] ?? null) ? $data['slug'] : Str::slug($data['title']);

            $genreIds     = $data['genre_ids'] ?? [];
            $posterFile   = $request->file('poster');
            $videoFile    = $request->file('video');
            $videoQuality = $data['video_quality'] ?? '1080p';

            unset($data['genre_ids'], $data['poster'], $data['video'], $data['video_quality']);

            $movie->update($data);
            $movie->genres()->sync($genreIds);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to save movie: ' . $e->getMessage());
        }

        // ── Step 2: attempt file uploads independently (don't block on failure) ─
        $uploadWarnings = [];

        if ($posterFile) {
            try {
                $posterUrl = $this->storage->uploadPoster($movie->id, $posterFile);
                $movie->update(['poster_url' => $posterUrl]);
            } catch (\Throwable $e) {
                $uploadWarnings[] = 'Poster upload failed: ' . $e->getMessage();
            }
        }

        if ($videoFile) {
            try {
                $this->storage->uploadVideo($movie->id, $videoQuality, $videoFile);
            } catch (\Throwable $e) {
                $uploadWarnings[] = 'Video upload failed: ' . $e->getMessage();
            }
        }

        $message = "Movie \"{$movie->title}\" updated.";
        if ($uploadWarnings) {
            return redirect()->route('admin.movies.index')
                ->with('success', $message)
                ->with('warning', implode(' | ', $uploadWarnings));
        }

        return redirect()->route('admin.movies.index')->with('success', $message);
    }

    public function destroy(Movie $movie)
    {
        $movie->delete();
        return redirect()->route('admin.movies.index')->with('success', "Movie \"{$movie->title}\" deleted.");
    }

    public function destroyVideo(Movie $movie, VideoAsset $asset)
    {
        $asset->delete();
        return back()->with('success', "Video quality {$asset->quality} removed.");
    }
}
