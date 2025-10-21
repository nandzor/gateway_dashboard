@extends('layouts.app')

@section('title', 'Service Assignments')
@section('page-title', 'Service Assignments - ' . $client->client_name)

@section('content')
  <div class="max-w-6xl">
    <!-- Client Info Header -->
    <x-card class="mb-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-md mr-4">
            <span class="text-lg font-bold text-white">{{ substr($client->client_name, 0, 1) }}</span>
          </div>
          <div>
            <h2 class="text-xl font-bold text-gray-900">{{ $client->client_name }}</h2>
            <p class="text-sm text-gray-500">Client ID: {{ $client->id }}</p>
          </div>
        </div>
        <div class="flex space-x-3">
          <x-button variant="primary" :href="route('client-service-assignments.create', $client)">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Assign Services
          </x-button>
          <x-button variant="secondary" :href="route('clients.show', $client)">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Client
          </x-button>
        </div>
      </div>
    </x-card>

    <!-- Assigned Services -->
    <x-card>
      <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Assigned Services</h3>
        <p class="text-sm text-gray-500 mt-1">Services currently assigned to this client</p>
      </div>

      @if($assignedServices->count() > 0)
        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($assignedServices as $service)
              <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-100 p-4">
                <div class="flex items-center justify-between">
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
                    </div>
                  </div>
                  <div class="ml-4">
                    <form method="POST" action="{{ route('client-service-assignments.destroy', [$client->id, $service->id]) }}"
                          onsubmit="return confirm('Are you sure you want to unassign this service?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="text-red-600 hover:text-red-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @else
        <div class="p-12 text-center">
          <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <h3 class="text-lg font-medium text-gray-900 mb-2">No Services Assigned</h3>
          <p class="text-gray-500 mb-4">This client doesn't have any services assigned yet.</p>
          <x-button variant="primary" :href="route('client-service-assignments.create', $client)">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Assign Services
          </x-button>
        </div>
      @endif
    </x-card>

    <!-- Available Services -->
    @if($availableServices->count() > 0)
      <x-card class="mt-6">
        <div class="p-6 border-b border-gray-200">
          <h3 class="text-lg font-medium text-gray-900">Available Services</h3>
          <p class="text-sm text-gray-500 mt-1">Services that can be assigned to this client</p>
        </div>

        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($availableServices as $service)
              @php
                $isAssigned = $assignedServices->contains('id', $service->id);
              @endphp
              <div class="bg-gradient-to-r {{ $isAssigned ? 'from-blue-50 to-indigo-50 border-blue-100' : 'from-gray-50 to-gray-100 border-gray-200' }} rounded-lg border p-4 {{ $isAssigned ? 'opacity-60' : '' }}">
                <div class="flex items-center justify-between">
                  <div class="flex-1">
                    <h4 class="text-sm font-semibold text-gray-900">{{ $service->name }}</h4>
                    <p class="text-xs text-gray-600 mt-1">{{ $service->type_name }}</p>
                    <div class="flex items-center mt-2">
                      <x-badge :variant="$service->is_active == 1 ? 'success' : 'danger'" size="sm">
                        {{ $service->is_active == 1 ? 'Active' : 'Inactive' }}
                      </x-badge>
                      @if($isAssigned)
                        <x-badge variant="info" size="sm" class="ml-2">
                          Assigned
                        </x-badge>
                      @endif
                    </div>
                  </div>
                  @if(!$isAssigned)
                    <div class="ml-4">
                      <form method="POST" action="{{ route('client-service-assignments.store', $client->id) }}">
                        @csrf
                        <input type="hidden" name="service_ids[]" value="{{ $service->id }}">
                        <button type="submit" class="text-green-600 hover:text-green-800 transition-colors">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                          </svg>
                        </button>
                      </form>
                    </div>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </x-card>
    @endif
  </div>
@endsection
