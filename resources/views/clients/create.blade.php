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
          <x-input name="client_name" label="Client Name" placeholder="Enter client name" required />

          <x-select name="type" label="Client Type" :options="$typeOptions" placeholder="Select client type" required />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <x-input name="address" label="Address" placeholder="Enter client address" />

          <x-input name="contact" label="Contact" placeholder="Enter contact information" />
        </div>

        <!-- API Credentials -->
        <div class="border-t pt-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">API Credentials</h3>
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
            <div class="flex items-center">
              <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <p class="text-sm text-blue-800">API credentials will be automatically generated when you create the client.</p>
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-copy-input name="ak"
                          :value="$previewCredentials['ak']"
                          label="Access Key (AK)" />

            <x-copy-input name="sk"
                          :value="$previewCredentials['sk']"
                          label="Secret Key (SK)" />
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            <x-copy-input name="avkey_iv"
                          :value="$previewCredentials['avkey_iv']"
                          label="AVKey IV" />

            <x-copy-input name="avkey_pass"
                          :value="$previewCredentials['avkey_pass']"
                          label="AVKey Pass" />
          </div>
        </div>

        <!-- Service Assignment -->
        <div class="border-t pt-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Service Assignment</h3>
          <div class="grid grid-cols-1 gap-6">
            <x-multi-select name="service_assignments"
                           label="Assign Services"
                           :options="$serviceModuleOptions"
                           placeholder="Select services to assign" />
          </div>
        </div>

        <!-- Security Settings -->
        <div class="border-t pt-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Security Settings</h3>
          <div class="grid grid-cols-1 gap-6">
            <x-ip-tags-input name="white_list"
                           label="Whitelist IPs"
                           placeholder="Enter IP address and press Enter" />
          </div>
        </div>

        <!-- Status Settings -->
        <div class="border-t pt-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Status Settings</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-select name="is_active" label="Status" :options="[1 => 'Active', 0 => 'Inactive']" :selected="1" placeholder="Select status" required />

            <x-select name="is_staging" label="Environment" :options="[0 => 'Production', 1 => 'Staging']" :selected="0" placeholder="Select environment" required />
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

  <x-copy-script />
@endsection
