@extends('layouts.app')

@section('title', 'Create Service')
@section('page-title', 'Create Gateway Service')

@section('content')
  <div class="max-w-2xl">
    <!-- Header -->
    <x-card class="mb-6">
      <div class="flex items-center">
        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-md mr-4">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
          </svg>
        </div>
        <div>
          <h2 class="text-xl font-bold text-gray-900">Create New Service</h2>
          <p class="text-sm text-gray-500">Add a new gateway service to the system</p>
        </div>
      </div>
    </x-card>

    <!-- Create Form -->
    <x-card>
      <form method="POST" action="{{ route('services.store') }}">
        @csrf

        <div class="p-6 space-y-6">
          <!-- Service Name -->
          <div>
            <x-input
              name="name"
              label="Service Name"
              placeholder="e.g., OCR Service, Identity Check"
              :value="old('name')"
              required
            />
            @error('name')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- Status -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <div class="space-y-2">
              <label class="flex items-center">
                <input type="radio" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="mr-2">
                <span class="text-sm text-gray-700">Active</span>
              </label>
              <label class="flex items-center">
                <input type="radio" name="is_active" value="0" {{ old('is_active') == '0' ? 'checked' : '' }} class="mr-2">
                <span class="text-sm text-gray-700">Inactive</span>
              </label>
            </div>
            @error('is_active')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Form Actions -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end space-x-3">
          <x-button variant="secondary" :href="route('services.index')">
            Cancel
          </x-button>
          <x-button type="submit" variant="primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Create Service
          </x-button>
        </div>
      </form>
    </x-card>
  </div>
@endsection
