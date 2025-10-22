@extends('layouts.app')

@section('title', 'Price Masters')
@section('page-title', 'Price Master Management')

@section('content')
  <div class="max-w-7xl">
    <!-- Header -->
    <x-card class="mb-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Price Master Management</h1>
          <p class="text-gray-600 mt-1">Manage pricing for different services and currencies</p>
        </div>
        <x-button variant="primary" :href="route('price-masters.create')">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Add Price Master
        </x-button>
      </div>
    </x-card>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
      <x-card>
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-blue-100 text-blue-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Prices</p>
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
            <p class="text-sm font-medium text-gray-600">Active Prices</p>
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
            <p class="text-sm font-medium text-gray-600">Inactive Prices</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['inactive'] }}</p>
          </div>
        </div>
      </x-card>

      <x-card>
        <div class="flex items-center">
          <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Value</p>
            <p class="text-2xl font-bold text-gray-900">{{ \App\Helpers\NumberHelper::formatCurrency($stats['total_value']) }}</p>
          </div>
        </div>
      </x-card>
    </div>

    <!-- Search and Filters -->
    <x-card class="mb-6">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
        <!-- Search -->
        <div class="flex-1 max-w-md">
          <form method="GET" action="{{ route('price-masters.index') }}" class="flex">
            <x-input
              name="search"
              :value="$search ?? ''"
              placeholder="Search price masters..."
              class="rounded-r-none border-r-0"
            />
            <input type="hidden" name="per_page" value="{{ $perPage }}">
            <input type="hidden" name="service_id" value="{{ $serviceId ?? '' }}">
            <input type="hidden" name="currency_id" value="{{ $currencyId ?? '' }}">
            <button type="submit" class="px-6 py-2 bg-gray-600 text-white rounded-r-lg hover:bg-gray-700 transition-colors">
              Search
            </button>
          </form>
        </div>

        <!-- Filters and Actions -->
        <div class="flex items-center space-x-2">
          <!-- Service Filter -->
          <form method="GET" action="{{ route('price-masters.index') }}" class="flex items-center">
            <input type="hidden" name="search" value="{{ $search ?? '' }}">
            <input type="hidden" name="per_page" value="{{ $perPage }}">
            <input type="hidden" name="currency_id" value="{{ $currencyId ?? '' }}">
            <select name="service_id" onchange="this.form.submit()" class="text-sm border border-gray-300 rounded-lg px-4 py-2 bg-white hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors min-w-[140px]">
              <option value="">All Services</option>
              @foreach($services as $service)
                <option value="{{ $service->id }}" {{ $serviceId == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
              @endforeach
            </select>
          </form>

          <!-- Currency Filter -->
          <form method="GET" action="{{ route('price-masters.index') }}" class="flex items-center">
            <input type="hidden" name="search" value="{{ $search ?? '' }}">
            <input type="hidden" name="per_page" value="{{ $perPage }}">
            <input type="hidden" name="service_id" value="{{ $serviceId ?? '' }}">
            <select name="currency_id" onchange="this.form.submit()" class="text-sm border border-gray-300 rounded-lg px-4 py-2 bg-white hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors min-w-[140px]">
              <option value="">All Currencies</option>
              @foreach($currencies as $currency)
                <option value="{{ $currency->id }}" {{ $currencyId == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
              @endforeach
            </select>
          </form>

          <!-- Per Page Selector -->
          <div class="flex items-center space-x-2">
            <x-per-page-selector
              :per-page="$perPage"
              :per-page-options="[10, 25, 50, 100]"
              :current-url="request()->url()"
              type="server"
            />
          </div>
        </div>
      </div>
    </x-card>

    <!-- Price Masters Table -->
    <x-card>
      @if($priceMasters->count() > 0)
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Currency</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($priceMasters as $priceMaster)
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $priceMaster->service_name }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $priceMaster->formatted_price }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $priceMaster->currency_name }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <x-badge :variant="$priceMaster->is_active ? 'success' : 'danger'">
                      {{ $priceMaster->is_active ? 'Active' : 'Inactive' }}
                    </x-badge>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $priceMaster->note ?? '-' }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $priceMaster->short_created_at }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <x-action-dropdown>
                      <x-dropdown-link :href="route('price-masters.show', $priceMaster)">View</x-dropdown-link>
                      <x-dropdown-link :href="route('price-masters.edit', $priceMaster)">Edit</x-dropdown-link>
                      <x-dropdown-link
                        :href="route('price-masters.destroy', $priceMaster)"
                        onclick="return confirm('Are you sure you want to delete this price master?')"
                        class="text-red-600 hover:text-red-900"
                      >
                        Delete
                      </x-dropdown-link>
                    </x-action-dropdown>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
            <!-- Pagination Info -->
            <div class="text-sm text-gray-700">
              Showing
              <span class="font-medium">{{ $priceMasters->firstItem() ?? 0 }}</span>
              to
              <span class="font-medium">{{ $priceMasters->lastItem() ?? 0 }}</span>
              of
              <span class="font-medium">{{ $priceMasters->total() }}</span>
              results
              @if ($search)
                for "<span class="font-medium text-blue-600">{{ $search }}</span>"
              @endif
            </div>

            <!-- Pagination Controls -->
            @if ($priceMasters->hasPages())
              <x-pagination :paginator="$priceMasters" />
            @endif
          </div>
        </div>
      @else
        <x-empty-state
          icon="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
          title="No Price Masters Found"
          description="Get started by creating a new price master."
          action-text="Add Price Master"
          :action-url="route('price-masters.create')"
        />
      @endif
    </x-card>
  </div>
@endsection
