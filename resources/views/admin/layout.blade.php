<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') · RoyalReel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --sidebar-w: 220px; --dark: #0d0d0d; --card: #1a1a1a; --border: rgba(255,255,255,0.08); }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--dark); color: #fff; font-family: 'Segoe UI', sans-serif; display: flex; min-height: 100vh; }

        /* ── Sidebar ── */
        #sidebar {
            width: var(--sidebar-w); min-width: var(--sidebar-w);
            background: #111; border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; height: 100vh; z-index: 100; overflow-y: auto;
        }
        .sidebar-brand {
            padding: 1.25rem 1rem;
            font-size: 1.1rem; font-weight: 800; color: #fff;
            border-bottom: 1px solid var(--border);
            text-decoration: none; display: block;
        }
        .sidebar-brand small { display: block; font-size: 0.65rem; font-weight: 400; color: rgba(255,255,255,0.35); letter-spacing: 0.1em; text-transform: uppercase; }
        .sidebar-nav { padding: 0.75rem 0; flex: 1; }
        .sidebar-nav .nav-label {
            font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.1em;
            color: rgba(255,255,255,0.25); padding: 0.75rem 1rem 0.25rem;
        }
        .sidebar-nav a {
            display: flex; align-items: center; gap: 0.6rem;
            padding: 0.55rem 1rem; color: rgba(255,255,255,0.6);
            text-decoration: none; font-size: 0.88rem; border-left: 2px solid transparent;
            transition: all 0.15s;
        }
        .sidebar-nav a:hover, .sidebar-nav a.active {
            color: #fff; background: rgba(255,255,255,0.05); border-left-color: #fff;
        }
        .sidebar-nav a i { font-size: 1rem; width: 1.1rem; text-align: center; }

        /* ── Main ── */
        #main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        #topbar {
            background: #111; border-bottom: 1px solid var(--border);
            padding: 0.75rem 1.5rem; display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }
        #topbar .page-title { font-weight: 700; font-size: 1rem; margin: 0; }
        #topbar .user-info { font-size: 0.83rem; color: rgba(255,255,255,0.55); display: flex; align-items: center; gap: 0.75rem; }
        #content { padding: 1.75rem 1.5rem; flex: 1; }

        /* ── Cards ── */
        .admin-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; }
        .admin-card-header { padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border); font-size: 0.88rem; font-weight: 600; }

        /* ── Tables ── */
        .admin-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
        .admin-table th { color: rgba(255,255,255,0.4); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.65rem 1rem; border-bottom: 1px solid var(--border); text-align: left; }
        .admin-table td { padding: 0.75rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.04); vertical-align: middle; }
        .admin-table tr:last-child td { border-bottom: none; }
        .admin-table tr:hover td { background: rgba(255,255,255,0.025); }

        /* ── Forms ── */
        .form-control-dark, .form-select-dark {
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);
            color: #fff; border-radius: 8px;
        }
        .form-control-dark:focus, .form-select-dark:focus {
            background: rgba(255,255,255,0.09); border-color: rgba(255,255,255,0.3); color: #fff; box-shadow: none;
        }
        .form-control-dark::placeholder { color: rgba(255,255,255,0.3); }
        .form-select-dark option { background: #1a1a1a; color: #fff; }
        .form-label-sm { font-size: 0.8rem; color: rgba(255,255,255,0.6); margin-bottom: 0.3rem; }
        .invalid-feedback { font-size: 0.78rem; }

        /* ── Badges ── */
        .badge-status-published { background: rgba(40,167,69,0.2); color: #5cb85c; border: 1px solid rgba(40,167,69,0.3); }
        .badge-status-draft     { background: rgba(255,193,7,0.15); color: #ffc107; border: 1px solid rgba(255,193,7,0.3); }
        .badge-status-archived  { background: rgba(108,117,125,0.2); color: #adb5bd; border: 1px solid rgba(108,117,125,0.3); }
        .badge-role-admin       { background: rgba(255,215,0,0.15); color: rgba(255,215,0,0.9); border: 1px solid rgba(255,215,0,0.3); }
        .badge-role-user        { background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.6); border: 1px solid rgba(255,255,255,0.15); }
        .badge-sub-active       { background: rgba(40,167,69,0.2); color: #5cb85c; border: 1px solid rgba(40,167,69,0.3); }
        .badge-sub-cancelled    { background: rgba(220,53,69,0.15); color: #dc3545; border: 1px solid rgba(220,53,69,0.25); }
        .badge-sub-expired      { background: rgba(108,117,125,0.15); color: #adb5bd; border: 1px solid rgba(108,117,125,0.25); }
        span.badge { border-radius: 50px; font-size: 0.72rem; font-weight: 600; padding: 0.25rem 0.65rem; }

        /* ── Buttons ── */
        .btn-admin-primary { background: #fff; color: #000; border: none; border-radius: 50px; font-weight: 600; font-size: 0.85rem; padding: 0.45rem 1.25rem; }
        .btn-admin-primary:hover { background: #e0e0e0; color: #000; }
        .btn-admin-ghost { background: rgba(255,255,255,0.07); color: #fff; border: 1px solid rgba(255,255,255,0.15); border-radius: 50px; font-size: 0.82rem; padding: 0.3rem 0.85rem; }
        .btn-admin-ghost:hover { background: rgba(255,255,255,0.12); color: #fff; }
        .btn-admin-danger { background: rgba(220,53,69,0.15); color: #dc3545; border: 1px solid rgba(220,53,69,0.25); border-radius: 50px; font-size: 0.82rem; padding: 0.3rem 0.85rem; }
        .btn-admin-danger:hover { background: rgba(220,53,69,0.25); color: #dc3545; }

        /* ── Alerts ── */
        .alert-dark-success { background: rgba(40,167,69,0.12); border: 1px solid rgba(40,167,69,0.25); color: #5cb85c; border-radius: 8px; padding: 0.65rem 1rem; font-size: 0.85rem; }
        .alert-dark-error   { background: rgba(220,53,69,0.12); border: 1px solid rgba(220,53,69,0.25); color: #dc3545; border-radius: 8px; padding: 0.65rem 1rem; font-size: 0.85rem; }
    </style>
    @yield('head')
</head>
<body>

{{-- ─────────────── Sidebar ─────────────── --}}
<nav id="sidebar">
    <a class="sidebar-brand" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-film me-1"></i>RoyalReel
        <small>Admin Panel</small>
    </a>
    <div class="sidebar-nav">
        <div class="nav-label">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <div class="nav-label">Catalog</div>
        <a href="{{ route('admin.movies.index') }}" class="{{ request()->routeIs('admin.movies.*') ? 'active' : '' }}">
            <i class="bi bi-play-circle"></i> Movies
        </a>
        <a href="{{ route('admin.genres.index') }}" class="{{ request()->routeIs('admin.genres.*') ? 'active' : '' }}">
            <i class="bi bi-tag"></i> Genres
        </a>
        <div class="nav-label">Commerce</div>
        <a href="{{ route('admin.plans.index') }}" class="{{ request()->routeIs('admin.plans.*') ? 'active' : '' }}">
            <i class="bi bi-star"></i> Plans
        </a>
        <a href="{{ route('admin.subscriptions.index') }}" class="{{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card"></i> Subscriptions
        </a>
        <a href="{{ route('admin.payments.index') }}" class="{{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i> Payments
        </a>
        <div class="nav-label">People</div>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Users
        </a>
        <div class="nav-label">Site</div>
        <a href="{{ route('home') }}">
            <i class="bi bi-house"></i> View Site
        </a>
    </div>
</nav>

{{-- ─────────────── Main ─────────────── --}}
<div id="main">
    <div id="topbar">
        <h1 class="page-title">@yield('page-title', 'Admin')</h1>
        <div class="user-info">
            <i class="bi bi-person-circle"></i>
            {{ Auth::user()->name }}
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn-admin-ghost btn">Sign Out</button>
            </form>
        </div>
    </div>

    <div id="content">
        @if(session('success'))
            <div class="alert-dark-success mb-3"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-dark-error mb-3"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
