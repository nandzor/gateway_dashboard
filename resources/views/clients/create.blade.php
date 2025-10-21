@extends('layouts.app')

@section('title', 'Create Client')
@section('page-title', 'Create New Client')

@section('content')
  <div class="max-w-4xl">
    <x-card title="Client Information">
      <div class="mb-6">
        <p class="text-sm text-gray-500">Fill in the details to create a new client account</p>
      </div>

      <form method="POST" action="{{ route('clients.store') }}" class="space-y-6">
        @csrf

        <!-- Basic Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <x-input name="client_name" label="Client Name" placeholder="Enter client name" required
            hint="Maximum 100 characters" />

          <x-select name="type" label="Client Type" :options="$typeOptions" placeholder="Select client type" required
            hint="Choose the appropriate client type" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <x-input name="address" label="Address" placeholder="Enter client address"
            hint="Maximum 150 characters" />

          <x-input name="contact" label="Contact" placeholder="Enter contact information"
            hint="Maximum 20 characters" />
        </div>

        <!-- API Credentials -->
        <div class="border-t pt-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">API Credentials</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-input name="ak" label="Access Key (AK)" placeholder="Enter access key" 
              hint="Maximum 100 characters" />

            <x-input name="sk" label="Secret Key (SK)" placeholder="Enter secret key" 
              hint="Maximum 100 characters" />
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            <x-input name="avkey_iv" label="AVKey IV" placeholder="Enter avkey iv" 
              hint="Maximum 100 characters" />

            <x-input name="avkey_pass" label="AVKey Pass" placeholder="Enter avkey pass" 
              hint="Maximum 100 characters" />
          </div>
        </div>

        <!-- Service Configuration -->
        <div class="border-t pt-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Service Configuration</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-select name="service_module" label="Service Module" :options="$serviceModuleOptions" placeholder="Select service module"
              hint="Choose the service module for this client" />

            <x-textarea name="service_allow" label="Service Allow" placeholder="Enter allowed services"
              hint="List of services this client can access" />
          </div>
        </div>

        <!-- Security Settings -->
        <div class="border-t pt-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Security Settings</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-textarea name="white_list" label="White List" placeholder="Enter whitelist IPs"
              hint="List of allowed IP addresses" />

            <x-textarea name="module_40" label="Module 40" placeholder="Enter module 40 configuration"
              hint="Module 40 specific configuration" />
          </div>
        </div>

        <!-- Status Settings -->
        <div class="border-t pt-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Status Settings</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-select name="is_active" label="Status" :options="[1 => 'Active', 0 => 'Inactive']" :selected="1" placeholder="Select status" required
              hint="Set client active status" />

            <x-select name="is_staging" label="Environment" :options="[0 => 'Production', 1 => 'Staging']" :selected="0" placeholder="Select environment" required
              hint="Set client environment" />
          </div>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
          <x-button variant="secondary" :href="route('clients.index')">
            Cancel
          </x-button>
          <x-button type="submit" variant="primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Create Client
          </x-button>
        </div>
      </form>
    </x-card>
  </div>
@endsection
