<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} · Royal Reel Cinema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/movies.css') }}" rel="stylesheet">
    <style>
        .player-area {
            min-height: 60vh;
            background: #111;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.07);
        }

        .play-icon {
            font-size: 5rem;
            color: rgba(255,255,255,0.15);
            margin-bottom: 1.25rem;
        }

        .back-link {
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            font-size: 0.88rem;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: #fff;
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
            <li class="nav-item"><a class="nav-link active" href="{{ route('home') }}">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Movies</a></li>
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

{{-- ─────────────── Content ─────────────── --}}
<div class="container px-4 px-lg-5" style="padding-top: 7rem;">
    <a href="{{ route('home') }}" class="back-link mb-4 d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> Back to browse
    </a>

    <div class="player-area mt-3">
        <i class="bi bi-play-circle play-icon"></i>
        <h2 class="fw-bold mb-2">{{ $title }}</h2>
        <p style="color:rgba(255,255,255,0.4); font-size:0.9rem;">
            Streaming content would play here.
        </p>
    </div>

    <p class="mt-4" style="color:rgba(255,255,255,0.5); font-size:0.85rem;">
        Watching as <strong class="text-white">{{ Auth::user()->name }}</strong>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
