@extends('layouts.app')

@section('title', 'Price Custom')
@section('page-title', 'Custom Pricing')

@section('content')
  <div class="max-w-7xl">
    <!-- Header -->
    <x-card class="mb-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Custom Pricing</h1>
          <p class="text-gray-600 mt-1">Manage custom pricing rules and configurations</p>
        </div>
        <x-button variant="primary">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Add Custom Price
        </x-button>
      </div>
    </x-card>

    <!-- Coming Soon Card -->
    <x-card>
      <div class="text-center py-12">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Custom Pricing Management</h3>
        <p class="text-gray-500 mb-4">This feature is coming soon. You'll be able to manage custom pricing rules here.</p>
        <x-badge variant="info">Coming Soon</x-badge>
      </div>
    </x-card>
  </div>
@endsection
