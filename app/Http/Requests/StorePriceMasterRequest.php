<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePriceMasterRequest extends FormRequest
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
            'module_id' => 'required|integer|exists:services,id',
            'price_default' => 'required|numeric|min:0',
            'is_active' => 'required|integer|in:0,1',
            'note' => 'nullable|string|max:50',
            'currency_id' => 'required|integer|exists:currencies,id',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'module_id' => 'service',
            'price_default' => 'price',
            'is_active' => 'status',
            'currency_id' => 'currency',
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
            'module_id.required' => 'Please select a service.',
            'module_id.exists' => 'The selected service does not exist.',
            'price_default.required' => 'Price is required.',
            'price_default.numeric' => 'Price must be a number.',
            'price_default.min' => 'Price must be at least 0.',
            'currency_id.required' => 'Please select a currency.',
            'currency_id.exists' => 'The selected currency does not exist.',
        ];
    }
}
