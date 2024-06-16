<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerFormRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email',
            'nif' => 'nullable|string|digits:9',
            'payment_type' => 'required|in:MBWAY,VISA,PAYPAL',
            'photo_file' => 'sometimes|image|max:4096',
        ];
        $rules['payment_ref'] = match ($this->input('payment_type')) {
            'VISA' => 'required|digits:16',
            'MBWAY' => 'required|digits:9',
            'PAYPAL' => 'required|email',
        };
        return $rules;
    }
}
