@extends('layouts.app')

@section('title', 'Price Master')
@section('page-title', 'Master Pricing')

@section('content')
  <div class="max-w-7xl">
    <!-- Header -->
    <x-card class="mb-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Master Pricing</h1>
          <p class="text-gray-600 mt-1">Manage master pricing configurations and base rates</p>
        </div>
        <x-button variant="primary">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Add Master Price
        </x-button>
      </div>
    </x-card>

    <!-- Coming Soon Card -->
    <x-card>
      <div class="text-center py-12">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Master Pricing Management</h3>
        <p class="text-gray-500 mb-4">This feature is coming soon. You'll be able to manage master pricing configurations here.</p>
        <x-badge variant="info">Coming Soon</x-badge>
      </div>
    </x-card>
  </div>
@endsection
