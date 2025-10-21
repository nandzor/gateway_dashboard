<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'name' => 'required|string|max:50|unique:services,name',
            'type' => 'required|integer|in:1,2',
            'is_active' => 'required|integer|in:0,1',
            'is_alert_zero' => 'required|integer|in:0,1',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'service name',
            'type' => 'service type',
            'is_active' => 'active status',
            'is_alert_zero' => 'alert zero status',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The service name is required.',
            'name.unique' => 'A service with this name already exists.',
            'name.max' => 'The service name may not be greater than 50 characters.',
            'type.required' => 'The service type is required.',
            'type.in' => 'The selected service type is invalid.',
            'is_active.required' => 'The active status is required.',
            'is_active.in' => 'The active status must be 0 or 1.',
            'is_alert_zero.required' => 'The alert zero status is required.',
            'is_alert_zero.in' => 'The alert zero status must be 0 or 1.',
        ];
    }
}
