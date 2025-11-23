<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DeleteUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Or implement specific authorization logic
    }

    public function rules(): array
    {
        if ($this->user()->google_id !== null) {
            return [];
        }

        return [
            'password' => ['required', 'current_password'],
        ];
    }
}
