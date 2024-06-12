<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenreFormRequest extends FormRequest
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
        $rules = [
            'name' => 'required|min:3|max:255|unique:genres,name,'. ($this->genre? $this->code : null).',code'
        ];
        if (empty($this->genre)) {
            // This will merge 2 arrays:
            // (adds the "code" rule to the $rules array)
            $rules = array_merge($rules, [
                'code' => 'sometimes|unique:genres,code'
            ]);
        }
        return $rules;
    }
}
