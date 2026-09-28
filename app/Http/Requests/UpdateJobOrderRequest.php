<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $jobOrder = $this->route('jobOrder');

        return $this->user()?->isMechanic()
            && $jobOrder?->created_by === $this->user()->id
            && $jobOrder?->status === 'rejected';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'problem_description' => ['required', 'string', 'min:5'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            'service_id' => ['nullable', 'integer', 'exists:services,service_id'],
            'expected_cmpl_date' => [
                'required',
                'date',
                Rule::date()->afterOrEqual($this->route('jobOrder')?->date_issued),
            ],
        ];
    }
}
