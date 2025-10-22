@extends('layouts.app')

@section('title', 'Price Master Details')
@section('page-title', 'Price Master Details')

@section('content')
  <div class="max-w-4xl">
    <x-card>
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Price Master Details</h1>
            <p class="text-gray-600 mt-1">View detailed information about this price master</p>
          </div>
          <div class="flex items-center space-x-3">
            <x-button variant="secondary" :href="route('price-masters.edit', $priceMaster)">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Edit
            </x-button>
            <x-button variant="secondary" :href="route('price-masters.index')">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              Back to List
            </x-button>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- Basic Information -->
          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
            <dl class="space-y-4">
              <div>
                <dt class="text-sm font-medium text-gray-500">Service</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $priceMaster->service_name }}</dd>
              </div>

              <div>
                <dt class="text-sm font-medium text-gray-500">Currency</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $priceMaster->currency_name }}</dd>
              </div>

              <div>
                <dt class="text-sm font-medium text-gray-500">Price</dt>
                <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $priceMaster->formatted_price }}</dd>
              </div>

              <div>
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                <dd class="mt-1">
                  <x-badge :variant="$priceMaster->is_active ? 'success' : 'danger'">
                    {{ $priceMaster->is_active ? 'Active' : 'Inactive' }}
                  </x-badge>
                </dd>
              </div>
            </dl>
          </div>

          <!-- Additional Information -->
          <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
            <dl class="space-y-4">
              <div>
                <dt class="text-sm font-medium text-gray-500">Note</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $priceMaster->note ?? 'No note provided' }}</dd>
              </div>

              <div>
                <dt class="text-sm font-medium text-gray-500">Created At</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $priceMaster->formatted_created_at }}</dd>
              </div>

              <div>
                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $priceMaster->formatted_updated_at }}</dd>
              </div>
            </dl>
          </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 pt-6 border-t border-gray-200">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
              <x-button variant="primary" :href="route('price-masters.edit', $priceMaster)">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Price Master
              </x-button>

              <x-button variant="secondary" :href="route('price-masters.index')">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                View All Price Masters
              </x-button>
            </div>

            <form method="POST" action="{{ route('price-masters.destroy', $priceMaster) }}" class="inline">
              @csrf
              @method('DELETE')
              <x-button
                variant="danger"
                type="submit"
                onclick="return confirm('Are you sure you want to delete this price master? This action cannot be undone.')"
              >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete
              </x-button>
            </form>
          </div>
        </div>
      </div>
    </x-card>
  </div>
@endsection
