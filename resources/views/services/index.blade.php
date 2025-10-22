@extends('layouts.app')

@section('title', 'Services')
@section('page-title', 'Gateway Services')

@section('content')
  <div class="max-w-7xl">
    <!-- Header -->
    <x-card class="mb-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Gateway Services</h1>
          <p class="text-gray-600 mt-1">Manage gateway services like OCR, Identity Check, etc.</p>
        </div>
        <x-button variant="primary" :href="route('services.create')">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Add Service
        </x-button>
      </div>
    </x-card>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <x-card>
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-blue-100 text-blue-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Services</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
          </div>
        </div>
      </x-card>

      <x-card>
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-green-100 text-green-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Active Services</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] }}</p>
          </div>
        </div>
      </x-card>

      <x-card>
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-red-100 text-red-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Inactive Services</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['inactive'] }}</p>
          </div>
        </div>
      </x-card>
    </div>

    <!-- Search and Filters -->
    <x-card class="mb-6">
      <form method="GET" action="{{ route('services.index') }}" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
          <x-input
            name="search"
            placeholder="Search services..."
            :value="$search"
            class="w-full"
          />
        </div>
        <div class="flex gap-2">
          <x-button type="submit" variant="primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            Search
          </x-button>
          @if($search)
            <x-button :href="route('services.index')" variant="secondary">
              Clear
            </x-button>
          @endif
        </div>
      </form>
    </x-card>

    <!-- Services Table -->
    <x-card>
      @if($services->count() > 0)
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alert Zero</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($services as $service)
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-md mr-4">
                        <span class="text-sm font-bold text-white">{{ substr($service->name, 0, 1) }}</span>
                      </div>
                      <div>
                        <div class="text-sm font-medium text-gray-900">{{ $service->name }}</div>
                        <div class="text-sm text-gray-500">ID: {{ $service->id }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <x-badge :variant="$service->type == 1 ? 'info' : 'warning'">
                      {{ $service->type_name }}
                    </x-badge>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <x-badge :variant="$service->is_active == 1 ? 'success' : 'danger'">
                      {{ $service->is_active == 1 ? 'Active' : 'Inactive' }}
                    </x-badge>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <x-badge :variant="$service->is_alert_zero == 1 ? 'warning' : 'secondary'">
                      {{ $service->is_alert_zero == 1 ? 'Yes' : 'No' }}
                    </x-badge>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $service->created_at ? $service->created_at->format('M d, Y') : 'N/A' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <x-action-dropdown>
                      <x-dropdown-link :href="route('services.show', $service)">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        View
                      </x-dropdown-link>
                      <x-dropdown-link :href="route('services.edit', $service)">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                      </x-dropdown-link>
                      @if($service->is_active == 1)
                        <x-dropdown-link
                          :href="route('services.destroy', $service)"
                          onclick="return confirm('Are you sure you want to deactivate this service?')"
                          class="text-red-600 hover:text-red-800"
                        >
                          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                          Deactivate
                        </x-dropdown-link>
                      @else
                        <x-dropdown-link
                          :href="route('services.restore', $service)"
                          onclick="return confirm('Are you sure you want to restore this service?')"
                          class="text-green-600 hover:text-green-800"
                        >
                          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                          </svg>
                          Restore
                        </x-dropdown-link>
                      @endif
                    </x-action-dropdown>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
          {{ $services->links() }}
        </div>
      @else
        <div class="text-center py-12">
          <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
          </svg>
          <h3 class="text-lg font-medium text-gray-900 mb-2">No Services Found</h3>
          <p class="text-gray-500 mb-4">
            @if($search)
              No services match your search criteria.
            @else
              Get started by creating your first gateway service.
            @endif
          </p>
          <x-button variant="primary" :href="route('services.create')">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Service
          </x-button>
        </div>
      @endif
    </x-card>
  </div>
@endsection
