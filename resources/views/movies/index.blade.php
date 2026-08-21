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
        <img src="{{ asset('images/movie_logo.png') }}" alt="RoyalReel" style="height:38px; width:auto;">
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

        <!-- Top-right action cluster (search, bell, sign in) -->
        <div class="ms-auto action-cluster">
            <button class="icon-btn" aria-label="Search"><i class="bi bi-search fs-6"></i></button>
            <span class="action-divider" aria-hidden="true"></span>
            <button class="icon-btn" aria-label="Notifications"><i class="bi bi-bell fs-6"></i></button>
            <span class="action-divider" aria-hidden="true"></span>
            @auth
                <span class="text-white small ps-1 pe-2 d-none d-lg-inline" aria-label="Signed in user"><i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}</span>
                @if(Auth::user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}" class="action-link"><i class="bi bi-shield-lock me-1"></i>Admin</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="action-link">Sign Out</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="action-link"><i class="bi bi-person me-1"></i>Sign In</a>
            @endauth
        </div>
    </div>
</nav>

<!-- ─────────────── Hero ─────────────── -->
@if($featured)
<section class="hero" style="{{ $featured->backdrop_url ? 'background-image: url('.e($featured->backdrop_url).');' : '' }}">
    <div class="container ps-4 ps-lg-5 hero-inner">
        <!-- Optional poster: render only when we have art; otherwise the grid adapts -->
        @if(!empty($featured->poster_url))
            <div class="hero-poster d-none d-lg-block">
                <img src="{{ $featured->poster_url }}" alt="Poster: {{ e($featured->title) }}">
            </div>
        @endif

        <div class="col-lg-8 col-xl-7">
            <p class="hero-kicker mb-2">FEATURED FILM</p>
            <h1 class="hero-title mb-2">{{ $featured->title }}</h1>
            @if($featured->description)
                <p class="hero-desc">{{ Str::limit($featured->description, 180) }}</p>
            @endif

            <!-- Metadata row: rating • duration • genres -->
            <div class="hero-meta" aria-label="Film details">
                @if(!empty($featured->rating))
                    <span class="meta-badge rating" title="Rating">{{ $featured->rating }}</span>
                    <span class="meta-sep">•</span>
                @endif
                @if(!empty($featured->runtime_minutes))
                    <span class="meta-item">{{ floor($featured->runtime_minutes / 60) }}h {{ $featured->runtime_minutes % 60 }}m</span>
                    <span class="meta-sep">•</span>
                @endif
                @if($featured->genres && $featured->genres->isNotEmpty())
                    <span class="meta-item">{{ $featured->genres->take(2)->pluck('name')->join(' · ') }}</span>
                @endif
            </div>

            <div class="d-flex gap-3 flex-wrap mb-4">
                <a href="{{ route('movies.show', $featured->slug) }}"
                   class="btn-watch text-decoration-none" aria-label="Watch Now: {{ e($featured->title) }}">
                    <i class="bi bi-play-fill"></i> Watch Now
                </a>
                <a href="{{ route('movies.show', $featured->slug) }}"
                   class="btn-details text-decoration-none" aria-label="View details: {{ e($featured->title) }}">
                    Details <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>
@else
{{-- Fallback hero when no movies are published yet --}}
<section class="hero">
    <div class="container ps-4 ps-lg-5 hero-inner">
        <div class="col-lg-8 col-xl-7">
            <p class="hero-kicker mb-2">WELCOME</p>
            <h1 class="hero-title mb-2">Royal Reel Cinema</h1>
            <p class="hero-desc">Your destination for great films. Check back soon for new releases.</p>
            <div class="d-flex gap-3 flex-wrap mb-4">
                <a href="#" class="btn-watch text-decoration-none"><i class="bi bi-play-fill"></i> Explore</a>
                <a href="#" class="btn-details text-decoration-none">Browse <i class="bi bi-chevron-right"></i></a>
            </div>
        </div>
    </div>
</section>
@endif

<!-- ─────────────── Genres ─────────────── -->
<section class="py-4 px-4 px-lg-5">
    <div class="scroll-row" role="tablist" aria-label="Browse by genre">
        <span class="genre-badge active" role="tab" aria-selected="true" tabindex="0" data-genre="all">All</span>
        @foreach($genres as $genre)
            <span class="genre-badge" role="tab" aria-selected="false" tabindex="-1" data-genre="{{ $genre->id }}">{{ $genre->name }}</span>
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
                        @if($percent)
                            <div class="progress" style="height: 4px; background: rgba(255,255,255,0.18);">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $percent }}%"></div>
                            </div>
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
  // Genre pills: active state toggle for prototype; integrate with filtering as needed
  document.querySelectorAll('.genre-badge').forEach(pill => {
    pill.addEventListener('click', () => {
      document.querySelectorAll('.genre-badge').forEach(p => p.classList.remove('active'));
      pill.classList.add('active');
      document.querySelectorAll('.genre-badge').forEach(p => {
        p.setAttribute('aria-selected', p === pill ? 'true' : 'false');
        p.setAttribute('tabindex', p === pill ? '0' : '-1');
      });
    });
  });
</script>
</body>
</html>
