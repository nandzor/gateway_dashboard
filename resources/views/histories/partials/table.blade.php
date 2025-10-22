<table class="min-w-full divide-y divide-gray-200">
  <thead class="bg-gray-50">
    <tr>
      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction</th>
      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
      <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
    </tr>
  </thead>
  <tbody class="bg-white divide-y divide-gray-200">
    @foreach($histories as $history)
      <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 whitespace-nowrap">
          <div class="flex items-center">
            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-md mr-4">
              <span class="text-sm font-bold text-white">{{ substr($history->trx_id ?? 'H', 0, 1) }}</span>
            </div>
            <div>
              <div class="text-sm font-medium text-gray-900">{{ $history->trx_id ?? 'N/A' }}</div>
              <div class="text-sm text-gray-500">{{ $history->trx_req ?? 'N/A' }}</div>
            </div>
          </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
          <div class="text-sm text-gray-900">{{ $history->client->client_name ?? 'N/A' }}</div>
          <div class="text-sm text-gray-500">{{ $history->client_type_display }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
          <div class="text-sm text-gray-900">{{ $history->service->name ?? 'N/A' }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
          <x-badge :variant="$history->trx_type == 1 ? 'success' : ($history->trx_type == 2 ? 'warning' : 'info')">
            {{ $history->transaction_type_display }}
          </x-badge>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
          <div class="text-sm font-medium text-gray-900">{{ $history->formatted_price }}</div>
          <div class="text-sm text-gray-500">{{ $history->charge_status_display }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
          {{ $history->formatted_duration }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
          <x-badge :variant="$history->status_badge_variant">
            {{ $history->status_display }}
          </x-badge>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
          {{ $history->trx_date ? $history->trx_date->format('M d, Y H:i') : ($history->created_at ? $history->created_at->format('M d, Y H:i') : 'N/A') }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
          <x-action-dropdown>
            <x-dropdown-link :href="route('histories.show', $history)">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              View Details
            </x-dropdown-link>
            @if($history->client)
              <x-dropdown-link :href="route('histories.by-client', $history->client)">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Client Histories
              </x-dropdown-link>
            @endif
            @if($history->service)
              <x-dropdown-link :href="route('histories.by-service', $history->service)">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Service Histories
              </x-dropdown-link>
            @endif
          </x-action-dropdown>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
