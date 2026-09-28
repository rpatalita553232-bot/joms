<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectJobOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isSupervisor() ?? false;
    }

    public function rules(): array
    {
        return [
            'remarks' => ['required', 'string', 'max:1000'],
        ];
    }
}
