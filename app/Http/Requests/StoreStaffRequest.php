<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'staff_first' => ['required', 'string', 'max:100'],
            'staff_middle' => ['nullable', 'string', 'max:100'],
            'staff_last' => ['required', 'string', 'max:100'],
            'contact_number' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
        ];

        if ($this->filled('email') || $this->filled('password') || $this->filled('role')) {
            $rules['email'] = ['required', 'email', 'max:255', 'unique:users,email'];
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
            $rules['role'] = ['required', 'in:supervisor,mechanic'];
        }

        return $rules;
    }
}
