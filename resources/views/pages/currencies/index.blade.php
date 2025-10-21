@extends('layouts.app')

@section('title', 'Currencies')
@section('page-title', 'Currencies Management')

@section('content')
  <div class="max-w-7xl">
    <!-- Header -->
    <x-card class="mb-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Currencies</h1>
          <p class="text-gray-600 mt-1">Manage currency settings and exchange rates</p>
        </div>
        <x-button variant="primary">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Add Currency
        </x-button>
      </div>
    </x-card>

    <!-- Coming Soon Card -->
    <x-card>
      <div class="text-center py-12">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Currencies Management</h3>
        <p class="text-gray-500 mb-4">This feature is coming soon. You'll be able to manage currencies and exchange rates here.</p>
        <x-badge variant="info">Coming Soon</x-badge>
      </div>
    </x-card>
  </div>
@endsection
