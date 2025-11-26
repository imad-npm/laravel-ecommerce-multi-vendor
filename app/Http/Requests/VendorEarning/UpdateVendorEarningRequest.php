<?php

namespace App\Http\Requests\VendorEarning;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVendorEarningRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'payout_id' => ['nullable', 'exists:payouts,id'],
            'is_paid' => ['required', 'boolean'],
        ];
    }
}
