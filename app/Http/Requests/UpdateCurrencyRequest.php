<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCurrencyRequest extends FormRequest
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
        $currencyId = $this->route('currency')->id;

        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|size:3|unique:currencies,code,' . $currencyId,
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
            'code' => 'currency code',
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
            'code.required' => 'Currency code is required.',
            'code.size' => 'Currency code must be exactly 3 characters.',
            'code.unique' => 'This currency code already exists.',
            'symbol.required' => 'Currency symbol is required.',
            'symbol.max' => 'Currency symbol must not exceed 10 characters.',
            'is_active.required' => 'Status is required.',
            'is_active.in' => 'Status must be either active or inactive.',
        ];
    }
}
