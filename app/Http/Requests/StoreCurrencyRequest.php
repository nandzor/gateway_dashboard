<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCurrencyRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'is_active' => 'required|integer|in:0,1',
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
            'name' => 'currency name',
            'symbol' => 'currency symbol',
            'is_active' => 'status',
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
            'name.required' => 'Currency name is required.',
            'name.max' => 'Currency name must not exceed 255 characters.',
            'symbol.required' => 'Currency symbol is required.',
            'symbol.max' => 'Currency symbol must not exceed 10 characters.',
            'is_active.required' => 'Status is required.',
            'is_active.in' => 'Status must be either active or inactive.',
        ];
    }
}
