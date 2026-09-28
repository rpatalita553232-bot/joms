<?php

namespace App\Http\Requests;

use App\Models\Staff;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UpdateStaffRequest extends FormRequest
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
            $staff = $this->route('staff');
            $rules['email'] = [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($staff instanceof Staff ? $staff->user_id : null),
            ];
            $rules['password'] = [$staff instanceof Staff && $staff->user_id ? 'nullable' : 'required', 'confirmed', Password::defaults()];
            $rules['role'] = ['required', 'in:supervisor,mechanic'];
        }

        return $rules;
    }
}
