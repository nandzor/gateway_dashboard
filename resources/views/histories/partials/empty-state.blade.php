{{-- General empty state that works for all pages --}}
<x-empty-state
  icon="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
  title="No Histories Found"
  :description="$hasFilters ? 'No transaction histories match your current filters.' : $emptyDescription"
  :action-text="$hasFilters ? 'Clear Filters' : $emptyActionText"
  :action-url="$hasFilters ? $clearFiltersUrl : $emptyActionUrl"
/>
