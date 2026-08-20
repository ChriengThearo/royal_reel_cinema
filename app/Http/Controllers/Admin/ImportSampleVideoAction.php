<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Services\SupabaseStorageService;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Lightweight action controller to fetch and store a sample video
 * in Supabase Storage (videos bucket) and create a demo Movie entry.
 */
class ImportSampleVideoAction extends Controller
{
    public function __invoke(Request $request, SupabaseStorageService $storage)
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

        // 1) Download the source video to memory (could be streamed for large files)
        $response = Http::timeout(120)->get($sourceUrl);
        if (!$response->ok()) {
            return back()->with('error', 'Failed to fetch sample video.');
        }
        $binary = $response->body();

        // 2) Create a temporary UploadedFile instance
        $tmpPath = storage_path('app/tmp_sample_'.Str::random(8).'.mp4');
        file_put_contents($tmpPath, $binary);
        $uploaded = new UploadedFile(
            path: $tmpPath,
            originalName: 'sample.mp4',
            mimeType: 'video/mp4',
            error: null,
            test: true
        );

        // 3) Create a minimal Movie record if none exists
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
        $storage->uploadVideo($movie->id, $quality, $uploaded);

        // 5) Cleanup tmp file
        @unlink($tmpPath);

        return back()->with('success', "Imported '{$title}' as {$quality} and stored in Supabase 'videos' bucket.");
    }
}
