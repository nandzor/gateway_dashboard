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
                <p class="text-white text-sm">{{ now()->format('l, F j, Y') }}</p>
                <p class="text-white text-2xl font-bold">{{ now()->format('H:i') }}</p>
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
            <span class="text-gray-600">Total Transactions:</span>
            <span class="text-xl font-semibold text-gray-700">{{ App\Helpers\NumberHelper::formatNumber($totalTransactions) }}</span>
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
@endsection
