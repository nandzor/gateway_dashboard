<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
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
        $serviceId = $this->route('service')->id;

        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('services', 'name')->ignore($serviceId)
            ],
            'is_active' => 'required|integer|in:0,1',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'service name',
            'is_active' => 'active status',
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
            'is_active.required' => 'The active status is required.',
            'is_active.in' => 'The active status must be 0 or 1.',
        ];
    }
}
