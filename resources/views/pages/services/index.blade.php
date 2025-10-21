@extends('layouts.app')

@section('title', 'Services')
@section('page-title', 'Services Management')

@section('content')
  <div class="max-w-7xl">
    <!-- Header -->
    <x-card class="mb-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Services</h1>
          <p class="text-gray-600 mt-1">Manage available services and their configurations</p>
        </div>
        <x-button variant="primary">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Add Service
        </x-button>
      </div>
    </x-card>

    <!-- Coming Soon Card -->
    <x-card>
      <div class="text-center py-12">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Services Management</h3>
        <p class="text-gray-500 mb-4">This feature is coming soon. You'll be able to manage services and their configurations here.</p>
        <x-badge variant="info">Coming Soon</x-badge>
      </div>
    </x-card>
  </div>
@endsection
