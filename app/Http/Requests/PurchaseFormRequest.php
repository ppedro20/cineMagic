<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseFormRequest extends FormRequest
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
        $rules = [
            'customer_id' => 'required|exists:customers,id',
            'date' => 'required|date|date_formart:Y-m-d|date_equals:today',
            'total_price' => 'required|number|gt:0',
            'customer_name' => 'required|string|min:3|max:255',
            'customer_email' => 'required|email'
            'nif' => 'nullable|string|digits:9',
            'payment_type' => 'required|in:MBWAY, VISA, PAYPAL',
            'receipt_pdf_file' => '',
        ];
        $rules['payment_ref'] = match ($this->input('payment_type')) {
            'VISA' => 'required|max:20|regex:/^\d{16}-\d{3}$/',
            'MBWAY' => 'required|digits:9',
            'PAYPAL' => 'required|email',
        };
    }
}
