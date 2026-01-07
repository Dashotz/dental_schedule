<?php

namespace App\Http\Requests\Appointment;

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
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['nullable', 'exists:users,id'],
            'appointment_date' => ['required', 'date', 'after:now'],
            'appointment_time' => ['required', 'string', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'duration' => ['nullable', 'integer', 'min:15'],
            'type' => ['required', 'in:consultation,cleaning,procedure,follow_up,emergency,other'],
            'status' => ['nullable', 'in:scheduled,confirmed,in_progress,completed,cancelled,no_show'],
            'reason' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'appointment_time.regex' => 'Invalid time format. Please use HH:MM format.',
            'appointment_date.after' => 'Appointment date must be in the future.',
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
