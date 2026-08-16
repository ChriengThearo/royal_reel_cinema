<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Royal Reel Cinema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/movies.css') }}" rel="stylesheet">
</head>
<body>

<!-- ─────────────── Navbar ─────────────── -->
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
            <i class="bi bi-search fs-5" style="cursor:pointer;"></i>
            <i class="bi bi-bell fs-5" style="cursor:pointer;"></i>

            @auth
                <span class="text-white-50 small d-none d-lg-inline">
                    <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                </span>
                @if(Auth::user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}"
                       class="btn btn-sm"
                       style="background:rgba(255,215,0,0.15); color:rgba(255,215,0,0.9); border:1px solid rgba(255,215,0,0.3); border-radius:50px; font-size:0.8rem; padding:0.3rem 1rem;">
                        <i class="bi bi-shield-lock me-1"></i>Admin
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit"
                            class="btn btn-sm"
                            style="background:rgba(255,255,255,0.1); color:#fff; border:1px solid rgba(255,255,255,0.2); border-radius:50px; font-size:0.8rem; padding:0.3rem 1rem;">
                        Sign Out
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="btn btn-sm"
                   style="background:rgba(255,255,255,0.1); color:#fff; border:1px solid rgba(255,255,255,0.2); border-radius:50px; font-size:0.8rem; padding:0.3rem 1rem;">
                    <i class="bi bi-person me-1"></i>Sign In
                </a>
            @endauth
        </div>
    </div>
</nav>

<!-- ─────────────── Hero ─────────────── -->
@if($featured)
<section class="hero" style="{{ $featured->backdrop_url ? 'background-image: linear-gradient(to right, rgba(0,0,0,0.85) 40%, rgba(0,0,0,0.15)), url('.e($featured->backdrop_url).');' : '' }}">
    <div class="container ps-4 ps-lg-5">
        <div class="col-lg-6">
            <p class="text-white-50 mb-1 small text-uppercase">Featured Film</p>
            <h1 class="hero-title mb-3">{{ $featured->title }}</h1>
            @if($featured->description)
                <p class="hero-desc mb-4">{{ Str::limit($featured->description, 180) }}</p>
            @endif
            <div class="d-flex gap-3 flex-wrap">
                <a href="{{ route('movies.show', $featured->slug) }}"
                   class="btn btn-watch d-flex align-items-center gap-2 text-decoration-none">
                    <i class="bi bi-play-fill"></i> Watch Now
                </a>
                <a href="{{ route('movies.show', $featured->slug) }}"
                   class="btn btn-details d-flex align-items-center gap-2 text-decoration-none">
                    Details <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>
@else
{{-- Fallback hero when no movies are published yet --}}
<section class="hero">
    <div class="container ps-4 ps-lg-5">
        <div class="col-lg-6">
            <p class="text-white-50 mb-1 small text-uppercase">Welcome</p>
            <h1 class="hero-title mb-3">Royal Reel Cinema</h1>
            <p class="hero-desc mb-4">Your destination for great films. Check back soon for new releases.</p>
        </div>
    </div>
</section>
@endif

<!-- ─────────────── Genres ─────────────── -->
<section class="py-4 px-4 px-lg-5">
    <div class="scroll-row">
        <span class="genre-badge active" data-genre="all">All</span>
        @foreach($genres as $genre)
            <span class="genre-badge" data-genre="{{ $genre->id }}">{{ $genre->name }}</span>
        @endforeach
    </div>
</section>

<!-- ─────────────── Trending Movies ─────────────── -->
@if($trending->isNotEmpty())
<section class="pb-4 px-4 px-lg-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="section-title mb-0">Trending Movies</h2>
        <a href="#" class="text-white-50 small text-decoration-none">See all <i class="bi bi-chevron-right"></i></a>
    </div>
    <div class="scroll-row">
        @foreach($trending as $movie)
            <a href="{{ route('movies.show', $movie->slug) }}" class="movie-card text-decoration-none"
               data-genres="{{ $movie->genres->pluck('id')->join(',') }}">
                <img src="{{ $movie->poster_url ?? asset('images/poster-placeholder.svg') }}"
                     alt="{{ e($movie->title) }}"
                     loading="lazy"
                     onerror="this.src='{{ asset('images/poster-placeholder.svg') }}'">
                <div class="movie-card-overlay">
                    <p class="movie-card-title">{{ $movie->title }}</p>
                    <p class="movie-card-meta">
                        {{ $movie->releaseYear() }}{{ $movie->releaseYear() && $movie->genreLabel() ? ' · ' : '' }}{{ $movie->genreLabel() }}
                    </p>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

<!-- ─────────────── Continue Watching (auth users only) ─────────────── -->
@auth
    @if($continueWatching->isNotEmpty())
    <section class="pb-5 px-4 px-lg-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="section-title mb-0">Continue Watching</h2>
            <a href="#" class="text-white-50 small text-decoration-none">See all <i class="bi bi-chevron-right"></i></a>
        </div>
        <div class="scroll-row">
            @foreach($continueWatching as $movie)
                @php
                    /** @var \App\Models\WatchHistory|null $history */
                    $history = $movie->getRelation('userHistory');
                    $percent = $history?->progressPercent();
                @endphp
                <a href="{{ route('movies.show', $movie->slug) }}" class="movie-card card-lg text-decoration-none">
                    <img src="{{ $movie->poster_url ?? asset('images/poster-placeholder.svg') }}"
                         alt="{{ e($movie->title) }}"
                         loading="lazy"
                         onerror="this.src='{{ asset('images/poster-placeholder.svg') }}'">
                    <div class="movie-card-overlay">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <p class="movie-card-title mb-0">{{ $movie->title }}</p>
                            <i class="bi bi-play-circle-fill"></i>
                        </div>
                        @if($percent !== null)
                            <div class="progress" style="height:3px; background:rgba(255,255,255,0.2);">
                                <div class="progress-bar bg-white" style="width:{{ $percent }}%;"></div>
                            </div>
                            <p class="movie-card-meta mt-1">{{ $percent }}% watched</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </section>
    @endif
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Genre filter
    document.querySelectorAll('.genre-badge').forEach(badge => {
        badge.addEventListener('click', function () {
            document.querySelectorAll('.genre-badge').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const selected = this.dataset.genre;
            document.querySelectorAll('.movie-card[data-genres]').forEach(card => {
                if (selected === 'all') {
                    card.style.display = '';
                } else {
                    const genres = card.dataset.genres ? card.dataset.genres.split(',') : [];
                    card.style.display = genres.includes(selected) ? '' : 'none';
                }
            });
        });
    });
</script>
</body>
</html>
