<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGenreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $genreId = $this->route('genre')?->id;

        return [
            'name' => ['required', 'string', 'max:100', "unique:genres,name,{$genreId}"],
        ];
    }
}
