<div class="p-6 border-b border-gray-200">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div>
      <label class="text-sm font-medium text-gray-500">Service Name</label>
      <p class="text-sm text-gray-900 mt-1">{{ $service->name }}</p>
    </div>
    <div>
      <label class="text-sm font-medium text-gray-500">Service Status</label>
      <div class="mt-1">
        <x-badge :variant="$service->is_active == 1 ? 'success' : 'danger'">
          {{ $service->is_active == 1 ? 'Active' : 'Inactive' }}
        </x-badge>
      </div>
    </div>
    <div>
      <label class="text-sm font-medium text-gray-500">Total Transactions</label>
      <p class="text-lg font-semibold text-gray-900 mt-1">{{ number_format($histories->total()) }}</p>
    </div>
  </div>
</div>
