<?php

namespace App\Http\Requests\Availability;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\Tenant\TenantContext;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization handled by policy
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'in:weekly,specific_date,date_range'],
            'day_of_week' => ['nullable', 'integer', 'min:0', 'max:6'],
            'specific_date' => ['nullable', 'date', 'required_if:type,specific_date'],
            'start_date' => ['nullable', 'date', 'required_if:type,date_range'],
            'end_date' => ['nullable', 'date', 'required_if:type,date_range', 'after_or_equal:start_date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'slot_duration' => ['required', 'integer', 'in:15,30,45,60'],
            'is_available' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure subdomain_id cannot be manipulated
        $this->merge([
            'subdomain_id' => TenantContext::getSubdomainId(),
        ]);
    }
}
