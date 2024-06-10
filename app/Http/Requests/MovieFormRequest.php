<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovieFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:2|max:255',
            'year' => 'required|intiger|digits:4|min:1888',
            'genre_code' => 'required|exists:genres,code',
            'poster_filename' => 'sometimes|image|max:4096',
            'synopsis' => 'required|string',
            'trailer_url' => 'nullable|url:http,https'
        ];
    }
}
