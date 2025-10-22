@extends('layouts.app')

@section('title', 'Client Histories')
@section('page-title', 'Client Transaction Histories')

@section('content')
  <x-card>
    <!-- Header -->
    <div class="p-6 border-b border-gray-200">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
        <div class="flex-1">
          <div class="flex items-center space-x-4">
            <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-md">
              <span class="text-lg font-bold text-white">{{ substr($client->client_name, 0, 1) }}</span>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-gray-900">{{ $client->client_name }}</h1>
              <p class="text-gray-600 mt-1">Transaction Histories</p>
            </div>
          </div>
        </div>

        <div class="flex items-center space-x-3">
          <x-button variant="secondary" :href="route('clients.show', $client)">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            View Client
          </x-button>
          <x-button variant="secondary" :href="route('histories.index')">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            All Histories
          </x-button>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="p-6 border-b border-gray-200 bg-gray-50">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
        <!-- Search -->
        <div class="flex-1 max-w-md">
          <form method="GET" action="{{ route('histories.by-client', $client) }}" class="flex">
            <x-input
              name="search"
              :value="$search ?? ''"
              placeholder="Search histories..."
              class="rounded-r-none border-r-0"
            />
            @include('histories.partials.hidden-filters')
            <button type="submit" class="px-6 py-2 bg-gray-600 text-white rounded-r-lg hover:bg-gray-700 transition-colors">
              Search
            </button>
          </form>
        </div>

        <!-- Filters and Actions -->
        <div class="flex items-center space-x-4">
          <!-- Per Page Selector -->
          <x-per-page-selector
            :per-page="$perPage"
            :per-page-options="[10, 15, 25, 50, 100]"
            :current-url="request()->url()"
            type="server"
          />

          <!-- Export Button -->
          <x-button variant="secondary" :href="route('histories.export', array_merge(request()->query(), ['client_id' => $client->id]))">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export
          </x-button>
        </div>
      </div>
    </div>

    <!-- Client Information -->
    @include('histories.partials.client-info')

    <!-- Table -->
    <div class="overflow-x-auto">
      @if($histories->count() > 0)
        @include('histories.partials.table')
        @include('histories.partials.pagination')
      @else
        @include('histories.partials.empty-state', [
          'hasFilters' => $search,
          'emptyDescription' => 'This client doesn\'t have any transaction histories yet.',
          'clearFiltersUrl' => route('histories.by-client', $client),
          'emptyActionText' => 'View Client Details',
          'emptyActionUrl' => route('clients.show', $client)
        ])
      @endif
    </div>
  </x-card>
@endsection
