@extends('layouts.app')

@section('title', 'Create Currency')
@section('page-title', 'Create New Currency')

@section('content')
  <div class="max-w-4xl">
    <x-card>
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Create New Currency</h1>
            <p class="text-gray-600 mt-1">Add a new currency to the system</p>
          </div>
          <x-button variant="secondary" :href="route('currencies.index')">
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

        <form method="POST" action="{{ route('currencies.store') }}" id="currency-form">
          @csrf

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Currency Name -->
            <div>
              <x-input
                name="name"
                label="Currency Name"
                type="text"
                :value="old('name')"
                placeholder="e.g., Indonesian Rupiah"
                required
                error="{{ $errors->first('name') }}"
              />
            </div>


            <!-- Currency Symbol -->
            <div>
              <x-input
                name="symbol"
                label="Currency Symbol"
                type="text"
                :value="old('symbol')"
                placeholder="e.g., Rp"
                required
                error="{{ $errors->first('symbol') }}"
              />
            </div>

            <!-- Status -->
            <div>
              <x-select
                name="is_active"
                label="Status"
                :options="['1' => 'Active', '0' => 'Inactive']"
                :selected="old('is_active', 1)"
                required
                error="{{ $errors->first('is_active') }}"
              />
            </div>
          </div>

          <!-- Form Actions -->
          <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
            <x-button variant="secondary" :href="route('currencies.index')">
              Cancel
            </x-button>
            <x-button variant="primary" type="submit">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              Create Currency
            </x-button>
          </div>
        </form>
      </div>
    </x-card>
  </div>

  <script>
    document.getElementById('currency-form').addEventListener('submit', function() {
      const submitButton = this.querySelector('button[type="submit"]');
      submitButton.disabled = true;
      submitButton.innerHTML = `
        <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        Creating...
      `;
    });

    // Auto-uppercase currency code
    document.querySelector('input[name="code"]').addEventListener('input', function() {
      this.value = this.value.toUpperCase();
    });
  </script>
@endsection
