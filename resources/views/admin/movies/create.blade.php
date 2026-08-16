@extends('admin.layout')
@section('title', 'Add Movie')
@section('page-title', 'Add Movie')

@section('content')
<div style="max-width:780px;">
    <a href="{{ route('admin.movies.index') }}" class="btn btn-admin-ghost btn mb-3">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>

    <form method="POST" action="{{ route('admin.movies.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.movies._form', ['movie' => null])
        <div class="mt-4">
            <button type="submit" class="btn btn-admin-primary">Create Movie</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
@include('admin.movies._form_scripts')
@endsection
