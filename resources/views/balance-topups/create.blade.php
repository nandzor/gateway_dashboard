@extends('layouts.app')

@section('title', 'Tambah Topup Saldo')
@section('page-title', 'Tambah Topup Saldo')

@section('content')
<div class="max-w-3xl">
    <!-- Header -->
    <x-card class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Topup Saldo</h1>
                <p class="text-gray-600 mt-1">Tambah topup saldo baru untuk klien</p>
            </div>
            <x-button variant="secondary" :href="route('balance-topups.index')" icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10 19l-7-7m0 0l7-7m-7 7h18' />">
                Kembali
            </x-button>
        </div>
    </x-card>

    <x-card>
        <form method="POST" action="{{ route('balance-topups.store') }}">
            @csrf

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Client Selection -->
                <div class="md:col-span-2">
                    <x-select
                        label="Klien"
                        name="client_id"
                        :options="collect($clients)->mapWithKeys(function($client) {
                            return [$client->id => $client->client_name . ' (' . ($client->type == 1 ? 'Prepaid' : 'Postpaid') . ')'];
                        })->toArray()"
                        :selected="old('client_id', $selectedClientId)"
                        placeholder="Pilih Klien"
                        :disabled="$isClientDisabled"
                        required
                    />
                    @if($isClientDisabled)
                        <p class="mt-1 text-sm text-red-600">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Klien yang dipilih tidak aktif atau tidak ditemukan.
                        </p>
                    @endif
                </div>

                <!-- Amount -->
                <div>
                    <x-input
                        label="Jumlah Topup"
                        name="amount"
                        type="number"
                        step="0.01"
                        min="0.01"
                        placeholder="0.00"
                        required
                        icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z' />"
                    />
                </div>

                <!-- Payment Method -->
                <x-select
                    label="Metode Pembayaran"
                    name="payment_method"
                    :options="[
                        'cash' => 'Tunai',
                        'transfer' => 'Transfer Bank',
                        'credit_card' => 'Kartu Kredit',
                        'debit_card' => 'Kartu Debit',
                        'e_wallet' => 'E-Wallet',
                        'other' => 'Lainnya'
                    ]"
                    placeholder="Pilih Metode"
                    required
                />

                <!-- Reference Number -->
                <x-input
                    label="Nomor Referensi"
                    name="reference_number"
                    placeholder="Nomor referensi pembayaran"
                    icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' />"
                />

                <!-- Notes -->
                <div class="md:col-span-2">
                    <x-textarea
                        label="Catatan"
                        name="notes"
                        placeholder="Catatan tambahan (opsional)"
                        :rows="3"
                    />
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 flex justify-end space-x-3">
                <x-button variant="outline" :href="route('balance-topups.index')">
                    Batal
                </x-button>
                <x-button type="submit" variant="primary" icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7' />">
                    Simpan Topup
                </x-button>
            </div>
        </form>
    </x-card>
</div>

<script>
    // Format currency input
    document.getElementById('amount').addEventListener('input', function(e) {
        let value = e.target.value;
        if (value && !isNaN(value)) {
            // Keep only 2 decimal places
            if (value.includes('.')) {
                let parts = value.split('.');
                if (parts[1] && parts[1].length > 2) {
                    parts[1] = parts[1].substring(0, 2);
                    e.target.value = parts.join('.');
                }
            }
        }
    });
</script>
@endsection
