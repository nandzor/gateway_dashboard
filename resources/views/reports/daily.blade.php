@extends('layouts.app')

@section('title', 'Daily Reports')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page Header with Export -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Daily Reports</h1>
                <p class="mt-2 text-gray-600">Laporan untuk {{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}</p>
            </div>

            @if ($totalTransactions > 0)
                <div class="mt-4 sm:mt-0">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <x-button variant="secondary" size="md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                Export Laporan
                                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </x-button>
                        </x-slot>

                        <x-dropdown-link :href="route('reports.daily.export', [
                            'date' => $date,
                            'client_id' => $clientId,
                            'service_id' => $serviceId,
                            'format' => 'excel',
                        ])" variant="success">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                            Export ke Excel
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('reports.daily.export', [
                            'date' => $date,
                            'client_id' => $clientId,
                            'service_id' => $serviceId,
                            'format' => 'pdf',
                        ])" variant="danger">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            Export ke PDF
                        </x-dropdown-link>
                    </x-dropdown>
                </div>
            @endif
        </div>

        <!-- Filters -->
        <x-card title="Filter Laporan" class="mb-6">
            <form method="GET">
                <div class="flex flex-col lg:flex-row lg:items-end gap-4">
                    <!-- Filter Fields -->
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <x-input type="date" name="date" label="Pilih Tanggal" :value="$date" />
                        <x-select
                            name="client_id"
                            label="Klien"
                            :selected="$clientId"
                            :options="$clients->pluck('client_name', 'id')->prepend('Semua Klien', '')"
                            placeholder="Pilih Klien"
                        />
                        <x-select
                            name="service_id"
                            label="Layanan"
                            :selected="$serviceId"
                            :options="$services->pluck('name', 'id')->prepend('Semua Layanan', '')"
                            placeholder="Pilih Layanan"
                        />
                    </div>

                    <!-- Action Button -->
                    <x-button type="submit" variant="primary" size="md" class="w-full md:w-auto">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Terapkan Filter
                    </x-button>
                </div>
            </form>
        </x-card>

        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <!-- Total Transactions -->
            <x-stat-card
                title="Total Transaksi"
                :value="App\Helpers\NumberHelper::formatNumber($totalTransactions)"
                icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z' />"
                color="blue"
            />

            <!-- Total Revenue -->
            <x-stat-card
                title="Total Pendapatan"
                :value="App\Helpers\NumberHelper::formatCurrency($totalRevenue)"
                icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z' />"
                color="green"
            />

            <!-- Total Duration -->
            <x-stat-card
                title="Total Durasi"
                :value="App\Helpers\NumberHelper::formatNumber($totalDuration) . ' detik'"
                icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' />"
                color="purple"
            />

            <!-- Unique Users -->
            <x-stat-card
                title="Pengguna Unik"
                :value="App\Helpers\NumberHelper::formatNumber($uniqueUsers)"
                icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z' />"
                color="orange"
            />

            <!-- Unique Clients -->
            <x-stat-card
                title="Klien Unik"
                :value="App\Helpers\NumberHelper::formatNumber($uniqueClients)"
                icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4' />"
                color="indigo"
            />
        </div>

        <!-- Performance Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Average Transaction Value -->
            <x-stat-card
                title="Rata-rata Nilai Transaksi"
                :value="App\Helpers\NumberHelper::formatCurrency($avgTransactionValue)"
                icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z' />"
                color="teal"
            />

            <!-- Success Rate -->
            <x-stat-card
                title="Tingkat Keberhasilan"
                :value="App\Helpers\NumberHelper::formatNumber($successRate, 1) . '%'"
                icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' />"
                color="cyan"
            />

            <!-- Average Duration Per Transaction -->
            <x-stat-card
                title="Rata-rata Durasi/Transaksi"
                :value="App\Helpers\NumberHelper::formatNumber($avgDurationPerTransaction, 1) . ' detik'"
                icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' />"
                color="pink"
            />
        </div>

        <!-- Trend Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Hourly Trends Chart -->
            <x-card title="Tren Per Jam">
                @if ($hourlyTrends->count() > 0)
                    <div class="p-6">
                        <div style="height: 400px;">
                            <canvas id="hourlyTrendsChart"></canvas>
                        </div>
                        <div class="mt-4 flex items-center justify-between text-sm text-gray-600">
                            <span>Jam dengan transaksi terbanyak: {{ $hourlyTrends->sortByDesc('count')->first()['hour'] ?? 'N/A' }}</span>
                            <span>Total: {{ App\Helpers\NumberHelper::formatNumber($hourlyTrends->sum('count')) }} transaksi</span>
                        </div>
                    </div>
                @else
                    <x-empty-state message="Tidak ada data tren per jam" icon="inbox" />
                @endif
            </x-card>

            <!-- Revenue Trends Chart -->
            <x-card title="Tren Pendapatan Per Jam">
                @if ($hourlyTrends->count() > 0)
                    <div class="p-6">
                        <div style="height: 400px;">
                            <canvas id="hourlyRevenueChart"></canvas>
                        </div>
                        <div class="mt-4 flex items-center justify-between text-sm text-gray-600">
                            <span>Jam dengan pendapatan tertinggi: {{ $hourlyTrends->sortByDesc('revenue')->first()['hour'] ?? 'N/A' }}</span>
                            <span>Total: {{ App\Helpers\NumberHelper::formatCurrency($hourlyTrends->sum('revenue')) }}</span>
                        </div>
                    </div>
                @else
                    <x-empty-state message="Tidak ada data tren pendapatan" icon="inbox" />
                @endif
            </x-card>
        </div>

        <!-- Transaction Types and Client Types -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Transaction Types Breakdown -->
            <x-card title="Breakdown Jenis Transaksi">
                @if ($transactionTypes->count() > 0)
                    <div class="space-y-4 p-6">
                        @foreach ($transactionTypes as $type => $data)
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-700">{{ $type }}</span>
                                        <span class="text-sm text-gray-500">{{ App\Helpers\NumberHelper::formatNumber($data['count']) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($data['count'] / $transactionTypes->max('count')) * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-empty-state message="Tidak ada data jenis transaksi" icon="inbox" />
                @endif
            </x-card>

            <!-- Client Types Breakdown -->
            <x-card title="Breakdown Jenis Klien">
                @if ($clientTypes->count() > 0)
                    <div class="space-y-4 p-6">
                        @foreach ($clientTypes as $type => $data)
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-700">{{ $type }}</span>
                                        <span class="text-sm text-gray-500">{{ App\Helpers\NumberHelper::formatNumber($data['count']) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($data['count'] / $clientTypes->max('count')) * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-empty-state message="Tidak ada data jenis klien" icon="users" />
                @endif
            </x-card>
        </div>

        <!-- Top Clients and Services -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Top Clients -->
            <x-card title="Top Klien berdasarkan Pendapatan">
                @if ($topClients->count() > 0)
                    <div class="space-y-4 p-6">
                        @foreach ($topClients as $client)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $client['client_name'] }}</h4>
                                    <p class="text-xs text-gray-500">{{ $client['client_type'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-900">{{ App\Helpers\NumberHelper::formatCurrency($client['total_revenue']) }}</p>
                                    <p class="text-xs text-gray-500">{{ App\Helpers\NumberHelper::formatNumber($client['transaction_count']) }} transaksi</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-empty-state message="Tidak ada data klien" icon="users" />
                @endif
            </x-card>

            <!-- Top Services -->
            <x-card title="Top Layanan berdasarkan Penggunaan">
                @if ($topServices->count() > 0)
                    <div class="space-y-4 p-6">
                        @foreach ($topServices as $service)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $service['service_name'] }}</h4>
                                    <p class="text-xs text-gray-500">ID Layanan: {{ $service['service_id'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-900">{{ App\Helpers\NumberHelper::formatNumber($service['usage_count']) }}</p>
                                    <p class="text-xs text-gray-500">{{ App\Helpers\NumberHelper::formatCurrency($service['total_revenue']) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-empty-state message="Tidak ada data layanan" icon="inbox" />
                @endif
            </x-card>
        </div>

        <!-- Status and Charge Breakdown -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Status Breakdown -->
            <x-card title="Breakdown Status">
                @if ($statusBreakdown->count() > 0)
                    <div class="space-y-4 p-6">
                        @foreach ($statusBreakdown as $status => $data)
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <div class="flex items-center space-x-2">
                                            @if($status === 'success')
                                                <x-badge variant="success" size="sm">Berhasil</x-badge>
                                            @elseif($status === 'failed')
                                                <x-badge variant="danger" size="sm">Gagal</x-badge>
                                            @else
                                                <x-badge variant="warning" size="sm">{{ ucfirst($status) }}</x-badge>
                                            @endif
                                            <span class="text-sm font-medium text-gray-700">{{ $status }}</span>
                                        </div>
                                        <span class="text-sm text-gray-500">{{ App\Helpers\NumberHelper::formatNumber($data['count']) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ ($data['count'] / $statusBreakdown->max('count')) * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-empty-state message="Tidak ada data status" icon="inbox" />
                @endif
            </x-card>

            <!-- Charge Breakdown -->
            <x-card title="Breakdown Biaya vs Non-Biaya">
                @if ($chargeBreakdown->count() > 0)
                    <div class="space-y-4 p-6">
                        @foreach ($chargeBreakdown as $charge => $data)
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <div class="flex items-center space-x-2">
                                            @if($charge === 'Charged')
                                                <x-badge variant="success" size="sm">Dikenai Biaya</x-badge>
                                            @else
                                                <x-badge variant="secondary" size="sm">Tidak Dikenai Biaya</x-badge>
                                            @endif
                                            <span class="text-sm font-medium text-gray-700">{{ $charge }}</span>
                                        </div>
                                        <span class="text-sm text-gray-500">{{ App\Helpers\NumberHelper::formatNumber($data['count']) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-orange-600 h-2 rounded-full" style="width: {{ ($data['count'] / $chargeBreakdown->max('count')) * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-empty-state message="Tidak ada data biaya" icon="inbox" />
                @endif
            </x-card>
        </div>

        <!-- Daily Summary -->
        <x-card title="Ringkasan Harian" class="mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 p-6">
                <!-- Peak Hour -->
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $peakHour ?? 'N/A' }}</h3>
                    <p class="text-sm text-gray-600">Jam Puncak</p>
                </div>

                <!-- Busiest Service -->
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mb-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $busiestService ?? 'N/A' }}</h3>
                    <p class="text-sm text-gray-600">Layanan Terpopuler</p>
                </div>

                <!-- Top Client -->
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-purple-100 rounded-full mb-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $topClient ?? 'N/A' }}</h3>
                    <p class="text-sm text-gray-600">Klien Teraktif</p>
                </div>

                <!-- Success Rate -->
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-orange-100 rounded-full mb-3">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ App\Helpers\NumberHelper::formatNumber($successRate, 1) }}%</h3>
                    <p class="text-sm text-gray-600">Tingkat Keberhasilan</p>
                </div>
            </div>
        </x-card>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hourly Trends Chart
    @if ($hourlyTrends->count() > 0)
    const hourlyCtx = document.getElementById('hourlyTrendsChart').getContext('2d');
    const hourlyData = @json($hourlyTrendsFormatted);

    new Chart(hourlyCtx, {
        type: 'bar',
        data: {
            labels: hourlyData.map(item => item.hour),
            datasets: [{
                label: 'Jumlah Transaksi',
                data: hourlyData.map(item => item.count),
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Tren Transaksi Per Jam',
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    color: '#374151'
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true
                }
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Jam',
                        color: '#6B7280',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Jumlah Transaksi',
                        color: '#6B7280',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }
            }
        }
    });

    // Hourly Revenue Chart
    const revenueCtx = document.getElementById('hourlyRevenueChart').getContext('2d');

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: hourlyData.map(item => item.hour),
            datasets: [{
                label: 'Pendapatan',
                data: hourlyData.map(item => item.revenue),
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgb(34, 197, 94)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Tren Pendapatan Per Jam',
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    color: '#374151'
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20
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
                            return 'Pendapatan: ' + new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                            }).format(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Jam',
                        color: '#6B7280',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Pendapatan (IDR)',
                        color: '#6B7280',
                        font: {
                            weight: 'bold'
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                            }).format(value);
                        }
                    }
                }
            }
        }
    });
    @endif
});
</script>
@endsection
