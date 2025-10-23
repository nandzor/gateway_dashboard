@extends('layouts.app')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Analytics Dashboard</h1>
            <p class="mt-2 text-sm text-gray-600">Overview of your gateway system performance and statistics</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Clients -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Clients</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format($totalClients) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-green-600 font-medium">{{ number_format($activeClients) }}</span>
                        <span class="text-gray-500">active</span>
                    </div>
                </div>
            </div>

            <!-- Total Services -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Services</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format($totalServices) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-green-600 font-medium">{{ number_format($activeServices) }}</span>
                        <span class="text-gray-500">active</span>
                    </div>
                </div>
            </div>

            <!-- Total Transactions -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Transactions</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ number_format($totalTransactions) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-green-600 font-medium">{{ number_format($successfulTransactions) }}</span>
                        <span class="text-gray-500">successful</span>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                                <dd class="text-lg font-medium text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-5 py-3">
                    <div class="text-sm">
                        <span class="text-green-600 font-medium">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</span>
                        <span class="text-gray-500">today</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Status Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Transaction Status -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Transaction Status</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">Successful</span>
                            <div class="flex items-center">
                                <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $totalTransactions > 0 ? ($successfulTransactions / $totalTransactions) * 100 : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ number_format($successfulTransactions) }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">Failed</span>
                            <div class="flex items-center">
                                <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="bg-red-600 h-2 rounded-full" style="width: {{ $totalTransactions > 0 ? ($failedTransactions / $totalTransactions) * 100 : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ number_format($failedTransactions) }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">Pending</span>
                            <div class="flex items-center">
                                <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $totalTransactions > 0 ? ($pendingTransactions / $totalTransactions) * 100 : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ number_format($pendingTransactions) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Overview -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Revenue Overview</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">Today</span>
                            <span class="text-lg font-semibold text-gray-900">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">This Month</span>
                            <span class="text-lg font-semibold text-gray-900">Rp {{ number_format($thisMonthRevenue, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">Total</span>
                            <span class="text-lg font-semibold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions and Top Clients -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Transactions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Transactions</h3>
                    <div class="flow-root">
                        <ul class="-my-5 divide-y divide-gray-200">
                            @forelse($recentTransactions as $transaction)
                            <li class="py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full {{ $transaction->status === 'success' ? 'bg-green-100' : ($transaction->status === 'failed' ? 'bg-red-100' : 'bg-yellow-100') }}">
                                            <span class="text-xs font-medium {{ $transaction->status === 'success' ? 'text-green-800' : ($transaction->status === 'failed' ? 'text-red-800' : 'text-yellow-800') }}">
                                                {{ strtoupper(substr($transaction->status, 0, 1)) }}
                                            </span>
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $transaction->client->client_name ?? 'Unknown Client' }}
                                        </p>
                                        <p class="text-sm text-gray-500 truncate">
                                            {{ $transaction->service->name ?? 'Unknown Service' }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0 text-right">
                                        <p class="text-sm font-medium text-gray-900">
                                            Rp {{ number_format($transaction->price, 0, ',', '.') }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $transaction->trx_date ? $transaction->trx_date->format('M d, H:i') : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                            @empty
                            <li class="py-4 text-center text-gray-500">
                                No recent transactions found.
                            </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Top Clients -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Top Clients by Transactions</h3>
                    <div class="flow-root">
                        <ul class="-my-5 divide-y divide-gray-200">
                            @forelse($topClients as $client)
                            <li class="py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100">
                                            <span class="text-xs font-medium text-blue-800">
                                                {{ $loop->iteration }}
                                            </span>
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $client->client->client_name ?? 'Unknown Client' }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ number_format($client->transaction_count) }} transactions
                                        </p>
                                    </div>
                                </div>
                            </li>
                            @empty
                            <li class="py-4 text-center text-gray-500">
                                No client data available.
                            </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
