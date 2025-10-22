@extends('layouts.app')

@section('title', 'Analytics')

@section('content')
  <div class="max-w-7xl mx-auto">
    <!-- Page Header with Export -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Analytics Dashboard</h1>
        <p class="mt-2 text-gray-600">Comprehensive analytics and insights from transaction data</p>
      </div>

      @if ($totalTransactions > 0)
        <div class="mt-4 sm:mt-0">
          <x-dropdown align="right" width="48">
            <x-slot name="trigger">
              <button type="button"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Export Analytics
                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
                </svg>
              </button>
            </x-slot>

            <x-dropdown-link :href="route('analytics.export', [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'client_id' => $clientId,
                'service_id' => $serviceId,
                'format' => 'excel',
            ])" variant="success">
              <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
              </svg>
              Export to Excel
            </x-dropdown-link>

            <x-dropdown-link :href="route('analytics.export', [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'client_id' => $clientId,
                'service_id' => $serviceId,
                'format' => 'pdf',
            ])" variant="danger">
              <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
              </svg>
              Export to PDF
            </x-dropdown-link>
          </x-dropdown>
        </div>
      @endif
    </div>

    <!-- Filters -->
    <x-card class="mb-6">
      <form method="GET">
        <div class="flex flex-col lg:flex-row lg:items-end gap-4">
          <!-- Filter Fields -->
          <div class="flex-1 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-input type="date" name="date_from" label="Date From" :value="$dateFrom" />
            <x-input type="date" name="date_to" label="Date To" :value="$dateTo" />
            <x-input type="select" name="client_id" label="Client" :value="$clientId">
              <option value="">All Clients</option>
              @foreach ($clients as $client)
                <option value="{{ $client->id }}" {{ $clientId == $client->id ? 'selected' : '' }}>
                  {{ $client->client_name }}
                </option>
              @endforeach
            </x-input>
            <x-input type="select" name="service_id" label="Service" :value="$serviceId">
              <option value="">All Services</option>
              @foreach ($services as $service)
                <option value="{{ $service->id }}" {{ $serviceId == $service->id ? 'selected' : '' }}>
                  {{ $service->name }}
                </option>
              @endforeach
            </x-input>
          </div>

          <!-- Action Button -->
          <x-button type="submit" variant="primary" size="md" class="w-full md:w-auto">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Apply Filters
          </x-button>
        </div>
      </form>
    </x-card>

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
      <x-card class="text-center">
        <div class="p-6">
          <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-blue-100 rounded-lg">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-900">@formatNumber($totalTransactions)</h3>
          <p class="text-sm text-gray-600">Total Transactions</p>
        </div>
      </x-card>

      <x-card class="text-center">
        <div class="p-6">
          <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-green-100 rounded-lg">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-900">@formatCurrency($totalRevenue)</h3>
          <p class="text-sm text-gray-600">Total Revenue</p>
        </div>
      </x-card>

      <x-card class="text-center">
        <div class="p-6">
          <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-purple-100 rounded-lg">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-900">@formatNumber($totalDuration)</h3>
          <p class="text-sm text-gray-600">Total Duration</p>
        </div>
      </x-card>

      <x-card class="text-center">
        <div class="p-6">
          <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-orange-100 rounded-lg">
            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-900">@formatNumber($uniqueUsers)</h3>
          <p class="text-sm text-gray-600">Unique Users</p>
        </div>
      </x-card>

      <x-card class="text-center">
        <div class="p-6">
          <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-indigo-100 rounded-lg">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-900">@formatNumber($uniqueClients)</h3>
          <p class="text-sm text-gray-600">Unique Clients</p>
        </div>
      </x-card>
    </div>

    <!-- Analytics Charts and Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <!-- Transaction Types Breakdown -->
      <x-card title="Transaction Types Breakdown">
        <div class="p-6">
          @if ($transactionTypes->count() > 0)
            <div class="space-y-4">
              @foreach ($transactionTypes as $type => $data)
                <div class="flex items-center justify-between">
                  <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                      <span class="text-sm font-medium text-gray-700">{{ $type }}</span>
                      <span class="text-sm text-gray-500">{{ $data['count'] }} ({{ $data['percentage'] }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                      <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $data['percentage'] }}%"></div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <p class="text-gray-500 text-center py-4">No transaction types data available</p>
          @endif
        </div>
      </x-card>

      <!-- Client Types Breakdown -->
      <x-card title="Client Types Breakdown">
        <div class="p-6">
          @if ($clientTypes->count() > 0)
            <div class="space-y-4">
              @foreach ($clientTypes as $type => $data)
                <div class="flex items-center justify-between">
                  <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                      <span class="text-sm font-medium text-gray-700">{{ $type }}</span>
                      <span class="text-sm text-gray-500">{{ $data['count'] }} ({{ $data['percentage'] }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                      <div class="bg-green-600 h-2 rounded-full" style="width: {{ $data['percentage'] }}%"></div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <p class="text-gray-500 text-center py-4">No client types data available</p>
          @endif
        </div>
      </x-card>
    </div>

    <!-- Top Clients and Services -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <!-- Top Clients -->
      <x-card title="Top Clients by Revenue">
        <div class="p-6">
          @if ($topClients->count() > 0)
            <div class="space-y-4">
              @foreach ($topClients as $client)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                  <div class="flex-1">
                    <h4 class="text-sm font-medium text-gray-900">{{ $client['client_name'] }}</h4>
                    <p class="text-xs text-gray-500">{{ $client['client_type'] }}</p>
                  </div>
                  <div class="text-right">
                    <p class="text-sm font-semibold text-gray-900">@formatCurrency($client['total_revenue'])</p>
                    <p class="text-xs text-gray-500">{{ $client['transaction_count'] }} transactions</p>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <p class="text-gray-500 text-center py-4">No client data available</p>
          @endif
        </div>
      </x-card>

      <!-- Top Services -->
      <x-card title="Top Services by Usage">
        <div class="p-6">
          @if ($topServices->count() > 0)
            <div class="space-y-4">
              @foreach ($topServices as $service)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                  <div class="flex-1">
                    <h4 class="text-sm font-medium text-gray-900">{{ $service['service_name'] }}</h4>
                    <p class="text-xs text-gray-500">Service ID: {{ $service['service_id'] }}</p>
                  </div>
                  <div class="text-right">
                    <p class="text-sm font-semibold text-gray-900">{{ $service['usage_count'] }}</p>
                    <p class="text-xs text-gray-500">@formatCurrency($service['total_revenue'])</p>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <p class="text-gray-500 text-center py-4">No service data available</p>
          @endif
        </div>
      </x-card>
    </div>

    <!-- Status and Charge Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <!-- Status Breakdown -->
      <x-card title="Status Breakdown">
        <div class="p-6">
          @if ($statusBreakdown->count() > 0)
            <div class="space-y-4">
              @foreach ($statusBreakdown as $status => $data)
                <div class="flex items-center justify-between">
                  <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                      <span class="text-sm font-medium text-gray-700">{{ $status }}</span>
                      <span class="text-sm text-gray-500">{{ $data['count'] }} ({{ $data['percentage'] }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                      <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $data['percentage'] }}%"></div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <p class="text-gray-500 text-center py-4">No status data available</p>
          @endif
        </div>
      </x-card>

      <!-- Charge Breakdown -->
      <x-card title="Charge vs Non-Charge">
        <div class="p-6">
          @if ($chargeBreakdown->count() > 0)
            <div class="space-y-4">
              @foreach ($chargeBreakdown as $charge => $data)
                <div class="flex items-center justify-between">
                  <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                      <span class="text-sm font-medium text-gray-700">{{ $charge }}</span>
                      <span class="text-sm text-gray-500">{{ $data['count'] }} ({{ $data['percentage'] }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                      <div class="bg-orange-600 h-2 rounded-full" style="width: {{ $data['percentage'] }}%"></div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <p class="text-gray-500 text-center py-4">No charge data available</p>
          @endif
        </div>
      </x-card>
    </div>

    <!-- Daily Trends Chart -->
    <x-card title="Daily Trends">
      <div class="p-6">
        @if ($dailyTrends->count() > 0)
          <div class="h-64 flex items-end space-x-2">
            @foreach ($dailyTrends as $trend)
              <div class="flex-1 flex flex-col items-center">
                <div class="w-full bg-blue-200 rounded-t" style="height: {{ ($trend['count'] / $dailyTrends->max('count')) * 200 }}px"></div>
                <span class="text-xs text-gray-500 mt-2">{{ \Carbon\Carbon::parse($trend['date'])->format('M d') }}</span>
              </div>
            @endforeach
          </div>
        @else
          <p class="text-gray-500 text-center py-4">No daily trends data available</p>
        @endif
      </div>
    </x-card>
  </div>
@endsection
