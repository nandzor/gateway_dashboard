@extends('layouts.app')

@section('title', 'Create Price Master')
@section('page-title', 'Create New Price Master')

@section('content')
  <div class="max-w-4xl">
    <x-card>
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Create New Price Master</h1>
            <p class="text-gray-600 mt-1">Add pricing information for a service and currency</p>
          </div>
          <x-button variant="secondary" :href="route('price-masters.index')">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to List
          </x-button>
        </div>

        @if ($errors->any())
          <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                <div class="mt-2 text-sm text-red-700">
                  <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>
          </div>
        @endif

        <form method="POST" action="{{ route('price-masters.store') }}" id="price-master-form">
          @csrf

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Service Selection -->
            <div>
              <x-input
                name="service_id"
                label="Service"
                type="select"
                :value="old('service_id')"
                required
                error="{{ $errors->first('service_id') }}"
              >
                <option value="">Select a service</option>
                @foreach($services as $service)
                  <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                    {{ $service->name }}
                  </option>
                @endforeach
              </x-input>
            </div>

            <!-- Currency Selection -->
            <div>
              <x-input
                name="currency_id"
                label="Currency"
                type="select"
                :value="old('currency_id')"
                required
                error="{{ $errors->first('currency_id') }}"
              >
                <option value="">Select a currency</option>
                @foreach($currencies as $currency)
                  <option value="{{ $currency->id }}" {{ old('currency_id') == $currency->id ? 'selected' : '' }}>
                    {{ $currency->name }}
                  </option>
                @endforeach
              </x-input>
            </div>

            <!-- Price -->
            <div>
              <x-input
                name="price_default"
                label="Price"
                type="number"
                step="0.001"
                min="0"
                :value="old('price_default')"
                placeholder="Enter price amount"
                required
                error="{{ $errors->first('price_default') }}"
              />
            </div>

            <!-- Status -->
            <div>
              <x-input
                name="is_active"
                label="Status"
                type="select"
                :value="old('is_active', 1)"
                required
                error="{{ $errors->first('is_active') }}"
              >
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </x-input>
            </div>

            <!-- Note -->
            <div class="md:col-span-2">
              <x-input
                name="note"
                label="Note"
                type="text"
                :value="old('note')"
                placeholder="Optional note about this price"
                error="{{ $errors->first('note') }}"
              />
            </div>
          </div>

          <!-- Form Actions -->
          <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
            <x-button variant="secondary" :href="route('price-masters.index')">
              Cancel
            </x-button>
            <x-button variant="primary" type="submit">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              Create Price Master
            </x-button>
          </div>
        </form>
      </div>
    </x-card>
  </div>

  <script>
    document.getElementById('price-master-form').addEventListener('submit', function() {
      const submitButton = this.querySelector('button[type="submit"]');
      submitButton.disabled = true;
      submitButton.innerHTML = `
        <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        Creating...
      `;
    });
  </script>
@endsection
