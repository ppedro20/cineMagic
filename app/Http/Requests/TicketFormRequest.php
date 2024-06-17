<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'seat_id' => 'required|exists:seats,id',
            'screening_id' => 'required|exists:screenings,id',
            'purchase_id' => 'required|exists:purchases,id',
            'price' => 'required|numeric|min:0',
            'qrcode_url' => 'nullable|url',
            'status' => 'required|in:valid,invalid',
        ];
    }
}
