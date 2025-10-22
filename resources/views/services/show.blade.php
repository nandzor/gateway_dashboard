@extends('layouts.app')

@section('title', 'Service Details')
@section('page-title', 'Service Details')

@section('content')
  <div class="max-w-4xl">
    <!-- Header -->
    <x-card class="mb-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-md mr-4">
            <span class="text-lg font-bold text-white">{{ substr($service->name, 0, 1) }}</span>
          </div>
          <div>
            <h2 class="text-xl font-bold text-gray-900">{{ $service->name }}</h2>
            <p class="text-sm text-gray-500">Service ID: {{ $service->id }}</p>
          </div>
        </div>
        <div class="flex space-x-3">
          <x-button variant="secondary" :href="route('histories.by-service', $service)">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            View Histories
          </x-button>
          <x-button variant="secondary" :href="route('services.edit', $service)">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit Service
          </x-button>
          <x-button variant="primary" :href="route('services.index')">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Services
          </x-button>
        </div>
      </div>
    </x-card>

    <!-- Service Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
      <!-- Basic Information -->
      <x-card>
        <div class="p-6 border-b border-gray-200">
          <h3 class="text-lg font-medium text-gray-900">Service Information</h3>
        </div>
        <div class="p-6 space-y-4">
          <div>
            <label class="text-sm font-medium text-gray-500">Service Name</label>
            <p class="text-sm text-gray-900 mt-1">{{ $service->name }}</p>
          </div>
          <div>
            <label class="text-sm font-medium text-gray-500">Status</label>
            <div class="mt-1">
              <x-badge :variant="$service->is_active == 1 ? 'success' : 'danger'">
                {{ $service->is_active == 1 ? 'Active' : 'Inactive' }}
              </x-badge>
            </div>
          </div>
        </div>
      </x-card>

      <!-- Service Statistics -->
      <x-card>
        <div class="p-6 border-b border-gray-200">
          <h3 class="text-lg font-medium text-gray-900">Service Statistics</h3>
        </div>
        <div class="p-6 space-y-4">
          <div>
            <label class="text-sm font-medium text-gray-500">Assigned Clients</label>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $service->clients->count() }}</p>
          </div>
          <div>
            <label class="text-sm font-medium text-gray-500">Created Date</label>
            <p class="text-sm text-gray-900 mt-1">{{ $service->created_at ? $service->created_at->format('M d, Y H:i') : 'N/A' }}</p>
          </div>
          <div>
            <label class="text-sm font-medium text-gray-500">Last Updated</label>
            <p class="text-sm text-gray-900 mt-1">{{ $service->updated_at ? $service->updated_at->format('M d, Y H:i') : 'N/A' }}</p>
          </div>
        </div>
      </x-card>
    </div>

    <!-- Assigned Clients -->
    <x-card>
      <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Assigned Clients</h3>
        <p class="text-sm text-gray-500 mt-1">Clients that have access to this service</p>
      </div>

      @if($service->clients->count() > 0)
        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($service->clients as $client)
              <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100 p-4">
                <div class="flex items-center justify-between">
                  <div class="flex-1">
                    <h4 class="text-sm font-semibold text-gray-900">{{ $client->client_name }}</h4>
                    <p class="text-xs text-gray-600 mt-1">Client ID: {{ $client->id }}</p>
                    <div class="flex items-center mt-2">
                      <x-badge :variant="$client->is_active == 1 ? 'success' : 'danger'" size="sm">
                        {{ $client->is_active == 1 ? 'Active' : 'Inactive' }}
                      </x-badge>
                    </div>
                  </div>
                  <div class="ml-4">
                    <x-button variant="secondary" size="sm" :href="route('clients.show', $client)">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </x-button>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @else
        <div class="p-12 text-center">
          <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          <h3 class="text-lg font-medium text-gray-900 mb-2">No Clients Assigned</h3>
          <p class="text-gray-500 mb-4">This service doesn't have any clients assigned yet.</p>
          <x-button variant="primary" :href="route('clients.index')">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            View Clients
          </x-button>
        </div>
      @endif
    </x-card>
  </div>
@endsection
