<div class="p-6 border-b border-gray-200 bg-gray-50">
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <x-stat-card
      title="Total Transactions"
      :value="number_format($stats['total'])"
      icon="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
      color="indigo"
    />
    <x-stat-card
      title="Successful"
      :value="number_format($stats['successful'])"
      icon="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
      color="green"
    />
    <x-stat-card
      title="Failed"
      :value="number_format($stats['failed'])"
      icon="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
      color="red"
    />
    <x-stat-card
      title="Total Price"
      :value="\App\Helpers\NumberHelper::formatCurrency($stats['total_price'])"
      icon="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"
      color="blue"
    />
  </div>
</div>
