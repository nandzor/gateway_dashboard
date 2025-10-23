@extends('layouts.app')

@section('title', 'Detail Topup Saldo #' . $balanceTopup->id)
@section('page-title', 'Detail Topup Saldo #' . $balanceTopup->id)

@section('content')
<div class="max-w-4xl">
    <!-- Header -->
    <x-card class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Topup Saldo #{{ $balanceTopup->id }}</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap topup saldo</p>
            </div>
            <div class="flex space-x-2">
                <x-button variant="warning" :href="route('balance-topups.edit', $balanceTopup)" icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' />">
                    Edit
                </x-button>
                <x-button variant="secondary" :href="route('balance-topups.index')" icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10 19l-7-7m0 0l7-7m-7 7h18' />">
                    Kembali
                </x-button>
            </div>
        </div>
    </x-card>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Main Information -->
        <div class="lg:col-span-2">
            <x-card class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Topup</h3>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">ID Topup</label>
                                <p class="mt-1 text-sm text-gray-900">#{{ $balanceTopup->id }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <div class="mt-1">
                                    <x-badge :variant="$balanceTopup->status_color">
                                        {{ $balanceTopup->status_label }}
                                    </x-badge>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jumlah Topup</label>
                                <p class="mt-1 text-sm text-gray-900 font-semibold">{{ App\Helpers\NumberHelper::formatCurrency($balanceTopup->amount) }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $balanceTopup->payment_method_label }}</p>
                            </div>

                            @if($balanceTopup->reference_number)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nomor Referensi</label>
                                    <p class="mt-1 text-sm text-gray-900 font-mono">{{ $balanceTopup->reference_number }}</p>
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Dibuat</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $balanceTopup->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>

                            @if($balanceTopup->processed_at)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tanggal Diproses</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $balanceTopup->processed_at->format('d/m/Y H:i:s') }}</p>
                                </div>
                            @endif

                            @if($balanceTopup->user)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Diproses Oleh</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $balanceTopup->user->name }}</p>
                                </div>
                            @endif
                        </div>

                        @if($balanceTopup->notes)
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Catatan</label>
                                <div class="mt-1 p-3 bg-gray-50 rounded-md">
                                    <p class="text-sm text-gray-900 whitespace-pre-line">{{ $balanceTopup->notes }}</p>
                                </div>
                            </div>
                        @endif
                    </x-card>

                    <!-- Client Information -->
                    <x-card>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Klien</h3>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Klien</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $balanceTopup->client->client_name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tipe Klien</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $balanceTopup->client->type == 1 ? 'Prepaid' : 'Postpaid' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status Klien</label>
                                <div class="mt-1">
                                    <x-badge :variant="$balanceTopup->client->is_active ? 'success' : 'danger'">
                                        {{ $balanceTopup->client->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </x-badge>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Bergabung</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $balanceTopup->client->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </x-card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Balance Information -->
                    <x-card>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Saldo</h3>

                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Saldo Sebelum</label>
                                <p class="mt-1 text-sm text-gray-900">{{ App\Helpers\NumberHelper::formatCurrency($balanceTopup->previous_balance) }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jumlah Topup</label>
                                <p class="mt-1 text-sm text-green-600 font-semibold">+ {{ App\Helpers\NumberHelper::formatCurrency($balanceTopup->amount) }}</p>
                            </div>

                            <div class="border-t pt-3">
                                <label class="block text-sm font-medium text-gray-700">Saldo Setelah</label>
                                <p class="mt-1 text-lg text-gray-900 font-bold">{{ App\Helpers\NumberHelper::formatCurrency($balanceTopup->new_balance) }}</p>
                            </div>
                        </div>
                    </x-card>

        <!-- Actions -->
        @if($balanceTopup->isPending())
            <x-card>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi</h3>

                <div class="space-y-3">
                    <form method="POST" action="{{ route('balance-topups.approve', $balanceTopup) }}">
                        @csrf
                        <x-button type="submit" variant="success" class="w-full"
                                onclick="return confirm('Setujui topup ini?')"
                                icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7' />">
                            Setujui Topup
                        </x-button>
                    </form>

                    <x-button type="button" variant="danger" class="w-full"
                            onclick="showRejectModal()"
                            icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12' />">
                        Tolak Topup
                    </x-button>

                    <x-button type="button" variant="secondary" class="w-full"
                            onclick="showCancelModal()"
                            icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' />">
                        Batalkan Topup
                    </x-button>
                </div>
            </x-card>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<x-modal id="rejectModal" title="Tolak Topup">
    <form method="POST" action="{{ route('balance-topups.reject', $balanceTopup) }}">
        @csrf
        <x-textarea
            label="Alasan Penolakan"
            name="reason"
            required
            placeholder="Masukkan alasan penolakan..."
            :rows="3"
        />
        <div class="flex justify-end space-x-2 mt-4">
            <x-button type="button" variant="outline" onclick="closeRejectModal()">
                Batal
            </x-button>
            <x-button type="submit" variant="danger">
                Tolak Topup
            </x-button>
        </div>
    </form>
</x-modal>

<!-- Cancel Modal -->
<x-modal id="cancelModal" title="Batalkan Topup">
    <form method="POST" action="{{ route('balance-topups.cancel', $balanceTopup) }}">
        @csrf
        <x-textarea
            label="Alasan Pembatalan"
            name="reason"
            required
            placeholder="Masukkan alasan pembatalan..."
            :rows="3"
        />
        <div class="flex justify-end space-x-2 mt-4">
            <x-button type="button" variant="outline" onclick="closeCancelModal()">
                Batal
            </x-button>
            <x-button type="submit" variant="secondary">
                Batalkan Topup
            </x-button>
        </div>
    </form>
</x-modal>

<script>
    function showRejectModal() {
        window.dispatchEvent(new CustomEvent('open-modal-rejectModal'));
    }

    function closeRejectModal() {
        window.dispatchEvent(new CustomEvent('close-modal-rejectModal'));
        document.getElementById('reason').value = '';
    }

    function showCancelModal() {
        window.dispatchEvent(new CustomEvent('open-modal-cancelModal'));
    }

    function closeCancelModal() {
        window.dispatchEvent(new CustomEvent('close-modal-cancelModal'));
        document.getElementById('cancelReason').value = '';
    }
</script>
@endsection
