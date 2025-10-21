@extends('layouts.app')

@section('title', 'Assign Services')
@section('page-title', 'Assign Services - ' . $client->client_name)

@section('content')
  <div class="max-w-4xl">
    <!-- Client Info Header -->
    <x-card class="mb-6">
      <div class="flex items-center">
        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-md mr-4">
          <span class="text-lg font-bold text-white">{{ substr($client->client_name, 0, 1) }}</span>
        </div>
        <div>
          <h2 class="text-xl font-bold text-gray-900">{{ $client->client_name }}</h2>
          <p class="text-sm text-gray-500">Client ID: {{ $client->id }}</p>
        </div>
      </div>
    </x-card>

    <!-- Assign Services Form -->
    <x-card>
      <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Assign Services</h3>
        <p class="text-sm text-gray-500 mt-1">Select services to assign to this client</p>
      </div>

      <form method="POST" action="{{ route('client-service-assignments.store', $client->id) }}" class="p-6">
        @csrf

        @if($availableServices->count() > 0)
          <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              @foreach($availableServices as $service)
                @php
                  $isAssigned = $assignedServices->contains('id', $service->id);
                @endphp
                <div class="bg-gradient-to-r {{ $isAssigned ? 'from-blue-50 to-indigo-50 border-blue-200' : 'from-gray-50 to-gray-100 border-gray-200' }} rounded-lg border p-4 {{ $isAssigned ? 'opacity-60' : '' }}">
                  <label class="flex items-start space-x-3 cursor-pointer {{ $isAssigned ? 'pointer-events-none' : '' }}">
                    <input type="checkbox"
                           name="service_ids[]"
                           value="{{ $service->id }}"
                           {{ $isAssigned ? 'checked disabled' : '' }}
                           class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <div class="flex-1">
                      <h4 class="text-sm font-semibold text-gray-900">{{ $service->name }}</h4>
                      <p class="text-xs text-gray-600 mt-1">{{ $service->type_name }}</p>
                      <div class="flex items-center mt-2">
                        <x-badge :variant="$service->is_active == 1 ? 'success' : 'danger'" size="sm">
                          {{ $service->is_active == 1 ? 'Active' : 'Inactive' }}
                        </x-badge>
                        @if($service->is_alert_zero)
                          <x-badge variant="warning" size="sm" class="ml-2">
                            Alert Zero
                          </x-badge>
                        @endif
                        @if($isAssigned)
                          <x-badge variant="info" size="sm" class="ml-2">
                            Already Assigned
                          </x-badge>
                        @endif
                      </div>
                    </div>
                  </label>
                </div>
              @endforeach
            </div>
          </div>

          <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
            <x-button variant="secondary" :href="route('client-service-assignments.index', $client)">
              Cancel
            </x-button>
            <x-button type="submit" variant="primary">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              Assign Selected Services
            </x-button>
          </div>
        @else
          <div class="p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Available Services</h3>
            <p class="text-gray-500 mb-4">There are no active services available for assignment.</p>
            <x-button variant="secondary" :href="route('client-service-assignments.index', $client)">
              Back to Assignments
            </x-button>
          </div>
        @endif
      </form>
    </x-card>
  </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submission
    const form = document.querySelector('form');
    const checkboxes = document.querySelectorAll('input[name="service_ids[]"]:not([disabled])');

    form.addEventListener('submit', function(e) {
        const checkedBoxes = Array.from(checkboxes).filter(cb => cb.checked);

        if (checkedBoxes.length === 0) {
            e.preventDefault();
            alert('Please select at least one service to assign.');
            return false;
        }
    });
});
</script>
@endpush
