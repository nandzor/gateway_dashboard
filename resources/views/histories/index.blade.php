@extends('layouts.app')

@section('title', 'Histories')
@section('page-title', 'Transaction Histories')

@section('content')
  <x-card>
    <!-- Header -->
    <div class="p-6 border-b border-gray-200">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
        <!-- Search -->
        <div class="flex-1 max-w-md">
          <form method="GET" action="{{ route('histories.index') }}" class="flex">
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
        <div class="flex items-center space-x-2">
          @include('histories.partials.filters', ['filterAction' => route('histories.index')])

          <!-- Per Page Selector -->
          <div class="flex items-center space-x-2">
            <x-per-page-selector
              :per-page="$perPage"
              :per-page-options="$perPageOptions"
              :current-url="request()->url()"
              type="server"
            />
          </div>

          <!-- Export Button -->
          <x-button variant="secondary" :href="route('histories.export', request()->query())">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export
          </x-button>
        </div>
      </div>
    </div>

    <!-- Statistics -->
    @include('histories.partials.statistics')

    <!-- Table -->
    <div class="overflow-x-auto">
      @if($histories->count() > 0)
        @include('histories.partials.table')
        @include('histories.partials.pagination')
      @else
        @include('histories.partials.empty-state', [
          'hasFilters' => $search || $status || $transactionType || $clientId || $serviceId,
          'emptyDescription' => 'No transaction histories have been recorded yet.',
          'clearFiltersUrl' => route('histories.index'),
          'emptyActionText' => null,
          'emptyActionUrl' => null
        ])
      @endif
    </div>
  </x-card>
@endsection
