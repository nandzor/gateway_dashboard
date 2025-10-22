<div class="p-6 border-b border-gray-200">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div>
      <label class="text-sm font-medium text-gray-500">Client Name</label>
      <p class="text-sm text-gray-900 mt-1">{{ $client->client_name }}</p>
    </div>
    <div>
      <label class="text-sm font-medium text-gray-500">Client Type</label>
      <p class="text-sm text-gray-900 mt-1">{{ $client->type == 1 ? 'Prepaid' : 'Postpaid' }}</p>
    </div>
    <div>
      <label class="text-sm font-medium text-gray-500">Total Transactions</label>
      <p class="text-lg font-semibold text-gray-900 mt-1">{{ number_format($histories->total()) }}</p>
    </div>
  </div>
</div>
