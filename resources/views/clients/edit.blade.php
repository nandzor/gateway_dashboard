@extends('layouts.app')

@section('title', 'Edit Client')
@section('page-title', 'Edit Client')

@section('content')
  <div class="max-w-4xl">
    <x-card title="Update Client Information">
      <div class="mb-6">
        <div class="flex items-center justify-between">
          <p class="text-sm text-gray-500">Modify the client details below</p>
          <div class="flex space-x-2">
            <x-badge :variant="$client->is_active == 1 ? 'success' : 'danger'">
              {{ $client->is_active == 1 ? 'Active' : 'Inactive' }}
            </x-badge>
            <x-badge :variant="$client->is_staging == 1 ? 'warning' : 'primary'">
              {{ $client->is_staging == 1 ? 'Staging' : 'Production' }}
            </x-badge>
          </div>
        </div>
      </div>

      <form method="POST" action="{{ route('clients.update', $client->id) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <x-input name="client_name" label="Client Name" :value="$client->client_name" placeholder="Enter client name" required />

          <x-select name="type" label="Client Type" :options="$typeOptions" :selected="$client->type" placeholder="Select client type" required />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <x-input name="address" label="Address" :value="$client->address" placeholder="Enter client address" />

          <x-input name="contact" label="Contact" :value="$client->contact" placeholder="Enter contact information" />
        </div>

        <!-- API Credentials -->
        <div class="border-t pt-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">API Credentials</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-input name="ak" label="Access Key (AK)" :value="$client->ak" placeholder="Enter access key" />

            <x-input name="sk" label="Secret Key (SK)" :value="$client->sk" placeholder="Enter secret key" />
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            <x-input name="avkey_iv" label="AVKey IV" :value="$client->avkey_iv" placeholder="Enter avkey iv" />

            <x-input name="avkey_pass" label="AVKey Pass" :value="$client->avkey_pass" placeholder="Enter avkey pass" />
          </div>
        </div>

        <!-- Service Assignment -->
        <div class="border-t pt-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Service Assignment</h3>
          <div class="grid grid-cols-1 gap-6">
            <x-multi-select name="service_assignments"
                           label="Assign Services"
                           :options="$serviceModuleOptions"
                           :selected="$client->service_assignments ?? []"
                           placeholder="Select services to assign" />
          </div>
        </div>

        <!-- Security Settings -->
        <div class="border-t pt-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Security Settings</h3>
          <div class="grid grid-cols-1 gap-6">
            <x-ip-tags-input name="white_list"
                           label="Whitelist IPs"
                           :value="$client->white_list ? explode(',', $client->white_list) : []"
                           placeholder="Enter IP address and press Enter" />
          </div>
        </div>

        <!-- Status Settings -->
        <div class="border-t pt-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Status Settings</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-select name="is_active" label="Status" :options="[1 => 'Active', 0 => 'Inactive']" :selected="$client->is_active" placeholder="Select status" required />

            <x-select name="is_staging" label="Environment" :options="[0 => 'Production', 1 => 'Staging']" :selected="$client->is_staging" placeholder="Select environment" required />
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
            Update Client
          </x-button>
        </div>
      </form>
    </x-card>
  </div>
@endsection
