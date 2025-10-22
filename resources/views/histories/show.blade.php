@extends('layouts.app')

@section('title', 'History Details')
@section('page-title', 'Transaction History Details')

@section('content')
  <div class="max-w-4xl">
    <!-- Header -->
    <x-card class="mb-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Transaction History</h1>
          <p class="text-gray-600 mt-1">Transaction ID: {{ $history->trx_id ?? 'N/A' }}</p>
        </div>
        <div class="flex space-x-3">
          <x-badge :variant="$history->status_badge_variant">
            {{ $history->status_display }}
          </x-badge>
          @if($history->client)
            <x-button variant="secondary" :href="route('histories.by-client', $history->client)">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              Client Histories
            </x-button>
          @endif
          @if($history->service)
            <x-button variant="secondary" :href="route('histories.by-service', $history->service)">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Service Histories
            </x-button>
          @endif
        </div>
      </div>
    </x-card>

    <!-- Transaction Details -->
    <x-card class="mb-6">
      <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Transaction Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="text-sm font-medium text-gray-500">Transaction ID</label>
            <p class="text-sm text-gray-900 mt-1">{{ $history->trx_id ?? 'N/A' }}</p>
          </div>
          <div>
            <label class="text-sm font-medium text-gray-500">Request ID</label>
            <p class="text-sm text-gray-900 mt-1">{{ $history->trx_req ?? 'N/A' }}</p>
          </div>
          <div>
            <label class="text-sm font-medium text-gray-500">Transaction Type</label>
            <div class="mt-1">
              <x-badge :variant="$history->trx_type == 1 ? 'success' : ($history->trx_type == 2 ? 'warning' : 'info')">
                {{ $history->transaction_type_display }}
              </x-badge>
            </div>
          </div>
          <div>
            <label class="text-sm font-medium text-gray-500">Status</label>
            <div class="mt-1">
              <x-badge :variant="$history->status_badge_variant">
                {{ $history->status_display }}
              </x-badge>
            </div>
          </div>
          <div>
            <label class="text-sm font-medium text-gray-500">Transaction Date</label>
            <p class="text-sm text-gray-900 mt-1">
              {{ $history->trx_date ? $history->trx_date->format('M d, Y H:i:s') : 'N/A' }}
            </p>
          </div>
          <div>
            <label class="text-sm font-medium text-gray-500">Created At</label>
            <p class="text-sm text-gray-900 mt-1">
              {{ $history->created_at ? $history->created_at->format('M d, Y H:i:s') : 'N/A' }}
            </p>
          </div>
        </div>
      </div>
    </x-card>

    <!-- Client & Service Information -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
      <!-- Client Information -->
      <x-card>
        <div class="p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Client Information</h3>
          <div class="space-y-4">
            <div>
              <label class="text-sm font-medium text-gray-500">Client Name</label>
              <p class="text-sm text-gray-900 mt-1">{{ $history->client->client_name ?? 'N/A' }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500">Client Type</label>
              <p class="text-sm text-gray-900 mt-1">{{ $history->client_type_display }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500">User ID</label>
              <p class="text-sm text-gray-900 mt-1">{{ $history->user_id ?? 'N/A' }}</p>
            </div>
            @if($history->client)
              <div class="pt-4">
                <x-button variant="primary" :href="route('clients.show', $history->client)">
                  View Client Details
                </x-button>
              </div>
            @endif
          </div>
        </div>
      </x-card>

      <!-- Service Information -->
      <x-card>
        <div class="p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Service Information</h3>
          <div class="space-y-4">
            <div>
              <label class="text-sm font-medium text-gray-500">Service Name</label>
              <p class="text-sm text-gray-900 mt-1">{{ $history->service->name ?? 'N/A' }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500">Module ID</label>
              <p class="text-sm text-gray-900 mt-1">{{ $history->module_id ?? 'N/A' }}</p>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-500">Node ID</label>
              <p class="text-sm text-gray-900 mt-1">{{ $history->node_id ?? 'N/A' }}</p>
            </div>
            @if($history->service)
              <div class="pt-4">
                <x-button variant="primary" :href="route('services.show', $history->service)">
                  View Service Details
                </x-button>
              </div>
            @endif
          </div>
        </div>
      </x-card>
    </div>

    <!-- Financial Information -->
    <x-card class="mb-6">
      <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Financial Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div>
            <label class="text-sm font-medium text-gray-500">Price</label>
            <p class="text-lg font-semibold text-gray-900 mt-1">{{ $history->formatted_price }}</p>
          </div>
          <div>
            <label class="text-sm font-medium text-gray-500">Duration</label>
            <p class="text-lg font-semibold text-gray-900 mt-1">{{ $history->formatted_duration }}</p>
          </div>
          <div>
            <label class="text-sm font-medium text-gray-500">Currency ID</label>
            <p class="text-lg font-semibold text-gray-900 mt-1">{{ $history->currency_id ?? 'N/A' }}</p>
          </div>
        </div>
      </div>
    </x-card>

    <!-- Technical Information -->
    <x-card>
      <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Technical Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="text-sm font-medium text-gray-500">Remote IP</label>
            <p class="text-sm text-gray-900 mt-1">{{ $history->remote_ip ?? 'N/A' }}</p>
          </div>
          <div>
            <label class="text-sm font-medium text-gray-500">Location</label>
            <p class="text-sm text-gray-900 mt-1">{{ $history->local_status_display }}</p>
          </div>
          <div>
            <label class="text-sm font-medium text-gray-500">Charge Status</label>
            <p class="text-sm text-gray-900 mt-1">{{ $history->charge_status_display }}</p>
          </div>
          <div>
            <label class="text-sm font-medium text-gray-500">Source</label>
            <p class="text-sm text-gray-900 mt-1">{{ $history->dashboard_status_display }}</p>
          </div>
        </div>
      </div>
    </x-card>
  </div>
@endsection
