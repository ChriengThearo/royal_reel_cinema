<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $movie->title }} · Royal Reel Cinema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/movies.css') }}" rel="stylesheet">
    <style>
        .player-area {
            position: relative;
            width: 100%;
            background: #000;
            border-radius: 16px;
            overflow: hidden;
            min-height: 56.25vw; /* 16:9 aspect fallback */
            max-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .player-area video {
            width: 100%;
            height: 100%;
            max-height: 70vh;
            display: block;
            border-radius: 16px;
        }

        .player-loading {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #0d0d0d;
            border-radius: 16px;
            z-index: 2;
        }

        /* ── Paywall ── */
        .paywall-card {
            background: #1a1a1a;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            padding: 3rem 2rem;
            text-align: center;
            max-width: 520px;
            margin: 0 auto;
        }

        .paywall-icon {
            font-size: 3.5rem;
            color: rgba(255,255,255,0.2);
            margin-bottom: 1.25rem;
        }

        .back-link {
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            font-size: 0.88rem;
            transition: color 0.2s;
        }

        .back-link:hover { color: #fff; }

        .movie-meta-pill {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 50px;
            font-size: 0.78rem;
            padding: 0.25rem 0.75rem;
            color: rgba(255,255,255,0.6);
            display: inline-block;
        }
    </style>
</head>
<body>

{{-- ─────────────── Navbar ─────────────── --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-transparent position-absolute w-100 z-3 px-4 px-lg-5 pt-3">
    <a class="navbar-brand" href="{{ route('home') }}">
        <span><i class="bi bi-film me-1"></i>RoyalReel</span>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-4 gap-2">
            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
            <li class="nav-item"><a class="nav-link active" href="#">Movies</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Series</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Kids</a></li>
        </ul>
        <div class="ms-auto d-flex align-items-center gap-3">
            @auth
                <span class="text-white-50 small d-none d-lg-inline">
                    <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                </span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit"
                            class="btn btn-sm"
                            style="background:rgba(255,255,255,0.1); color:#fff; border:1px solid rgba(255,255,255,0.2); border-radius:50px; font-size:0.8rem; padding:0.3rem 1rem;">
                        Sign Out
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>

{{-- ─────────────── Page Content ─────────────── --}}
<div class="container px-4 px-lg-5" style="padding-top: 7rem; padding-bottom: 4rem;">

    <a href="{{ route('home') }}" class="back-link mb-4 d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> Back to browse
    </a>

    {{-- Movie metadata --}}
    <div class="mb-4">
        <h1 class="fw-bold mb-2" style="font-size: clamp(1.5rem, 4vw, 2.5rem);">{{ $movie->title }}</h1>
        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            @if($movie->releaseYear())
                <span class="movie-meta-pill">{{ $movie->releaseYear() }}</span>
            @endif
            @if($movie->age_rating)
                <span class="movie-meta-pill">{{ $movie->age_rating }}</span>
            @endif
            @if($movie->duration_minutes)
                <span class="movie-meta-pill">
                    {{ intdiv($movie->duration_minutes, 60) }}h {{ $movie->duration_minutes % 60 }}m
                </span>
            @endif
            @foreach($movie->genres as $genre)
                <span class="movie-meta-pill">{{ $genre->name }}</span>
            @endforeach
            @if($movie->access_type === 'subscription')
                <span class="movie-meta-pill" style="border-color:rgba(255,215,0,0.4); color:rgba(255,215,0,0.85);">
                    <i class="bi bi-star-fill me-1" style="font-size:0.65rem;"></i>
                    {{ $movie->requiredPlan?->name ?? 'Subscription' }}
                </span>
            @endif
        </div>
        @if($movie->description)
            <p style="color:rgba(255,255,255,0.65); max-width: 680px; font-size:0.95rem; line-height:1.7;">
                {{ $movie->description }}
            </p>
        @endif
    </div>

    @if($canWatch)
        {{-- ── Video Player ─────────────────────────────────────────────── --}}
        <div class="player-area mt-2" id="player-wrapper">

            {{-- Loading state shown while the signed URL is being fetched --}}
            <div class="player-loading" id="player-loading">
                <div class="spinner-border text-light mb-3" role="status" style="width:2.5rem;height:2.5rem;">
                    <span class="visually-hidden">Loading…</span>
                </div>
                <p style="color:rgba(255,255,255,0.4); font-size:0.88rem;">Preparing stream…</p>
            </div>

            {{-- The <video> element — src is set by JS after fetching the signed URL --}}
            <video id="movie-player"
                   controls
                   preload="metadata"
                   poster="{{ $movie->backdrop_url ?? $movie->poster_url ?? '' }}"
                   style="display:none;">
                Your browser does not support HTML5 video.
            </video>
        </div>

        <p class="mt-3" style="color:rgba(255,255,255,0.4); font-size:0.8rem;">
            <i class="bi bi-shield-lock me-1"></i>
            Stream URL is personalised and expires after 2 hours. Do not share it.
        </p>

        @auth
        <p style="color:rgba(255,255,255,0.5); font-size:0.85rem;">
            Watching as <strong class="text-white">{{ Auth::user()->name }}</strong>
        </p>
        @endauth

    @else
        {{-- ── Paywall ──────────────────────────────────────────────────── --}}
        <div class="paywall-card mt-2">
            <i class="bi bi-lock paywall-icon"></i>
            <h4 class="fw-bold mb-2">Subscription Required</h4>
            @if($movie->requiredPlan)
                <p style="color:rgba(255,255,255,0.5); font-size:0.9rem; margin-bottom:1.5rem;">
                    <strong>{{ $movie->title }}</strong> is only available on the
                    <strong>{{ $movie->requiredPlan->name }}</strong> plan
                    ({{ $movie->requiredPlan->currency }} {{ number_format($movie->requiredPlan->price, 2) }}/{{ $movie->requiredPlan->billing_cycle }}).
                </p>
            @else
                <p style="color:rgba(255,255,255,0.5); font-size:0.9rem; margin-bottom:1.5rem;">
                    <strong>{{ $movie->title }}</strong> requires an active subscription.
                    Unlock this and thousands of other films.
                </p>
            @endif

            <a href="{{ route('home') }}"
               class="btn btn-watch d-inline-flex align-items-center gap-2 text-decoration-none">
                <i class="bi bi-star"></i> View Plans
            </a>
        </div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@if($canWatch)
<script>
    (function () {
        const streamUrl = @json(route('movies.stream-url', $movie->slug));
        const player    = document.getElementById('movie-player');
        const loading   = document.getElementById('player-loading');

        fetch(streamUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(res => {
            if (!res.ok) throw new Error('Could not load stream (' + res.status + ')');
            return res.json();
        })
        .then(data => {
            player.src = data.url;
            loading.style.display = 'none';
            player.style.display  = 'block';
        })
        .catch(err => {
            loading.innerHTML = `
                <i class="bi bi-exclamation-circle" style="font-size:2.5rem; color:rgba(255,100,100,0.6); margin-bottom:0.75rem;"></i>
                <p style="color:rgba(255,255,255,0.5); font-size:0.88rem;">${err.message}</p>
                <button onclick="location.reload()" class="btn btn-sm mt-2"
                    style="background:rgba(255,255,255,0.1); color:#fff; border-radius:50px; border:1px solid rgba(255,255,255,0.2);">
                    Try again
                </button>`;
        });
    })();
</script>
@endif

</body>
</html>
