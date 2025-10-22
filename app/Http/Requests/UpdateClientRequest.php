<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only admin can update clients
        return $this->user() && $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $clientId = $this->route('client')?->id;

        return [
            'client_name' => ['required', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:150'],
            'contact' => ['nullable', 'string', 'max:20'],
            'type' => ['required', 'integer', 'in:1,2,3,4'],
            'ak' => ['nullable', 'string', 'max:100'],
            'sk' => ['nullable', 'string', 'max:100'],
            'avkey_iv' => ['nullable', 'string', 'max:100'],
            'avkey_pass' => ['nullable', 'string', 'max:100'],
            'service_assignments' => ['nullable', 'array'],
            'service_assignments.*' => ['integer', 'exists:services,id'],
            'is_active' => ['nullable', 'integer', 'in:0,1'],
            'service_allow' => ['nullable', 'string'],
            'white_list' => ['nullable', 'array'],
            'white_list.*' => ['string', 'regex:/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/'],
            'module_40' => ['nullable', 'string'],
            'is_staging' => ['nullable', 'integer', 'in:0,1'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'client_name' => 'client name',
            'address' => 'address',
            'contact' => 'contact',
            'type' => 'client type',
            'ak' => 'access key',
            'sk' => 'secret key',
            'avkey_iv' => 'avkey iv',
            'avkey_pass' => 'avkey pass',
            'service_assignments' => 'service assignments',
            'is_active' => 'status',
            'service_allow' => 'service allow',
            'white_list' => 'white list',
            'module_40' => 'module 40',
            'is_staging' => 'staging status',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'client_name.required' => 'The client name field is required.',
            'client_name.max' => 'The client name may not be greater than 100 characters.',
            'type.required' => 'The client type field is required.',
            'type.in' => 'The selected client type is invalid.',
            'contact.max' => 'The contact may not be greater than 20 characters.',
            'address.max' => 'The address may not be greater than 150 characters.',
            'ak.max' => 'The access key may not be greater than 100 characters.',
            'sk.max' => 'The secret key may not be greater than 100 characters.',
        ];
    }
}
