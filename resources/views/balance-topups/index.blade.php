@extends('layouts.app')

@section('title', 'Balance Topups')
@section('page-title', 'Manajemen Topup Saldo')

@section('content')
<div class="max-w-7xl">
  <!-- Header -->
  <x-card class="mb-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Topup Saldo</h1>
        <p class="text-gray-600 mt-1">Kelola topup saldo klien dan persetujuan pembayaran</p>
      </div>
      <x-button variant="primary" :href="route('balance-topups.create')"
        icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4' />">
        Tambah Topup
      </x-button>
    </div>
  </x-card>

  <!-- Statistics Cards -->
  <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-4">
    <x-card>
      <div class="flex items-center">
        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div class="ml-4">
          <p class="text-sm font-medium text-gray-600">Total Topup</p>
          <p class="text-2xl font-bold text-gray-900">{{ App\Helpers\NumberHelper::formatNumber($topups->total()) }}</p>
        </div>
      </div>
    </x-card>

    <x-card>
      <div class="flex items-center">
        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div class="ml-4">
          <p class="text-sm font-medium text-gray-600">Menunggu Persetujuan</p>
          <p class="text-2xl font-bold text-gray-900">{{ App\Helpers\NumberHelper::formatNumber($topups->where('status',
            'pending')->count()) }}</p>
        </div>
      </div>
    </x-card>

    <x-card>
      <div class="flex items-center">
        <div class="p-3 rounded-full bg-green-100 text-green-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div class="ml-4">
          <p class="text-sm font-medium text-gray-600">Disetujui</p>
          <p class="text-2xl font-bold text-gray-900">{{ App\Helpers\NumberHelper::formatNumber($topups->where('status',
            'approved')->count()) }}</p>
        </div>
      </div>
    </x-card>

    <x-card>
      <div class="flex items-center">
        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div class="ml-4">
          <p class="text-sm font-medium text-gray-600">Total Nilai</p>
          <p class="text-2xl font-bold text-gray-900">{{
            App\Helpers\NumberHelper::formatCurrency($topups->where('status', 'approved')->sum('amount')) }}</p>
        </div>
      </div>
    </x-card>
  </div>


  <!-- Search and Filters -->
  <x-card class="mb-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
      <!-- Search -->
      <div class="flex-1 max-w-md">
        <form method="GET" action="{{ route('balance-topups.index') }}" class="flex">
          <x-input
            name="search"
            :value="request('search')"
            placeholder="Search by client name, amount, or reference number..."
            class="rounded-r-none border-r-0"
            id="searchInput"
          />
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="hidden" name="client_id" value="{{ request('client_id') }}">
                    <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">
          <button type="submit" id="searchButton"
            class="px-6 py-2 bg-gray-600 text-white rounded-r-lg hover:bg-gray-700 transition-colors flex items-center">
            <span id="searchText">Search</span>
            <svg id="searchSpinner" class="hidden animate-spin ml-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </button>
        </form>
      </div>

      <!-- Filters and Actions -->
      <div class="flex items-center space-x-2">
        <!-- Status Filter -->
        <form method="GET" action="{{ route('balance-topups.index') }}" class="flex items-center">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="client_id" value="{{ request('client_id') }}">
                    <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">
          <select name="status" onchange="this.form.submit()"
            class="text-sm border border-gray-300 rounded-lg px-4 py-2 bg-white hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors min-w-[140px]">
            <option value="">All Status</option>
            <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
            <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>Disetujui</option>
            <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>Ditolak</option>
            <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Dibatalkan</option>
          </select>
        </form>

        <!-- Client Filter -->
        <form method="GET" action="{{ route('balance-topups.index') }}" class="flex items-center">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">
          <select name="client_id" onchange="this.form.submit()"
            class="text-sm border border-gray-300 rounded-lg px-4 py-2 bg-white hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors min-w-[140px]">
            <option value="">All Clients</option>
            @foreach($clients as $client)
            <option value="{{ $client->id }}" {{ request('client_id')==$client->id ? 'selected' : '' }}>
              {{ $client->client_name }} ({{ $client->type == 1 ? 'Prepaid' : 'Postpaid' }})
            </option>
            @endforeach
          </select>
        </form>

        <!-- Payment Method Filter -->
        <form method="GET" action="{{ route('balance-topups.index') }}" class="flex items-center">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="hidden" name="client_id" value="{{ request('client_id') }}">
          <select name="payment_method" onchange="this.form.submit()"
            class="text-sm border border-gray-300 rounded-lg px-4 py-2 bg-white hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors min-w-[140px]">
            <option value="">All Methods</option>
            <option value="cash" {{ request('payment_method')=='cash' ? 'selected' : '' }}>Tunai</option>
            <option value="transfer" {{ request('payment_method')=='transfer' ? 'selected' : '' }}>Transfer Bank
            </option>
            <option value="credit_card" {{ request('payment_method')=='credit_card' ? 'selected' : '' }}>Kartu Kredit
            </option>
            <option value="debit_card" {{ request('payment_method')=='debit_card' ? 'selected' : '' }}>Kartu Debit
            </option>
            <option value="e_wallet" {{ request('payment_method')=='e_wallet' ? 'selected' : '' }}>E-Wallet</option>
            <option value="other" {{ request('payment_method')=='other' ? 'selected' : '' }}>Lainnya</option>
          </select>
        </form>

      </div>
    </div>
  </x-card>

  <!-- Topups Table -->
  <x-card>
    @if($topups->count() > 0)
    <x-table :headers="['ID', 'Klien', 'Jumlah', 'Metode Pembayaran', 'Status', 'Tanggal', 'Aksi']">
      @foreach($topups as $topup)
      <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
          #{{ $topup->id }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
          <div class="text-sm font-medium text-gray-900">{{ $topup->client->client_name }}</div>
          <div class="text-sm text-gray-500">{{ $topup->client->type == 1 ? 'Prepaid' : 'Postpaid' }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
          {{ App\Helpers\NumberHelper::formatCurrency($topup->amount) }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
          {{ $topup->payment_method_label }}
          @if($topup->reference_number)
          <div class="text-xs text-gray-500">Ref: {{ $topup->reference_number }}</div>
          @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
          <x-badge :variant="$topup->status_color">
            {{ $topup->status_label }}
          </x-badge>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
          {{ $topup->created_at->format('d/m/Y H:i') }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
          <div class="flex space-x-2">
            <x-button variant="outline" size="sm" :href="route('balance-topups.show', $topup)">
              Lihat
            </x-button>

            @if($topup->isPending())
            <x-button variant="warning" size="sm" :href="route('balance-topups.edit', $topup)">
              Edit
            </x-button>

            <form method="POST" action="{{ route('balance-topups.approve', $topup) }}" class="inline">
              @csrf
              <x-button type="submit" variant="success" size="sm" onclick="return confirm('Setujui topup ini?')">
                Setujui
              </x-button>
            </form>

            <x-button type="button" variant="danger" size="sm" onclick="showRejectModal({{ $topup->id }})">
              Tolak
            </x-button>
            @endif
          </div>
        </td>
      </tr>
      @endforeach
    </x-table>

    <!-- Pagination -->
    <div class="mt-6">
      {{ $topups->links() }}
    </div>
    @else
    <x-empty-state title="Tidak ada topup" description="Belum ada topup saldo yang ditemukan."
      icon="<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z' />" />
    @endif
  </x-card>
</div>

<!-- Reject Modal -->
<x-modal id="rejectModal" title="Tolak Topup">
  <form method="POST" id="rejectForm">
    @csrf
    <x-textarea label="Alasan Penolakan" name="reason" required placeholder="Masukkan alasan penolakan..." :rows="3" />
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

<script>
  function showRejectModal(topupId) {
        document.getElementById('rejectForm').action = `/balance-topups/${topupId}/reject`;
        window.dispatchEvent(new CustomEvent('open-modal-rejectModal'));
    }

    function closeRejectModal() {
        window.dispatchEvent(new CustomEvent('close-modal-rejectModal'));
        document.getElementById('reason').value = '';
    }



    // Auto-submit form when filters change
    document.addEventListener('DOMContentLoaded', function() {
        const filterInputs = document.querySelectorAll('select[name="status"], select[name="client_id"], select[name="payment_method"]');
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                this.form.submit();
            });
        });

        // Search functionality with loading indicator
        const searchInput = document.querySelector('input[name="search"]');
        const searchButton = document.getElementById('searchButton');
        const searchText = document.getElementById('searchText');
        const searchSpinner = document.getElementById('searchSpinner');

        if (searchInput && searchButton) {
            // Show loading indicator
            function showLoading() {
                searchText.textContent = 'Searching...';
                searchSpinner.classList.remove('hidden');
                searchButton.disabled = true;
            }

            // Hide loading indicator
            function hideLoading() {
                searchText.textContent = 'Search';
                searchSpinner.classList.add('hidden');
                searchButton.disabled = false;
            }

            // Auto-submit search form on Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    showLoading();
                    this.form.submit();
                }
            });

            // Auto-submit search form when input changes (with debounce)
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (this.value.trim() !== '') {
                        showLoading();
                        this.form.submit();
                    }
                }, 800); // 800ms delay for better UX
            });

            // Handle form submit
            searchInput.form.addEventListener('submit', function() {
                showLoading();
            });

            // Hide loading when page loads (in case of back button)
            hideLoading();
        }
    });
</script>
@endsection
