<div class="px-6 py-4 border-t border-gray-200">
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
    <!-- Pagination Info -->
    <div class="text-sm text-gray-700">
      Showing
      <span class="font-medium">{{ $histories->firstItem() ?? 0 }}</span>
      to
      <span class="font-medium">{{ $histories->lastItem() ?? 0 }}</span>
      of
      <span class="font-medium">{{ $histories->total() }}</span>
      results
      @if ($search)
        for "<span class="font-medium text-blue-600">{{ $search }}</span>"
      @endif
    </div>

    <!-- Pagination Controls -->
    @if ($histories->hasPages())
      <x-pagination :paginator="$histories" />
    @endif
  </div>
</div>
