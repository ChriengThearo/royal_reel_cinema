{{-- Shared movie form fields. $movie is null on create, Model on edit. --}}

@php $old = fn($field, $default = '') => old($field, $movie?->$field ?? $default); @endphp

<div class="admin-card p-4 mb-3">
    <div class="row g-3">

        <div class="col-12">
            <label class="form-label-sm">Title <span style="color:#dc3545">*</span></label>
            <input type="text" name="title" value="{{ $old('title') }}"
                   class="form-control form-control-dark @error('title') is-invalid @enderror"
                   placeholder="Movie title" required>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
            <label class="form-label-sm">Slug <span style="color:rgba(255,255,255,0.3)">(auto-generated if blank)</span></label>
            <input type="text" name="slug" value="{{ $old('slug') }}"
                   class="form-control form-control-dark @error('slug') is-invalid @enderror"
                   placeholder="my-movie-slug">
            @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
            <label class="form-label-sm">Description</label>
            <textarea name="description" rows="4"
                      class="form-control form-control-dark @error('description') is-invalid @enderror"
                      placeholder="Short synopsis…">{{ $old('description') }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-sm-4">
            <label class="form-label-sm">Release Date</label>
            <input type="date" name="release_date" value="{{ $old('release_date') }}"
                   class="form-control form-control-dark @error('release_date') is-invalid @enderror">
            @error('release_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-sm-4">
            <label class="form-label-sm">Duration (minutes)</label>
            <input type="number" name="duration_minutes" value="{{ $old('duration_minutes') }}"
                   class="form-control form-control-dark @error('duration_minutes') is-invalid @enderror"
                   min="1" placeholder="90">
            @error('duration_minutes')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-sm-4">
            <label class="form-label-sm">Age Rating</label>
            <select name="age_rating" class="form-select form-select-dark @error('age_rating') is-invalid @enderror">
                <option value="">— Select —</option>
                @foreach(['G','PG','PG-13','R'] as $r)
                    <option value="{{ $r }}" {{ $old('age_rating') === $r ? 'selected' : '' }}>{{ $r }}</option>
                @endforeach
            </select>
            @error('age_rating')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-sm-6">
            <label class="form-label-sm">Status <span style="color:#dc3545">*</span></label>
            <select name="status" class="form-select form-select-dark @error('status') is-invalid @enderror" required>
                @foreach(['draft','published','archived'] as $s)
                    <option value="{{ $s }}" {{ $old('status', 'draft') === $s ? 'selected' : '' }}>
                        {{ ucfirst($s) }}
                    </option>
                @endforeach
            </select>
            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-sm-6">
            <label class="form-label-sm">Access Type <span style="color:#dc3545">*</span></label>
            <select name="access_type" id="access_type"
                    class="form-select form-select-dark @error('access_type') is-invalid @enderror" required>
                <option value="free"         {{ $old('access_type', 'free') === 'free'         ? 'selected' : '' }}>Free</option>
                <option value="subscription" {{ $old('access_type', 'free') === 'subscription' ? 'selected' : '' }}>Subscription</option>
            </select>
            @error('access_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12" id="plan_row" style="{{ $old('access_type', 'free') === 'subscription' ? '' : 'display:none' }}">
            <label class="form-label-sm">Required Plan <span style="color:rgba(255,255,255,0.3)">(blank = any active plan)</span></label>
            <select name="required_plan_id" class="form-select form-select-dark @error('required_plan_id') is-invalid @enderror">
                <option value="">Any active plan</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}"
                        {{ (string)$old('required_plan_id') === (string)$plan->id ? 'selected' : '' }}>
                        {{ $plan->name }} ({{ $plan->currency }} {{ number_format($plan->price, 2) }}/{{ $plan->billing_cycle }})
                    </option>
                @endforeach
            </select>
            @error('required_plan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
            <label class="form-label-sm">Genres</label>
            <div class="d-flex flex-wrap gap-2 mt-1">
                @foreach($genres as $genre)
                @php
                    $checked = in_array($genre->id, old('genre_ids', $movie?->genres->pluck('id')->toArray() ?? []));
                @endphp
                <label style="cursor:pointer; background:rgba(255,255,255,0.06); border:1px solid {{ $checked ? '#fff' : 'rgba(255,255,255,0.12)' }}; border-radius:50px; padding:0.3rem 0.85rem; font-size:0.82rem; user-select:none;"
                       class="genre-toggle {{ $checked ? 'genre-selected' : '' }}">
                    <input type="checkbox" name="genre_ids[]" value="{{ $genre->id }}"
                           {{ $checked ? 'checked' : '' }} style="display:none;">
                    {{ $genre->name }}
                </label>
                @endforeach
            </div>
            @error('genre_ids')<div style="color:#dc3545; font-size:0.78rem; margin-top:0.25rem;">{{ $message }}</div>@enderror
        </div>

    </div>
</div>

<div class="admin-card p-4 mb-3">
    <div style="font-size:0.88rem; font-weight:600; margin-bottom:1rem; color:rgba(255,255,255,0.7);">
        <i class="bi bi-image me-1"></i> Poster Image
    </div>
    <div class="row g-3">
        <div class="col-12">
            <label class="form-label-sm">Upload Poster <span style="color:rgba(255,255,255,0.3)">(JPG/PNG/WebP, max 5 MB)</span></label>
            <input type="file" name="poster" accept="image/jpeg,image/png,image/webp"
                   class="form-control form-control-dark @error('poster') is-invalid @enderror">
            @error('poster')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="admin-card p-4 mb-3">
    <div style="font-size:0.88rem; font-weight:600; margin-bottom:1rem; color:rgba(255,255,255,0.7);">
        <i class="bi bi-camera-video me-1"></i> Video File
    </div>
    <div class="row g-3">
        <div class="col-sm-4">
            <label class="form-label-sm">Quality</label>
            <select name="video_quality" class="form-select form-select-dark">
                @foreach(['480p','720p','1080p','4k'] as $q)
                    <option value="{{ $q }}" {{ old('video_quality', '1080p') === $q ? 'selected' : '' }}>{{ $q }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-8">
            <label class="form-label-sm">Upload Video <span style="color:rgba(255,255,255,0.3)">(MP4/WebM, max 10 GB)</span></label>
            <input type="file" name="video" accept="video/mp4,video/webm"
                   class="form-control form-control-dark @error('video') is-invalid @enderror">
            @error('video')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>
