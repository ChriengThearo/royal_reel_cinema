@extends('admin.layout')
@section('title', 'Movies')
@section('page-title', 'Movies')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <span style="color:rgba(255,255,255,0.4); font-size:0.85rem;">{{ $movies->total() }} movies total</span>
    <a href="{{ route('admin.movies.create') }}" class="btn btn-admin-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Movie
    </a>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Access</th>
                    <th>Genres</th>
                    <th>Created</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($movies as $movie)
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $movie->title }}</div>
                        <div style="font-size:0.75rem; color:rgba(255,255,255,0.35);">{{ $movie->slug }}</div>
                    </td>
                    <td>
                        <span class="badge badge-status-{{ $movie->status }}">{{ $movie->status }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $movie->access_type === 'free' ? 'badge-role-user' : 'badge-role-admin' }}">
                            {{ $movie->access_type }}
                        </span>
                    </td>
                    <td style="color:rgba(255,255,255,0.5); font-size:0.8rem;">
                        {{ $movie->genres->pluck('name')->join(', ') ?: '—' }}
                    </td>
                    <td style="color:rgba(255,255,255,0.4); font-size:0.8rem;">
                        {{ $movie->created_at?->format('d M Y') }}
                    </td>
                    <td>
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.movies.edit', $movie) }}" class="btn btn-admin-ghost btn">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.movies.destroy', $movie) }}"
                                  onsubmit="return confirm('Delete \'{{ addslashes($movie->title) }}\'?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-admin-danger btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; color:rgba(255,255,255,0.3); padding:2.5rem;">
                        No movies yet. <a href="{{ route('admin.movies.create') }}" style="color:#fff;">Add one.</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $movies->links() }}</div>
@endsection
