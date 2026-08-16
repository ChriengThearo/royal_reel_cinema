@extends('admin.layout')
@section('title', $user->name)
@section('page-title', $user->name)

@section('content')
<div style="max-width:680px;">
    <a href="{{ route('admin.users.index') }}" class="btn btn-admin-ghost btn mb-3">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>

    {{-- Profile card --}}
    <div class="admin-card p-4 mb-3">
        <div class="row g-3">
            <div class="col-sm-6">
                <div class="form-label-sm">Name</div>
                <div style="font-weight:600;">{{ $user->name }}</div>
            </div>
            <div class="col-sm-6">
                <div class="form-label-sm">Email</div>
                <div style="color:rgba(255,255,255,0.65);">{{ $user->email }}</div>
            </div>
            <div class="col-sm-6">
                <div class="form-label-sm">Roles</div>
                <div class="d-flex flex-wrap gap-1 mt-1">
                    @forelse($user->roles as $role)
                        <span class="badge badge-role-{{ $role->name }}">{{ $role->name }}</span>
                    @empty
                        <span style="color:rgba(255,255,255,0.3);">No roles</span>
                    @endforelse
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-label-sm">Joined</div>
                <div style="color:rgba(255,255,255,0.65);">{{ $user->created_at?->format('d M Y, H:i') }}</div>
            </div>
        </div>
    </div>

    {{-- Subscriptions --}}
    <div class="admin-card mb-3">
        <div class="admin-card-header">Subscriptions</div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead><tr><th>Plan</th><th>Status</th><th>Start</th><th>End</th></tr></thead>
                <tbody>
                    @forelse($user->subscriptions as $sub)
                    <tr>
                        <td>{{ $sub->plan?->name ?? '—' }}</td>
                        <td><span class="badge badge-sub-{{ $sub->status }}">{{ $sub->status }}</span></td>
                        <td style="font-size:0.8rem; color:rgba(255,255,255,0.5);">{{ $sub->start_date?->format('d M Y') }}</td>
                        <td style="font-size:0.8rem; color:rgba(255,255,255,0.5);">{{ $sub->end_date?->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="color:rgba(255,255,255,0.3); text-align:center; padding:1.5rem;">No subscriptions.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Ratings --}}
    <div class="admin-card">
        <div class="admin-card-header">Ratings ({{ $user->ratings->count() }})</div>
        @if($user->ratings->isNotEmpty())
        <div class="table-responsive">
            <table class="admin-table">
                <thead><tr><th>Movie ID</th><th>Score</th><th>Date</th></tr></thead>
                <tbody>
                    @foreach($user->ratings as $rating)
                    <tr>
                        <td style="color:rgba(255,255,255,0.5);">#{{ $rating->movie_id }}</td>
                        <td><span class="badge badge-role-admin">{{ $rating->score }}/10</span></td>
                        <td style="font-size:0.8rem; color:rgba(255,255,255,0.4);">{{ $rating->created_at?->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div style="padding:1.5rem; color:rgba(255,255,255,0.3); text-align:center; font-size:0.85rem;">No ratings.</div>
        @endif
    </div>
</div>
@endsection
