@extends('admin.layout')
@section('title', 'Add Genre')
@section('page-title', 'Add Genre')

@section('content')
<div style="max-width:480px;">
    <a href="{{ route('admin.genres.index') }}" class="btn btn-admin-ghost btn mb-3">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>
    <div class="admin-card p-4">
        <form method="POST" action="{{ route('admin.genres.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label-sm">Name <span style="color:#dc3545">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="form-control form-control-dark @error('name') is-invalid @enderror"
                       placeholder="e.g. Action" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-admin-primary">Create Genre</button>
        </form>
    </div>
</div>
@endsection
