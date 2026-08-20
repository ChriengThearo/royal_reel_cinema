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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;

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

    // ── Admin utility: import a sample film into Supabase videos bucket ──────
    public function importSample(Request $request)
    {
        $request->validate([
            'url' => ['nullable','url'],
            'title' => ['nullable','string','max:255'],
            'quality' => ['nullable','in:1080p,720p,480p']
        ]);

        $sourceUrl = $request->input('url')
            ?? 'https://sample-videos.com/video321/mp4/720/big_buck_bunny_720p_1mb.mp4';
        $title     = $request->input('title') ?? 'Sample Short Film';
        $quality   = $request->input('quality') ?? '720p';

        try {
            // 1) Fetch sample binary
            $response = Http::timeout(120)->get($sourceUrl);
            if (!$response->ok()) {
                return back()->with('error', 'Failed to fetch sample video. HTTP ' . $response->status());
            }

            // 2) Create temporary UploadedFile
            $tmpPath = storage_path('app/tmp_sample_'.Str::random(8).'.mp4');
            file_put_contents($tmpPath, $response->body());
            $uploaded = new UploadedFile(
                path: $tmpPath,
                originalName: 'sample.mp4',
                mimeType: 'video/mp4',
                error: null,
                test: true
            );

            // 3) Create movie if needed
            $movie = Movie::firstOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'description' => 'A sample short film used for testing playback.',
                    'status' => 'published',
                    'access_type' => 'free',
                ]
            );

            // 4) Upload to Supabase Storage (videos bucket)
            $this->storage->uploadVideo($movie->id, $quality, $uploaded);

            // 5) Cleanup tmp file
            @unlink($tmpPath);

            return back()->with('success', "Imported '{$title}' as {$quality} and stored in Supabase 'videos' bucket.");
        } catch (\Throwable $e) {
            Log::error('Sample import failed', ['exception' => $e]);
            return back()->with('error', 'Sample import failed: ' . $e->getMessage());
        }
    }
}
