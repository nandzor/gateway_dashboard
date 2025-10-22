@extends('layouts.app')

@section('title', 'Clients')
@section('page-title', 'Clients Management')

@section('content')
  <x-card>
    <!-- Header -->
    <div class="p-6 border-b border-gray-200">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
        <div class="flex-1 max-w-md">
          <form method="GET" action="{{ route('clients.index') }}" class="flex">
            <x-input name="search" :value="$search ?? ''" placeholder="Search clients..." class="rounded-r-none border-r-0" />
            @if (request()->has('per_page'))
              <input type="hidden" name="per_page" value="{{ request()->get('per_page') }}">
            @endif
            <button type="submit"
              class="px-6 py-2 bg-gray-600 text-white rounded-r-lg hover:bg-gray-700 transition-colors">
              Search
            </button>
          </form>
        </div>

        <div class="flex items-center space-x-4">
          <!-- Show Inactive Toggle -->
          <form method="GET" action="{{ route('clients.index') }}" class="flex items-center">
            <input type="hidden" name="search" value="{{ $search ?? '' }}">
            <input type="hidden" name="per_page" value="{{ $perPage ?? 10 }}">
            <input type="hidden" name="type" value="{{ $type ?? '' }}">
            <label class="flex items-center space-x-2 cursor-pointer">
              <input type="checkbox" name="show_inactive" value="1" {{ $showInactive ? 'checked' : '' }}
                onchange="this.form.submit()" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
              <span class="text-sm text-gray-700">Show Inactive</span>
            </label>
          </form>

          <!-- Type Filter -->
          <form method="GET" action="{{ route('clients.index') }}" class="flex items-center">
            <input type="hidden" name="search" value="{{ $search ?? '' }}">
            <input type="hidden" name="per_page" value="{{ $perPage ?? 10 }}">
            <input type="hidden" name="show_inactive" value="{{ $showInactive ? '1' : '' }}">
            <select name="type" onchange="this.form.submit()" class="rounded border-gray-300 text-sm">
              <option value="">All Types</option>
              @foreach($typeOptions as $value => $label)
                <option value="{{ $value }}" {{ $type == $value ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </form>

          <!-- Per Page Selector -->
          <div class="flex items-center space-x-2">
            <x-per-page-selector :options="$perPageOptions ?? [10, 25, 50, 100]" :current="$perPage ?? 10" :url="route('clients.index')" type="server" />
          </div>

          <!-- Add Client Button -->
          @if (auth()->user()->isAdmin())
            <x-button variant="primary" size="sm" :href="route('clients.create')">
              <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Add Client
            </x-button>
          @endif
        </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="p-6 border-b border-gray-200">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-blue-50 p-4 rounded-lg">
          <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg mr-3">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-500">Total Clients</p>
              <p class="text-2xl font-bold text-blue-600">{{ $statistics['total_clients'] }}</p>
            </div>
          </div>
        </div>

        <div class="bg-green-50 p-4 rounded-lg">
          <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg mr-3">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-500">Active Clients</p>
              <p class="text-2xl font-bold text-green-600">{{ $statistics['active_clients'] }}</p>
            </div>
          </div>
        </div>

        <div class="bg-purple-50 p-4 rounded-lg">
          <div class="flex items-center">
            <div class="p-2 bg-purple-100 rounded-lg mr-3">
              <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-500">Production</p>
              <p class="text-2xl font-bold text-purple-600">{{ $statistics['production_clients'] }}</p>
            </div>
          </div>
        </div>

        <div class="bg-orange-50 p-4 rounded-lg">
          <div class="flex items-center">
            <div class="p-2 bg-orange-100 rounded-lg mr-3">
              <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-500">Staging</p>
              <p class="text-2xl font-bold text-orange-600">{{ $statistics['staging_clients'] }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Table -->
    <x-table :headers="['Client Name', 'Type', 'Contact', 'Status', 'Environment', 'Created', 'Actions']">
      @forelse($clients as $client)
        <tr class="hover:bg-blue-50 transition-colors {{ $client->is_active == 0 ? 'opacity-60 bg-gray-50' : '' }}">
          <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
              <div class="flex-shrink-0 h-10 w-10">
                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-md">
                  <span class="text-sm font-bold text-white">{{ substr($client->client_name, 0, 1) }}</span>
                </div>
              </div>
              <div class="ml-4">
                <div class="text-sm font-medium text-gray-900">{{ $client->client_name }}</div>
                @if($client->address)
                  <div class="text-sm text-gray-500">{{ Str::limit($client->address, 30) }}</div>
                @endif
              </div>
            </div>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <x-badge :variant="$client->type_badge_variant">
              {{ $client->type_name }}
            </x-badge>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            @if($client->contact)
              <div class="text-sm text-gray-900">{{ $client->contact }}</div>
            @else
              <div class="text-sm text-gray-400">No contact</div>
            @endif
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <x-badge :variant="$client->is_active == 1 ? 'success' : 'danger'">
              {{ $client->is_active == 1 ? 'Active' : 'Inactive' }}
            </x-badge>
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <x-badge :variant="$client->is_staging == 1 ? 'warning' : 'primary'">
              {{ $client->is_staging == 1 ? 'Staging' : 'Production' }}
            </x-badge>
          </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ $client->created_at ? $client->created_at->format('M d, Y') : 'N/A' }}
            </td>
          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <x-action-dropdown>
              <x-dropdown-link :href="route('clients.show', $client)">
                👁️ View Details
              </x-dropdown-link>

              @if (auth()->user()->isAdmin())
                <x-dropdown-link :href="route('clients.edit', $client)">
                  ✏️ Edit Client
                </x-dropdown-link>

                <x-dropdown-divider />

                <x-dropdown-button type="button" onclick="confirmDelete({{ $client->id }})" variant="danger">
                  🗑️ Deactivate Client
                </x-dropdown-button>

                <form id="delete-form-{{ $client->id }}" action="{{ route('clients.destroy', $client->id) }}" method="POST"
                  class="hidden">
                  @csrf
                  @method('DELETE')
                </form>
              @endif
            </x-action-dropdown>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" class="px-6 py-12 text-center">
            <div class="flex flex-col items-center justify-center">
              <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              <p class="text-gray-500 text-lg font-medium">No clients found</p>
              <p class="text-gray-400 text-sm mt-1">Try adjusting your search criteria</p>
            </div>
          </td>
        </tr>
      @endforelse
    </x-table>

    <!-- Pagination Info & Controls -->
    <div class="px-6 py-4 border-t border-gray-200">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
        <!-- Pagination Info -->
        <div class="text-sm text-gray-700">
          Showing
          <span class="font-medium">{{ $clients->firstItem() ?? 0 }}</span>
          to
          <span class="font-medium">{{ $clients->lastItem() ?? 0 }}</span>
          of
          <span class="font-medium">{{ $clients->total() }}</span>
          results
          @if (request()->has('search'))
            for "<span class="font-medium text-blue-600">{{ request()->get('search') }}</span>"
          @endif
        </div>

        <!-- Pagination Controls -->
        @if ($clients->hasPages())
          <x-pagination :paginator="$clients" />
        @endif
      </div>
    </div>
  </x-card>

  <!-- Delete Confirmation Modal -->
  <x-confirm-modal id="confirm-delete" title="Confirm Deactivate"
    message="Are you sure you want to deactivate this client? The client's is_active status will be set to 0." confirmText="Deactivate Client"
    cancelText="Cancel" icon="warning" confirmAction="handleDeleteConfirm(data)" />
@endsection

@push('scripts')
  <script>
    // Store clientId for deletion
    let pendingDeleteClientId = null;

    function confirmDelete(clientId) {
      pendingDeleteClientId = clientId;
      // Dispatch event to open modal with clientId
      window.dispatchEvent(new CustomEvent('open-modal-confirm-delete', {
        detail: {
          clientId: clientId
        }
      }));
    }

    function handleDeleteConfirm(data) {
      const clientId = data?.clientId || pendingDeleteClientId;
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
