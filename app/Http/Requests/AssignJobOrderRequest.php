<?php

namespace App\Http\Requests;

use App\Models\Staff;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class AssignJobOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isSupervisor() ?? false;
    }

    public function rules(): array
    {
        return [
            'staff_id' => ['required', 'exists:staff,staff_id'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            if ($validator->errors()->has('staff_id')) {
                return;
            }

            $isMechanic = Staff::query()
                ->where('staff_id', $this->integer('staff_id'))
                ->whereHas('user', fn ($query) => $query->where('role', 'mechanic'))
                ->exists();

            if (! $isMechanic) {
                $validator->errors()->add('staff_id', 'Select a staff member with a mechanic account.');
            }
        }];
    }
}
