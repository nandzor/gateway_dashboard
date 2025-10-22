@extends('layouts.app')

@section('title', 'View Client')
@section('page-title', 'Client Details')

@section('content')
  <div class="max-w-6xl">
    <x-card :padding="false">
      <!-- Header with Gradient -->
      <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-8">
        <div class="flex items-center">
          <div class="h-20 w-20 rounded-full bg-white flex items-center justify-center shadow-lg">
            <span class="text-3xl font-bold text-indigo-600">{{ substr($client->client_name, 0, 1) }}</span>
          </div>
          <div class="ml-6 flex-1">
            <h2 class="text-2xl font-bold text-white mb-1">{{ $client->client_name }}</h2>
            <p class="text-indigo-100 flex items-center text-sm mb-1">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
              </svg>
              {{ $client->type_name }}
            </p>
            @if($client->contact)
              <p class="text-indigo-100 flex items-center text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                {{ $client->contact }}
              </p>
            @endif
          </div>
          <div class="flex flex-col space-y-2">
            <x-badge :variant="$client->is_active == 1 ? 'success' : 'danger'" size="lg">
              {{ $client->is_active == 1 ? 'Active' : 'Inactive' }}
            </x-badge>
            <x-badge :variant="$client->is_staging == 1 ? 'warning' : 'primary'" size="lg">
              {{ $client->is_staging == 1 ? 'Staging' : 'Production' }}
            </x-badge>
          </div>
        </div>
      </div>

      <!-- Details Section -->
      <div class="p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Client ID -->
          <div class="p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center">
              <div class="p-2 bg-blue-100 rounded-lg mr-3">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                </svg>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 mb-0.5">Client ID</label>
                <p class="text-sm font-semibold text-gray-900">#{{ $client->id }}</p>
              </div>
            </div>
          </div>

          <!-- Client Type -->
          <div class="p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center">
              <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 mb-0.5">Client Type</label>
                <p class="text-sm font-semibold text-gray-900">{{ $client->type_name }}</p>
              </div>
            </div>
          </div>

          <!-- Status -->
          <div class="p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center">
              <div class="p-2 {{ $client->is_active == 1 ? 'bg-green-100' : 'bg-red-100' }} rounded-lg mr-3">
                <svg class="w-5 h-5 {{ $client->is_active == 1 ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 mb-0.5">Account Status</label>
                <x-badge :variant="$client->is_active == 1 ? 'success' : 'danger'" size="sm">
                  {{ $client->is_active == 1 ? 'Active' : 'Inactive' }}
                </x-badge>
              </div>
            </div>
          </div>

          <!-- Environment -->
          <div class="p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center">
              <div class="p-2 {{ $client->is_staging == 1 ? 'bg-orange-100' : 'bg-purple-100' }} rounded-lg mr-3">
                <svg class="w-5 h-5 {{ $client->is_staging == 1 ? 'text-orange-600' : 'text-purple-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 mb-0.5">Environment</label>
                <x-badge :variant="$client->is_staging == 1 ? 'warning' : 'primary'" size="sm">
                  {{ $client->is_staging == 1 ? 'Staging' : 'Production' }}
                </x-badge>
              </div>
            </div>
          </div>

          <!-- Member Since -->
          <div class="p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center">
              <div class="p-2 bg-purple-100 rounded-lg mr-3">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 mb-0.5">Member Since</label>
                <p class="text-sm font-semibold text-gray-900">{{ $client->created_at ? $client->created_at->format('F d, Y') : 'N/A' }}</p>
              </div>
            </div>
          </div>

          <!-- Last Updated -->
          <div class="p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center">
              <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 mb-0.5">Last Updated</label>
                <p class="text-sm font-semibold text-gray-900">{{ $client->updated_at ? $client->updated_at->format('M d, Y - h:i A') : 'N/A' }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Contact Information -->
        @if($client->address || $client->contact)
          <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
            <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
              <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              Contact Information
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              @if($client->address)
                <div>
                  <p class="text-sm font-medium text-gray-600">Address</p>
                  <p class="text-sm text-gray-900">{{ $client->address }}</p>
                </div>
              @endif
              @if($client->contact)
                <div>
                  <p class="text-sm font-medium text-gray-600">Contact</p>
                  <p class="text-sm text-gray-900">{{ $client->contact }}</p>
                </div>
              @endif
            </div>
          </div>
        @endif

        <!-- API Credentials -->
        @if($client->ak || $client->sk || $client->avkey_iv || $client->avkey_pass)
          <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-100">
            <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
              <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
              </svg>
              API Credentials
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              @if($client->ak)
                <div>
                  <p class="text-sm font-medium text-gray-600">Access Key (AK)</p>
                  <p class="text-sm text-gray-900 font-mono">{{ $client->ak }}</p>
                </div>
              @endif
              @if($client->sk)
                <div>
                  <p class="text-sm font-medium text-gray-600">Secret Key (SK)</p>
                  <p class="text-sm text-gray-900 font-mono">{{ $client->sk }}</p>
                </div>
              @endif
              @if($client->avkey_iv)
                <div>
                  <p class="text-sm font-medium text-gray-600">AVKey IV</p>
                  <p class="text-sm text-gray-900 font-mono">{{ $client->avkey_iv }}</p>
                </div>
              @endif
              @if($client->avkey_pass)
                <div>
                  <p class="text-sm font-medium text-gray-600">AVKey Pass</p>
                  <p class="text-sm text-gray-900 font-mono">{{ $client->avkey_pass }}</p>
                </div>
              @endif
            </div>
          </div>
        @endif

        <!-- Service Configuration -->
        @if(!empty($client->service_allow_name))
          <div class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border border-purple-100">
            <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
              <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              Service Configuration
            </h4>
            <div class="space-y-3">
              <div>
                <p class="text-sm font-medium text-gray-600 mb-2">Assigned Services</p>
                <div class="flex flex-wrap gap-2">
                  @foreach($client->service_allow_name as $serviceName)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                      {{ $serviceName }}
                    </span>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        @endif

        <!-- Security Settings -->
        @if($client->white_list || $client->module_40)
          <div class="p-4 bg-gradient-to-r from-red-50 to-orange-50 rounded-lg border border-red-100">
            <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
              <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
              Security Settings
            </h4>
            <div class="space-y-3">
              @if($client->white_list)
                <div>
                  <p class="text-sm font-medium text-gray-600">White List</p>
                  <p class="text-sm text-gray-900 whitespace-pre-line">{{ $client->white_list }}</p>
                </div>
              @endif
              @if($client->module_40)
                <div>
                  <p class="text-sm font-medium text-gray-600">Module 40</p>
                  <p class="text-sm text-gray-900 whitespace-pre-line">{{ $client->module_40 }}</p>
                </div>
              @endif
            </div>
          </div>
        @endif

        <!-- Balance Information -->
        @if($client->balances && $client->balances->count() > 0)
          @php
            $currentBalance = $client->balances->first();
          @endphp
          <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-100">
            <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
              <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
              </svg>
              Current Balance
            </h4>
            <div class="grid grid-cols-2 gap-4">
              <div class="text-center">
                <p class="text-3xl font-bold {{ $currentBalance->balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                  {{ number_format($currentBalance->balance, 3) }}
                </p>
                <p class="text-sm text-gray-600">Balance</p>
              </div>
              <div class="text-center">
                <p class="text-3xl font-bold text-blue-600">{{ number_format($currentBalance->quota) }}</p>
                <p class="text-sm text-gray-600">Quota</p>
              </div>
            </div>
            <div class="mt-3 text-center">
              <p class="text-xs text-gray-500">Last updated: {{ $currentBalance->updated_at ? $currentBalance->updated_at->format('M d, Y - h:i A') : 'N/A' }}</p>
            </div>
          </div>
        @endif

        <!-- Account Stats -->
        <div class="p-4 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg border border-indigo-100">
          <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Account Information
          </h4>
          <div class="grid grid-cols-4 gap-4">
            <div class="text-center">
              <p class="text-2xl font-bold text-indigo-600">{{ $client->id }}</p>
              <p class="text-xs text-gray-600 mt-1">Client ID</p>
            </div>
            <div class="text-center">
              @php
                $daysAgo = $daysSinceCreated;
              @endphp
              <p class="text-2xl font-bold text-green-600">
                @if ($daysAgo === 0)
                  Today
                @elseif ($daysAgo === 1)
                  Yesterday
                @else
                  {{ $daysAgo }} days ago
                @endif
              </p>
              <p class="text-xs text-gray-600 mt-1">Joined</p>
            </div>
            <div class="text-center">
              <p class="text-2xl font-bold text-purple-600">{{ $client->type_name }}</p>
              <p class="text-xs text-gray-600 mt-1">Client Type</p>
            </div>
            <div class="text-center">
              <p class="text-2xl font-bold {{ $client->is_active == 1 ? 'text-green-600' : 'text-red-600' }}">
                {{ $client->is_active == 1 ? 'Active' : 'Inactive' }}
              </p>
              <p class="text-xs text-gray-600 mt-1">Status</p>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
          <x-button variant="secondary" :href="route('clients.index')">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to List
          </x-button>
          <div class="flex space-x-3">
            @if (auth()->user()->isAdmin())
              <x-button variant="danger" @click="confirmDelete({{ $client->id }})">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Deactivate
              </x-button>
            @endif
            <x-button variant="primary" :href="route('clients.edit', $client)">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Edit Client
            </x-button>
          </div>
        </div>
      </div>
    </x-card>
  </div>

  <!-- Delete Confirmation Modal -->
  @if (auth()->user()->isAdmin())
    <!-- Hidden delete form -->
    <form id="delete-form-{{ $client->id }}" action="{{ route('clients.destroy', $client->id) }}" method="POST"
      class="hidden">
      @csrf
      @method('DELETE')
    </form>

    <x-confirm-modal id="confirm-delete" title="Confirm Deactivate"
      message="Are you sure you want to deactivate this client? The client's is_active status will be set to 0." confirmText="Deactivate Client"
      cancelText="Cancel" icon="warning" confirmAction="handleDeleteConfirm(data)" />
  @endif

  @push('scripts')
    <script>
      // Store clientId for deletion
      let pendingDeleteClientId = {{ $client->id }};

      function confirmDelete(clientId) {
        pendingDeleteClientId = clientId;
        console.log('confirmDelete called with clientId:', clientId);
        // Dispatch event to open modal with clientId
        window.dispatchEvent(new CustomEvent('open-modal-confirm-delete', {
          detail: {
            clientId: clientId
          }
        }));
      }

      function handleDeleteConfirm(data) {
        const clientId = data?.clientId || pendingDeleteClientId;
        console.log('handleDeleteConfirm called with clientId:', clientId);
        if (clientId) {
          const form = document.getElementById('delete-form-' + clientId);
          if (form) {
            form.submit();
          }
        }
      }

      // Make functions globally available
      window.confirmDelete = confirmDelete;
      window.handleDeleteConfirm = handleDeleteConfirm;
    </script>
  @endpush
@endsection
