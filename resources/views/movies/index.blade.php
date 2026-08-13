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
    <a class="navbar-brand" href="#">
        <span><i class="bi bi-film me-1"></i>RoyalReel</span>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-4 gap-2">
            <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Movies</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Series</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Kids</a></li>
        </ul>
        <div class="ms-auto d-flex align-items-center gap-3">
            <i class="bi bi-search fs-5" style="cursor:pointer;"></i>
            <i class="bi bi-bell fs-5" style="cursor:pointer;"></i>
            <img src="https://i.pravatar.cc/32" alt="avatar" class="rounded-circle" width="32" height="32" style="cursor:pointer;">
        </div>
    </div>
</nav>

<!-- ─────────────── Hero ─────────────── -->
<section class="hero">
    <div class="container ps-4 ps-lg-5">
        <div class="col-lg-6">
            <p class="text-white-50 mb-1 small text-uppercase">Featured Film</p>
            <h1 class="hero-title mb-3">Thearo, INC.</h1>
            <p class="hero-desc mb-4">
                Animated film that explores the world of Monstropolis,
                where monsters generate their city's power by scaring children at night.
            </p>
            <div class="d-flex gap-3 flex-wrap">
                <button class="btn btn-watch d-flex align-items-center gap-2">
                    <i class="bi bi-play-fill"></i> Watch Now
                </button>
                <button class="btn btn-details d-flex align-items-center gap-2">
                    Details <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- ─────────────── Genres ─────────────── -->
<section class="py-4 px-4 px-lg-5">
    <div class="scroll-row">
        <span class="genre-badge active">All</span>
        <span class="genre-badge">Action</span>
        <span class="genre-badge">Animation</span>
        <span class="genre-badge">Comedy</span>
        <span class="genre-badge">Drama</span>
        <span class="genre-badge">Horror</span>
        <span class="genre-badge">Sci-Fi</span>
        <span class="genre-badge">Romance</span>
        <span class="genre-badge">Thriller</span>
    </div>
</section>

<!-- ─────────────── Trending Movies ─────────────── -->
<section class="pb-4 px-4 px-lg-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="section-title mb-0">Trending Movies</h2>
        <a href="#" class="text-white-50 small text-decoration-none">See all <i class="bi bi-chevron-right"></i></a>
    </div>
    <div class="scroll-row">

        <div class="movie-card">
            <img src="https://m.media-amazon.com/images/M/MV5BNDk3NjMwMDMtNDcwNC00NTkwLTk1ZTMtNGYwMzdmMzZlMzdjXkEyXkFqcGc@._V1_FMjpg_UX1000_.jpg" alt="The Good Dinosaur">
            <div class="movie-card-overlay">
                <p class="movie-card-title">The Good Dinosaur</p>
                <p class="movie-card-meta">2015 · Animation</p>
            </div>
        </div>

        <div class="movie-card">
            <img src="https://m.media-amazon.com/images/M/MV5BOGJmNGM1ZmMtYjU3OS00OWYyLWE4ZWYtNTJmNjM2ZjBlMGFiXkEyXkFqcGc@._V1_.jpg" alt="Aladdin">
            <div class="movie-card-overlay">
                <p class="movie-card-title">Aladdin</p>
                <p class="movie-card-meta">1992 · Animation</p>
            </div>
        </div>

        <div class="movie-card">
            <img src="https://m.media-amazon.com/images/M/MV5BZDJhNjI4ZGItOTM5Mi00YTQ4LWFlZmUtYTQ3OTI3ZGJmMWYyXkEyXkFqcGc@._V1_.jpg" alt="Luca">
            <div class="movie-card-overlay">
                <p class="movie-card-title">Luca</p>
                <p class="movie-card-meta">2021 · Animation</p>
            </div>
        </div>

        <div class="movie-card">
            <img src="https://m.media-amazon.com/images/M/MV5BMTkwMTc0ODYxNl5BMl5BanBnXkFtZTcwNDA3NzYxMw@@._V1_.jpg" alt="Tangled">
            <div class="movie-card-overlay">
                <p class="movie-card-title">Tangled</p>
                <p class="movie-card-meta">2010 · Animation</p>
            </div>
        </div>

        <div class="movie-card">
            <img src="https://m.media-amazon.com/images/M/MV5BZjZhYzFiZTUtNTNjZi00YTI4LWE2ZTEtYThkNTA3NDFlMDkwXkEyXkFqcGc@._V1_.jpg" alt="Coco">
            <div class="movie-card-overlay">
                <p class="movie-card-title">Coco</p>
                <p class="movie-card-meta">2017 · Animation</p>
            </div>
        </div>

        <div class="movie-card">
            <img src="https://m.media-amazon.com/images/M/MV5BNzgxMzc1MjYtZjMwZi00OGFmLTkwOWEtMWZkMmJhN2JmYWM1XkEyXkFqcGc@._V1_.jpg" alt="Moana">
            <div class="movie-card-overlay">
                <p class="movie-card-title">Moana</p>
                <p class="movie-card-meta">2016 · Animation</p>
            </div>
        </div>

    </div>
</section>

<!-- ─────────────── Continue Watching ─────────────── -->
<section class="pb-5 px-4 px-lg-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="section-title mb-0">Continue Watching</h2>
        <a href="#" class="text-white-50 small text-decoration-none">See all <i class="bi bi-chevron-right"></i></a>
    </div>
    <div class="scroll-row">

        <div class="movie-card card-lg">
            <img src="https://m.media-amazon.com/images/M/MV5BNWIyNmIxNWEtNmNhZC00ZjgwLWJhZWYtNTdjZjdmZmQ0NTUwXkEyXkFqcGc@._V1_.jpg" alt="Finding Nemo">
            <div class="movie-card-overlay">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <p class="movie-card-title mb-0">Finding Nemo</p>
                    <i class="bi bi-play-circle-fill"></i>
                </div>
                <div class="progress" style="height:3px; background:rgba(255,255,255,0.2);">
                    <div class="progress-bar bg-white" style="width:65%;"></div>
                </div>
                <p class="movie-card-meta mt-1">65% watched</p>
            </div>
        </div>

        <div class="movie-card card-lg">
            <img src="https://m.media-amazon.com/images/M/MV5BMDU2ZWJlMjktMTRhMy00ZTA5LWEzNDgtYmNmZTEwZTViZWJkXkEyXkFqcGc@._V1_.jpg" alt="Up">
            <div class="movie-card-overlay">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <p class="movie-card-title mb-0">Up</p>
                    <i class="bi bi-play-circle-fill"></i>
                </div>
                <div class="progress" style="height:3px; background:rgba(255,255,255,0.2);">
                    <div class="progress-bar bg-white" style="width:30%;"></div>
                </div>
                <p class="movie-card-meta mt-1">30% watched</p>
            </div>
        </div>

        <div class="movie-card card-lg">
            <img src="https://m.media-amazon.com/images/M/MV5BOTgxMDQwMDk0OF5BMl5BanBnXkFtZTgwNjU5OTg2NDE@._V1_.jpg" alt="Inside Out">
            <div class="movie-card-overlay">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <p class="movie-card-title mb-0">Inside Out</p>
                    <i class="bi bi-play-circle-fill"></i>
                </div>
                <div class="progress" style="height:3px; background:rgba(255,255,255,0.2);">
                    <div class="progress-bar bg-white" style="width:80%;"></div>
                </div>
                <p class="movie-card-meta mt-1">80% watched</p>
            </div>
        </div>

    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.genre-badge').forEach(badge => {
        badge.addEventListener('click', function () {
            document.querySelectorAll('.genre-badge').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>
</body>
</html>
