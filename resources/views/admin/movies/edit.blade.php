@extends('admin.layout')
@section('title', 'Edit Movie')
@section('page-title', 'Edit: ' . $movie->title)

@section('content')
<div style="max-width:780px;">
    <a href="{{ route('admin.movies.index') }}" class="btn btn-admin-ghost btn mb-3">
        <i class="bi bi-arrow-left me-1"></i> Back
    </a>

    {{-- ── Main edit form ───────────────────────────────────────────────────── --}}
    {{-- NOTE: The delete-video forms are intentionally placed OUTSIDE this form --}}
    {{-- because nested <form> tags are invalid HTML and prevent submission.     --}}
    <form id="movie-edit-form"
          method="POST"
          action="{{ route('admin.movies.update', $movie) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('admin.movies._form', ['movie' => $movie])

        {{-- Existing poster (display only — no nested form) --}}
        @if($movie->poster_url)
        <div class="admin-card p-3 mb-3">
            <div class="form-label-sm mb-2">Current Poster</div>
            <img src="{{ $movie->poster_url }}" alt="Poster"
                 style="height:120px; border-radius:8px; object-fit:cover;">
        </div>
        @endif

        {{-- Existing video qualities (display only — delete buttons are below, outside this form) --}}
        @if($movie->videoAssets->isNotEmpty())
        <div class="admin-card p-3 mb-3" id="video-qualities-display">
            <div class="form-label-sm mb-2">Uploaded Video Qualities</div>
            <div class="d-flex flex-wrap gap-2">
                @foreach($movie->videoAssets as $asset)
                <div class="d-flex align-items-center gap-2"
                     style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:8px; padding:0.4rem 0.75rem;">
                    <span style="font-size:0.83rem; font-weight:600;">{{ $asset->quality }}</span>
                    @if($asset->size_mb)
                        <span style="font-size:0.72rem; color:rgba(255,255,255,0.4);">{{ $asset->size_mb }} MB</span>
                    @endif
                    {{-- Delete button triggers the standalone form below (outside the main form) --}}
                    <button type="button"
                            onclick="document.getElementById('delete-video-{{ $asset->id }}').submit()"
                            class="btn p-0 border-0"
                            style="color:rgba(220,53,69,0.7); background:none; line-height:1;"
                            title="Remove {{ $asset->quality }}">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="mt-4">
            <button type="submit" class="btn btn-admin-primary">Save Changes</button>
        </div>

    </form>{{-- end #movie-edit-form --}}

    {{-- ── Delete-video forms — OUTSIDE the main form to avoid nested form bug ── --}}
    {{-- HTML does not support nested <form> tags. Placing these outside ensures  --}}
    {{-- the main form's submit button always works.                              --}}
    @if($movie->videoAssets->isNotEmpty())
        @foreach($movie->videoAssets as $asset)
        <form id="delete-video-{{ $asset->id }}"
              method="POST"
              action="{{ route('admin.movies.videos.destroy', [$movie, $asset]) }}"
              onsubmit="return confirm('Remove {{ $asset->quality }} quality?')"
              style="display:none;">
            @csrf
            @method('DELETE')
        </form>
        @endforeach
    @endif

</div>
@endsection

@section('scripts')
@include('admin.movies._form_scripts')
@endsection
