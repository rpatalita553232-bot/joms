<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isMechanic() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cust_id' => ['required', 'integer', 'exists:customers,cust_id'],
            'vehicle_id' => [
                'required',
                'integer',
                Rule::exists('vehicles', 'vehicle_id')->where('cust_id', $this->input('cust_id')),
            ],
            'service_id' => ['required', 'integer', 'exists:services,service_id'],
            'problem_description' => ['required', 'string', 'min:5'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            'expected_cmpl_date' => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }
}
