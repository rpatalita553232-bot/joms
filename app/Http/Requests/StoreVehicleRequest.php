<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cust_id' => ['required', 'integer', 'exists:customers,cust_id'],
            'plate_number' => ['required', 'string', 'max:20', Rule::unique('vehicles', 'plate_number')],
            'make' => ['required', 'string', 'max:100'],
            'engine_model' => ['required', 'string', 'max:100'],
        ];
    }
}
