{{-- General hidden filters that work for all pages --}}
@if (request()->has('per_page'))
  <input type="hidden" name="per_page" value="{{ request()->get('per_page') }}">
@endif
@if (request()->has('client_id'))
  <input type="hidden" name="client_id" value="{{ request()->get('client_id') }}">
@endif
@if (request()->has('service_id'))
  <input type="hidden" name="service_id" value="{{ request()->get('service_id') }}">
@endif
