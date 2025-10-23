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
            <x-stat-card
                title="Total Clients"
                :value="number_format($totalClients, 0, ',', '.')"
                icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z' />"
                color="blue"
                :trend="number_format($activeClients, 0, ',', '.') . ' aktif'"
                :trend-up="true"
            />

            <!-- Total Services -->
            <x-stat-card
                title="Total Services"
                :value="number_format($totalServices, 0, ',', '.')"
                icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' />"
                color="green"
                :trend="number_format($activeServices, 0, ',', '.') . ' aktif'"
                :trend-up="true"
            />

            <!-- Total Transactions -->
            <x-stat-card
                title="Total Transactions"
                :value="number_format($totalTransactions, 0, ',', '.')"
                icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z' />"
                color="purple"
                :trend="number_format($successfulTransactions, 0, ',', '.') . ' berhasil'"
                :trend-up="true"
            />

            <!-- Total Revenue -->
            <x-stat-card
                title="Total Revenue"
                :value="'Rp ' . number_format($totalRevenue, 0, ',', '.')"
                icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1' />"
                color="orange"
                :trend="'Rp ' . number_format($todayRevenue, 0, ',', '.') . ' hari ini'"
                :trend-up="true"
            />
        </div>

        <!-- Transaction Status Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Transaction Status -->
            <x-card title="Transaction Status">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <x-badge variant="success" size="sm">Berhasil</x-badge>
                            <span class="text-sm font-medium text-gray-500">{{ number_format($successfulTransactions, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $totalTransactions > 0 ? ($successfulTransactions / $totalTransactions) * 100 : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $totalTransactions > 0 ? number_format(($successfulTransactions / $totalTransactions) * 100, 1) : 0 }}%</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <x-badge variant="danger" size="sm">Gagal</x-badge>
                            <span class="text-sm font-medium text-gray-500">{{ number_format($failedTransactions, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                <div class="bg-red-600 h-2 rounded-full" style="width: {{ $totalTransactions > 0 ? ($failedTransactions / $totalTransactions) * 100 : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $totalTransactions > 0 ? number_format(($failedTransactions / $totalTransactions) * 100, 1) : 0 }}%</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <x-badge variant="warning" size="sm">Pending</x-badge>
                            <span class="text-sm font-medium text-gray-500">{{ number_format($pendingTransactions, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $totalTransactions > 0 ? ($pendingTransactions / $totalTransactions) * 100 : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $totalTransactions > 0 ? number_format(($pendingTransactions / $totalTransactions) * 100, 1) : 0 }}%</span>
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Revenue Overview -->
            <x-card title="Revenue Overview">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500">Hari Ini</span>
                        <span class="text-lg font-semibold text-gray-900">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500">Bulan Ini</span>
                        <span class="text-lg font-semibold text-gray-900">Rp {{ number_format($thisMonthRevenue, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500">Total</span>
                        <span class="text-lg font-semibold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Recent Transactions and Top Clients -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Transactions -->
            <x-card title="Recent Transactions">
                <div class="flow-root">
                    <ul class="-my-5 divide-y divide-gray-200">
                        @forelse($recentTransactions as $transaction)
                        <li class="py-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    @if($transaction->status === 'success')
                                        <x-badge variant="success" size="sm">{{ strtoupper(substr($transaction->status, 0, 1)) }}</x-badge>
                                    @elseif($transaction->status === 'failed')
                                        <x-badge variant="danger" size="sm">{{ strtoupper(substr($transaction->status, 0, 1)) }}</x-badge>
                                    @else
                                        <x-badge variant="warning" size="sm">{{ strtoupper(substr($transaction->status, 0, 1)) }}</x-badge>
                                    @endif
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
                                        {{ $transaction->trx_date ? $transaction->trx_date->format('d M Y, H:i') : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </li>
                        @empty
                        <x-empty-state message="Tidak ada transaksi terbaru" icon="inbox" />
                        @endforelse
                    </ul>
                </div>
            </x-card>

            <!-- Top Clients -->
            <x-card title="Top Clients by Transactions">
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
                                        {{ $client['client_name'] ?? 'Unknown Client' }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ number_format($client['transaction_count'], 0, ',', '.') }} transaksi
                                    </p>
                                </div>
                                <div class="flex-shrink-0 text-right">
                                    <p class="text-sm font-medium text-gray-900">
                                        Rp {{ number_format($client['total_revenue'], 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ number_format($client['total_duration'], 0, ',', '.') }} detik
                                    </p>
                                </div>
                            </div>
                        </li>
                        @empty
                        <x-empty-state message="Tidak ada data klien" icon="users" />
                        @endforelse
                    </ul>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
