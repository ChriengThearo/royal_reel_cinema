<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => ['nullable', 'string', 'max:255', 'unique:movies,slug'],
            'description'      => ['nullable', 'string'],
            'release_date'     => ['nullable', 'date'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'age_rating'       => ['nullable', 'in:G,PG,PG-13,R'],
            'status'           => ['required', 'in:draft,published,archived'],
            'access_type'      => ['required', 'in:free,subscription'],
            'required_plan_id' => ['nullable', 'exists:plans,id', 'required_if:access_type,subscription'],
            'genre_ids'        => ['nullable', 'array'],
            'genre_ids.*'      => ['exists:genres,id'],
            'poster'           => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video'            => ['nullable', 'file', 'mimes:mp4,webm', 'max:10485760'],
            'video_quality'    => ['nullable', 'in:480p,720p,1080p,4k'],
        ];
    }
}
