<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In · Royal Reel Cinema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/movies.css') }}" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #0d0d0d;
        }

        .auth-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6rem 1rem 3rem;
        }

        .auth-card {
            background: #1a1a1a;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 440px;
        }

        .auth-card .nav-tabs {
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1.75rem;
        }

        .auth-card .nav-tabs .nav-link {
            color: rgba(255,255,255,0.5);
            border: none;
            border-bottom: 2px solid transparent;
            background: none;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.5rem 0;
            margin-right: 1.5rem;
        }

        .auth-card .nav-tabs .nav-link:hover {
            color: rgba(255,255,255,0.85);
        }

        .auth-card .nav-tabs .nav-link.active {
            color: #fff;
            border-bottom-color: #fff;
            background: none;
        }

        .form-control-dark {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            color: #fff;
            border-radius: 8px;
        }

        .form-control-dark:focus {
            background: rgba(255,255,255,0.09);
            border-color: rgba(255,255,255,0.35);
            color: #fff;
            box-shadow: none;
        }

        .form-control-dark::placeholder {
            color: rgba(255,255,255,0.3);
        }

        .form-label {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.7);
            margin-bottom: 0.4rem;
        }

        .btn-auth-primary {
            background: #fff;
            color: #000;
            border: none;
            border-radius: 50px;
            font-weight: 700;
            padding: 0.65rem;
            width: 100%;
            transition: background 0.2s;
        }

        .btn-auth-primary:hover {
            background: #e0e0e0;
            color: #000;
        }

        .auth-divider {
            color: rgba(255,255,255,0.25);
            font-size: 0.78rem;
            text-align: center;
            position: relative;
            margin: 1.25rem 0;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 42%;
            height: 1px;
            background: rgba(255,255,255,0.1);
        }

        .auth-divider::before { left: 0; }
        .auth-divider::after  { right: 0; }

        .form-check-input {
            background-color: rgba(255,255,255,0.06);
            border-color: rgba(255,255,255,0.2);
        }

        .form-check-input:checked {
            background-color: #fff;
            border-color: #fff;
        }

        .form-check-label {
            font-size: 0.83rem;
            color: rgba(255,255,255,0.6);
        }

        .invalid-feedback {
            font-size: 0.78rem;
        }
    </style>
</head>
<body>

{{-- ─────────────── Navbar ─────────────── --}}
<nav class="navbar navbar-expand-lg navbar-dark position-sticky top-0 z-3 px-4 px-lg-5 py-2" style="background: rgba(0,0,0,0.92); backdrop-filter: blur(8px); border-bottom: 1px solid rgba(255,255,255,0.06);">
    <a class="navbar-brand" href="{{ route('home') }}">
        <img src="{{ asset('images/movie_logo.png') }}" alt="RoyalReel" style="height:38px; width:auto;">
    </a>
</nav>

{{-- ─────────────── Auth Card ─────────────── --}}
<div class="auth-wrapper">
    <div class="auth-card">

        {{-- Tabs --}}
        <ul class="nav nav-tabs" id="authTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $errors->has('email') && old('active_tab','login') === 'login' || (!$errors->has('name') && !session('active_tab')) ? 'active' : '' }}"
                        id="login-tab" data-bs-toggle="tab" data-bs-target="#login-pane"
                        type="button" role="tab">
                    Sign In
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $errors->has('name') ? 'active' : '' }}"
                        id="register-tab" data-bs-toggle="tab" data-bs-target="#register-pane"
                        type="button" role="tab">
                    Create Account
                </button>
            </li>
        </ul>

        <div class="tab-content" id="authTabContent">

            {{-- ── Login Tab ── --}}
            <div class="tab-pane fade {{ !$errors->has('name') ? 'show active' : '' }}"
                 id="login-pane" role="tabpanel">

                @if($errors->has('email') && !$errors->has('name'))
                    <div class="alert alert-danger py-2 mb-3" style="font-size:0.83rem; background:rgba(220,53,69,0.15); border-color:rgba(220,53,69,0.3); color:#ff6b6b;">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $errors->first('email') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" novalidate>
                    @csrf
                    <input type="hidden" name="intended" value="{{ $intended ?? '/' }}">

                    <div class="mb-3">
                        <label class="form-label" for="login_email">Email address</label>
                        <input type="email" id="login_email" name="email"
                               class="form-control form-control-dark @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="you@example.com"
                               autocomplete="email"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="login_password">Password</label>
                        <input type="password" id="login_password" name="password"
                               class="form-control form-control-dark"
                               placeholder="••••••••"
                               autocomplete="current-password"
                               required>
                    </div>

                    <div class="mb-4 d-flex align-items-center justify-content-between">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-auth-primary">Sign In</button>
                </form>

                <div class="auth-divider mt-4">or</div>

                <p class="text-center mb-0" style="font-size:0.83rem; color:rgba(255,255,255,0.45);">
                    New to RoyalReel?
                    <a href="#" class="text-white" onclick="document.getElementById('register-tab').click(); return false;">
                        Create an account
                    </a>
                </p>
            </div>

            {{-- ── Register Tab ── --}}
            <div class="tab-pane fade {{ $errors->has('name') ? 'show active' : '' }}"
                 id="register-pane" role="tabpanel">

                <form method="POST" action="{{ route('register') }}" novalidate>
                    @csrf
                    <input type="hidden" name="intended" value="{{ $intended ?? '/' }}">

                    <div class="mb-3">
                        <label class="form-label" for="reg_name">Full name</label>
                        <input type="text" id="reg_name" name="name"
                               class="form-control form-control-dark @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="John Doe"
                               autocomplete="name"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="reg_email">Email address</label>
                        <input type="email" id="reg_email" name="email"
                               class="form-control form-control-dark @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="you@example.com"
                               autocomplete="email"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="reg_password">Password</label>
                        <input type="password" id="reg_password" name="password"
                               class="form-control form-control-dark @error('password') is-invalid @enderror"
                               placeholder="Min. 8 characters"
                               autocomplete="new-password"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="reg_password_confirmation">Confirm password</label>
                        <input type="password" id="reg_password_confirmation" name="password_confirmation"
                               class="form-control form-control-dark"
                               placeholder="Repeat password"
                               autocomplete="new-password"
                               required>
                    </div>

                    <button type="submit" class="btn btn-auth-primary">Create Account</button>
                </form>

                <div class="auth-divider mt-4">or</div>

                <p class="text-center mb-0" style="font-size:0.83rem; color:rgba(255,255,255,0.45);">
                    Already have an account?
                    <a href="#" class="text-white" onclick="document.getElementById('login-tab').click(); return false;">
                        Sign in
                    </a>
                </p>
            </div>

        </div>{{-- /tab-content --}}
    </div>{{-- /auth-card --}}
</div>{{-- /auth-wrapper --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
