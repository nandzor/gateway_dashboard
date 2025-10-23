@extends('layouts.app')

@section('title', 'Edit Topup Saldo #' . $balanceTopup->id)
@section('page-title', 'Edit Topup Saldo #' . $balanceTopup->id)

@section('content')
<div class="max-w-3xl">
    <!-- Header -->
    <x-card class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Topup Saldo #{{ $balanceTopup->id }}</h1>
                <p class="text-gray-600 mt-1">Edit informasi topup saldo</p>
            </div>
            <div class="flex space-x-2">
                <x-button variant="primary" :href="route('balance-topups.show', $balanceTopup)" icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z' />">
                    Lihat
                </x-button>
                <x-button variant="secondary" :href="route('balance-topups.index')" icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10 19l-7-7m0 0l7-7m-7 7h18' />">
                    Kembali
                </x-button>
            </div>
        </div>
    </x-card>

    <x-card>
        <form method="POST" action="{{ route('balance-topups.update', $balanceTopup) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Client Information (Read-only) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Klien</label>
                    <div class="p-3 bg-gray-50 rounded-md">
                        <p class="text-sm text-gray-900">{{ $balanceTopup->client->client_name }} ({{ $balanceTopup->client->type == 1 ? 'Prepaid' : 'Postpaid' }})</p>
                    </div>
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
                        :value="old('amount', $balanceTopup->amount)"
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
                    :selected="old('payment_method', $balanceTopup->payment_method)"
                    placeholder="Pilih Metode"
                    required
                />

                <!-- Reference Number -->
                <x-input
                    label="Nomor Referensi"
                    name="reference_number"
                    :value="old('reference_number', $balanceTopup->reference_number)"
                    placeholder="Nomor referensi pembayaran"
                    icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' />"
                />

                <!-- Notes -->
                <div class="md:col-span-2">
                    <x-textarea
                        label="Catatan"
                        name="notes"
                        :value="old('notes', $balanceTopup->notes)"
                        placeholder="Catatan tambahan (opsional)"
                        :rows="3"
                    />
                </div>
            </div>

            <!-- Current Balance Info -->
            <div class="mt-6 p-4 bg-blue-50 rounded-md">
                <h4 class="text-sm font-medium text-blue-900 mb-2">Informasi Saldo Saat Ini</h4>
                <div class="grid grid-cols-1 gap-2 md:grid-cols-3 text-sm">
                    <div>
                        <span class="text-blue-700">Saldo Sebelum:</span>
                        <span class="font-semibold text-blue-900">{{ App\Helpers\NumberHelper::formatCurrency($balanceTopup->previous_balance) }}</span>
                    </div>
                    <div>
                        <span class="text-blue-700">Jumlah Topup:</span>
                        <span class="font-semibold text-blue-900">{{ App\Helpers\NumberHelper::formatCurrency($balanceTopup->amount) }}</span>
                    </div>
                    <div>
                        <span class="text-blue-700">Saldo Setelah:</span>
                        <span class="font-semibold text-blue-900">{{ App\Helpers\NumberHelper::formatCurrency($balanceTopup->new_balance) }}</span>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 flex justify-end space-x-3">
                <x-button variant="outline" :href="route('balance-topups.show', $balanceTopup)">
                    Batal
                </x-button>
                <x-button type="submit" variant="primary" icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7' />">
                    Simpan Perubahan
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

    // Update new balance preview
    document.getElementById('amount').addEventListener('input', function(e) {
        const amount = parseFloat(e.target.value) || 0;
        const previousBalance = {{ $balanceTopup->previous_balance }};
        const newBalance = previousBalance + amount;

        // Update the preview in the info box
        const newBalanceElement = document.querySelector('.text-blue-900.font-semibold:last-child');
        if (newBalanceElement) {
            newBalanceElement.textContent = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(newBalance);
        }
    });
</script>
@endsection
