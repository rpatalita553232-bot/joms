<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job_desc' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'decimal:0,2', 'gt:0'],
        ];
    }
}
