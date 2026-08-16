@extends('admin.layout')
@section('title', 'Genres')
@section('page-title', 'Genres')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <span style="color:rgba(255,255,255,0.4); font-size:0.85rem;">{{ $genres->total() }} genres</span>
    <a href="{{ route('admin.genres.create') }}" class="btn btn-admin-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Genre
    </a>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead><tr><th>Name</th><th></th></tr></thead>
            <tbody>
                @forelse($genres as $genre)
                <tr>
                    <td style="font-weight:600;">{{ $genre->name }}</td>
                    <td>
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.genres.edit', $genre) }}" class="btn btn-admin-ghost btn">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.genres.destroy', $genre) }}"
                                  onsubmit="return confirm('Delete genre \'{{ addslashes($genre->name) }}\'?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-admin-danger btn"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="2" style="text-align:center; color:rgba(255,255,255,0.3); padding:2rem;">No genres yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $genres->links() }}</div>
@endsection
