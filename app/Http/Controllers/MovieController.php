<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use App\Services\SupabaseStorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class MovieController extends Controller
{
    public function __construct(
        private readonly SupabaseStorageService $storage
    ) {}

    // ── Homepage ──────────────────────────────────────────────────────────────

    /**
     * Homepage: public.  Shows published movies grouped by section.
     * Continue Watching is only populated for authenticated users.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // ── Featured film: most recently published ────────────────────────────
        $featured = Movie::published()
            ->with('genres')
            ->latest()
            ->first();

        // ── Trending: latest 12 published movies ──────────────────────────────
        $trending = Movie::published()
            ->with('genres')
            ->latest()
            ->take(12)
            ->get();

        // ── Continue Watching: movies the auth user has started but not finished
        $continueWatching = collect();
        if ($user) {
            $continueWatching = Movie::published()
                ->with(['genres', 'watchHistories' => fn ($q) => $q->where('user_id', $user->id)])
                ->whereHas(
                    'watchHistories',
                    fn ($q) => $q->where('user_id', $user->id)
                                ->where('completed', false)
                                ->where('progress_seconds', '>', 0)
                )
                ->latest()
                ->take(6)
                ->get()
                // Attach the user's specific watch-history entry to each movie
                ->each(function (Movie $movie) use ($user) {
                    $movie->setRelation(
                        'userHistory',
                        $movie->watchHistories->first()
                    );
                });
        }

        // ── Genres for the filter bar ─────────────────────────────────────────
        $genres = Genre::orderBy('name')->get();

        return view('movies.index', compact(
            'featured',
            'trending',
            'continueWatching',
            'genres'
        ));
    }

    // ── Movie detail / player ─────────────────────────────────────────────────

    /**
     * Movie detail page.  Requires auth (set on the route).
     * Shows paywall if the user's subscription doesn't cover this movie.
     */
    public function show(Request $request, string $slug): View
    {
        $movie = Movie::published()
            ->with(['genres', 'requiredPlan'])
            ->where('slug', $slug)
            ->firstOrFail();

        $user       = $request->user();
        $canWatch   = $movie->canBeWatchedBy($user);

        return view('movies.show', compact('movie', 'canWatch'));
    }

    // ── Signed stream URL (AJAX, auth-gated) ─────────────────────────────────

    /**
     * Return a short-lived signed URL for the video player.
     * Called by the Blade view via fetch() — never embedded directly in HTML.
     *
     * GET /movies/{movie:slug}/stream-url
     */
    public function streamUrl(Request $request, string $slug): JsonResponse
    {
        $movie   = Movie::published()->where('slug', $slug)->firstOrFail();
        $user    = $request->user();
        $quality = $request->query('quality', '1080p');

        try {
            $url = $this->storage->getSignedVideoUrl($movie, $user, $quality);
        } catch (AccessDeniedHttpException $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }

        return response()->json(['url' => $url]);
    }
}
