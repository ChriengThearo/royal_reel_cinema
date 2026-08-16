@extends('admin.layout')
@section('title', 'Edit Genre')
@section('page-title', 'Edit Genre')

@section('content')
<div style="max-width:480px;">
    <a href="{{ route('admin.genres.index') }}" class="btn btn-admin-ghost btn mb-3">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
    <div class="admin-card p-4">
        <form method="POST" action="{{ route('admin.genres.update', $genre) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label-sm">Name <span style="color:#dc3545">*</span></label>
                <input type="text" name="name" value="{{ old('name', $genre->name) }}"
                       class="form-control form-control-dark @error('name') is-invalid @enderror"
                       placeholder="e.g. Action" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-admin-primary">Save Changes</button>
        </form>
    </div>
</div>
@endsection
