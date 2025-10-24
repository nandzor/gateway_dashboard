@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
  <div class="max-w-7xl mx-auto">
    <!-- Welcome Banner -->
    <div class="mb-8">
      <div
        class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 rounded-2xl shadow-2xl">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-white opacity-5 rounded-full -ml-20 -mb-20"></div>

        <div class="relative p-8">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold text-white mb-2">Welcome back, {{ auth()->user()->name }}! 👋</h1>
              <p class="text-blue-100">Here's what's happening with your gateway system today.</p>
            </div>
            <div class="hidden md:block">
              <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-4">
                <p class="text-black text-sm">{{ now()->format('l, F j, Y') }}</p>
                <p class="text-black text-2xl font-bold">{{ now()->format('H:i') }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <!-- Users -->
      <x-stat-card title="Total Users" :value="$totalUsers" color="blue" :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z\'/>'" />

      <!-- Services -->
      <x-stat-card title="Total Services" :value="$totalServices" color="green" :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z\'/>'" />

      <!-- Clients -->
      <x-stat-card title="Total Clients" :value="$totalClients" color="purple" :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z\'/>'" />

      <!-- Transactions -->
      <x-stat-card title="Total Transactions" :value="$totalTransactions" color="orange" :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z\'/>'" />
    </div>

    <!-- Revenue Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <!-- Total Revenue -->
      <x-card title="Revenue Statistics">
        <div class="space-y-4">
          <div class="flex justify-between items-center">
            <span class="text-gray-600">Total Revenue:</span>
            <span class="text-2xl font-bold text-green-600">{{ App\Helpers\NumberHelper::formatCurrency($totalRevenue) }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-gray-600">Average Transaction:</span>
            <span class="text-xl font-semibold text-green-500">{{ App\Helpers\NumberHelper::formatCurrency($avgTransactionValue) }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-gray-600">Total Transaction Success:</span>
            <span class="text-xl font-semibold text-gray-700">{{ number_format($totalTransactionSuccess, 0, ',', '.') }}</span>
          </div>
        </div>
      </x-card>

      <!-- System Performance -->
      <x-card title="System Performance">
        <div class="space-y-4">
          <div class="flex justify-between items-center">
            <span class="text-gray-600">Active Users:</span>
            <span class="text-2xl font-bold text-blue-600">{{ App\Helpers\NumberHelper::formatNumber($activeUsers) }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-gray-600">Active Clients:</span>
            <span class="text-xl font-semibold text-purple-500">{{ App\Helpers\NumberHelper::formatNumber($activeClients) }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-gray-600">Production Clients:</span>
            <span class="text-xl font-semibold text-green-600">{{ App\Helpers\NumberHelper::formatNumber($productionClients) }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-gray-600">Staging Clients:</span>
            <span class="text-xl font-semibold text-yellow-600">{{ App\Helpers\NumberHelper::formatNumber($stagingClients) }}</span>
          </div>
        </div>
      </x-card>
    </div>

    <!-- 7-Day Histories Charts -->
    <div class="mb-8">
      <x-card title="7-Day Transaction Analytics" class="mb-6">
        <div class="mb-4">
          <div class="flex items-center justify-between mb-4">
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Transaction Trends</h3>
              <p class="text-sm text-gray-600">Last 7 days: {{ $historiesChartData['statistics']['period'] }}</p>
            </div>
            <div class="flex space-x-4 text-sm">
              <div class="flex items-center">
                <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                <span class="text-gray-600">Transactions</span>
              </div>
              <div class="flex items-center">
                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                <span class="text-gray-600">Revenue</span>
              </div>
              <div class="flex items-center">
                <div class="w-3 h-3 bg-purple-500 rounded-full mr-2"></div>
                <span class="text-gray-600">Success Rate</span>
              </div>
            </div>
          </div>

          <!-- Chart Container -->
          <div class="relative" style="height: 400px;">
            <canvas id="historiesChart"></canvas>
          </div>
        </div>
      </x-card>

      <!-- Chart Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-blue-100 text-sm">Total Transactions</p>
              <p class="text-2xl font-bold">{{ number_format($historiesChartData['statistics']['totalTransactions']) }}</p>
            </div>
            <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-green-100 text-sm">Total Revenue</p>
              <p class="text-2xl font-bold">Rp {{ number_format($historiesChartData['statistics']['totalRevenue'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-purple-100 text-sm">Avg Daily Transactions</p>
              <p class="text-2xl font-bold">{{ $historiesChartData['statistics']['avgDailyTransactions'] }}</p>
            </div>
            <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-4 text-white">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-orange-100 text-sm">Avg Daily Revenue</p>
              <p class="text-2xl font-bold">Rp {{ number_format($historiesChartData['statistics']['avgDailyRevenue'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-orange-400 bg-opacity-30 rounded-full p-3">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Top Services & Clients -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Services -->
        <x-card title="Top Services (7 Days)" :padding="false">
          <div class="p-6">
            @if($historiesChartData['topServices']->count() > 0)
              <div class="space-y-4">
                @foreach($historiesChartData['topServices'] as $index => $service)
                  <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                      <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-blue-600 font-bold text-sm">{{ $index + 1 }}</span>
                      </div>
                      <div>
                        <h4 class="text-sm font-medium text-gray-900">{{ $service->service->name ?? 'Unknown Service' }}</h4>
                        <p class="text-xs text-gray-500">ID: {{ $service->module_id }}</p>
                      </div>
                    </div>
                    <div class="text-right">
                      <p class="text-sm font-semibold text-gray-900">{{ number_format($service->usage_count) }}</p>
                      <p class="text-xs text-gray-500">Rp {{ number_format($service->revenue, 0, ',', '.') }}</p>
                    </div>
                  </div>
                @endforeach
              </div>
            @else
              <div class="text-center py-8 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <p>No service data available</p>
              </div>
            @endif
          </div>
        </x-card>

        <!-- Top Clients -->
        <x-card title="Top Clients (7 Days)" :padding="false">
          <div class="p-6">
            @if($historiesChartData['topClients']->count() > 0)
              <div class="space-y-4">
                @foreach($historiesChartData['topClients'] as $index => $client)
                  <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                      <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-green-600 font-bold text-sm">{{ $index + 1 }}</span>
                      </div>
                      <div>
                        <h4 class="text-sm font-medium text-gray-900">{{ $client->client->client_name ?? 'Client ID: ' . $client->client_id }}</h4>
                        <p class="text-xs text-gray-500">ID: {{ $client->client_id }}</p>
                      </div>
                    </div>
                    <div class="text-right">
                      <p class="text-sm font-semibold text-gray-900">{{ number_format($client->transaction_count) }}</p>
                      <p class="text-xs text-gray-500">Rp {{ number_format($client->revenue, 0, ',', '.') }}</p>
                    </div>
                  </div>
                @endforeach
              </div>
            @else
              <div class="text-center py-8 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p>No client data available</p>
              </div>
            @endif
          </div>
        </x-card>
      </div>
    </div>

    <!-- Recent Data Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <!-- Recent Users -->
      <x-card title="Recent Users" :padding="false">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @forelse($recentUsers as $user)
                <tr class="hover:bg-gray-50">
                  <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">
                      {{ Str::limit($user->name, 20) }}
                    </div>
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                    {{ Str::limit($user->email, 25) }}
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap">
                    <x-badge :variant="$user->is_active ? 'success' : 'danger'">
                      {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </x-badge>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="px-4 py-8 text-center text-gray-400">
                    No users data
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        @if ($recentUsers->count() > 0)
          <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
            <a href="{{ route('users.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
              View all Users →
            </a>
          </div>
        @endif
      </x-card>

      <!-- Recent Services -->
      <x-card title="Recent Services" :padding="false">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @forelse($recentServices as $service)
                <tr class="hover:bg-gray-50">
                  <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">
                      {{ Str::limit($service->name, 20) }}
                    </div>
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                    {{ $service->type ?? 'N/A' }}
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap">
                    <x-badge :variant="$service->is_active ? 'success' : 'danger'">
                      {{ $service->is_active ? 'Active' : 'Inactive' }}
                    </x-badge>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="px-4 py-8 text-center text-gray-400">
                    No services data
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        @if ($recentServices->count() > 0)
          <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
            <a href="{{ route('services.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
              View all Services →
            </a>
          </div>
        @endif
      </x-card>

      <!-- Recent Clients -->
      <x-card title="Recent Clients" :padding="false">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @forelse($recentClients as $client)
                <tr class="hover:bg-gray-50">
                  <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">
                      {{ Str::limit($client->client_name, 20) }}
                    </div>
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                    <x-badge :variant="$client->type_badge_variant">
                      {{ $client->type_name }}
                    </x-badge>
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap">
                    <x-badge :variant="$client->is_active ? 'success' : 'danger'">
                      {{ $client->is_active ? 'Active' : 'Inactive' }}
                    </x-badge>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="px-4 py-8 text-center text-gray-400">
                    No clients data
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        @if ($recentClients->count() > 0)
          <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
            <a href="{{ route('clients.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
              View all Clients →
            </a>
          </div>
        @endif
      </x-card>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
      <x-card title="Quick Actions">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <a href="{{ route('users.index') }}"
            class="flex flex-col items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-colors">
            <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
            </svg>
            <span class="text-sm font-medium text-gray-700">Manage Users</span>
          </a>

          <a href="{{ route('services.index') }}"
            class="flex flex-col items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition-colors">
            <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <span class="text-sm font-medium text-gray-700">Manage Services</span>
          </a>

          <a href="{{ route('clients.index') }}"
            class="flex flex-col items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-colors">
            <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="text-sm font-medium text-gray-700">Manage Clients</span>
          </a>

          <a href="{{ route('analytics.index') }}"
            class="flex flex-col items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition-colors">
            <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span class="text-sm font-medium text-gray-700">View Analytics</span>
          </a>
        </div>
      </x-card>
    </div>
  </div>

  <!-- Chart.js CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    // Histories Chart
    const ctx = document.getElementById('historiesChart').getContext('2d');
    const chartData = @json($historiesChartData);

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: chartData.labels,
        datasets: [
          {
            label: 'Transactions',
            data: chartData.transactions,
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: 'rgb(59, 130, 246)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 6,
            pointHoverRadius: 8,
            yAxisID: 'y'
          },
          {
            label: 'Revenue (Rp)',
            data: chartData.revenue,
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            borderWidth: 3,
            fill: false,
            tension: 0.4,
            pointBackgroundColor: 'rgb(34, 197, 94)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 6,
            pointHoverRadius: 8,
            yAxisID: 'y1'
          },
          {
            label: 'Success Rate (%)',
            data: chartData.successRate,
            borderColor: 'rgb(147, 51, 234)',
            backgroundColor: 'rgba(147, 51, 234, 0.1)',
            borderWidth: 3,
            fill: false,
            tension: 0.4,
            pointBackgroundColor: 'rgb(147, 51, 234)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 6,
            pointHoverRadius: 8,
            yAxisID: 'y2'
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
          mode: 'index',
          intersect: false,
        },
        plugins: {
          title: {
            display: true,
            text: '7-Day Transaction Analytics',
            font: {
              size: 18,
              weight: 'bold'
            },
            color: '#374151',
            padding: 20
          },
          legend: {
            display: true,
            position: 'top',
            labels: {
              usePointStyle: true,
              padding: 20,
              font: {
                size: 12,
                weight: '500'
              }
            }
          },
          tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleColor: '#fff',
            bodyColor: '#fff',
            borderColor: 'rgba(255, 255, 255, 0.1)',
            borderWidth: 1,
            cornerRadius: 8,
            displayColors: true,
            callbacks: {
              label: function(context) {
                let label = context.dataset.label || '';
                if (label) {
                  label += ': ';
                }
                if (context.dataset.label === 'Revenue (Rp)') {
                  label += new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                  }).format(context.parsed.y);
                } else if (context.dataset.label === 'Success Rate (%)') {
                  label += context.parsed.y + '%';
                } else {
                  label += new Intl.NumberFormat('id-ID').format(context.parsed.y);
                }
                return label;
              }
            }
          }
        },
        scales: {
          x: {
            display: true,
            title: {
              display: true,
              text: 'Date',
              color: '#6B7280',
              font: {
                weight: 'bold',
                size: 12
              }
            },
            grid: {
              color: 'rgba(0, 0, 0, 0.05)'
            },
            ticks: {
              color: '#6B7280',
              font: {
                size: 11
              }
            }
          },
          y: {
            type: 'linear',
            display: true,
            position: 'left',
            title: {
              display: true,
              text: 'Transactions',
              color: '#3B82F6',
              font: {
                weight: 'bold',
                size: 12
              }
            },
            grid: {
              color: 'rgba(59, 130, 246, 0.1)'
            },
            ticks: {
              color: '#3B82F6',
              font: {
                size: 11
              }
            }
          },
          y1: {
            type: 'linear',
            display: true,
            position: 'right',
            title: {
              display: true,
              text: 'Revenue (Rp)',
              color: '#22C55E',
              font: {
                weight: 'bold',
                size: 12
              }
            },
            grid: {
              drawOnChartArea: false,
            },
            ticks: {
              color: '#22C55E',
              font: {
                size: 11
              },
              callback: function(value) {
                return new Intl.NumberFormat('id-ID', {
                  style: 'currency',
                  currency: 'IDR',
                  minimumFractionDigits: 0
                }).format(value);
              }
            }
          },
          y2: {
            type: 'linear',
            display: false,
            min: 0,
            max: 100
          }
        },
        elements: {
          point: {
            hoverBackgroundColor: '#fff',
            hoverBorderWidth: 3
          }
        }
      }
    });
  });
  </script>
@endsection
