{{-- Seamless Client and Service filters --}}
<div class="flex items-center space-x-1">
  @if(isset($clients) && $clients->count() > 0)
    {{-- Client Filter --}}
    <form method="GET" action="{{ $filterAction }}" class="flex items-center">
      @include('histories.partials.hidden-filters')
      <input type="hidden" name="search" value="{{ $search ?? '' }}">
      <input type="hidden" name="service_id" value="{{ $serviceId ?? '' }}">
      <select name="client_id" onchange="this.form.submit()" class="text-sm border border-gray-300 rounded-lg px-4 py-2 bg-white hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors min-w-[140px]">
        <option value="">All Clients</option>
        @foreach($clients as $client)
          <option value="{{ $client->id }}" {{ $clientId == $client->id ? 'selected' : '' }}>{{ $client->client_name }}</option>
        @endforeach
      </select>
    </form>
  @endif

  @if(isset($services) && $services->count() > 0)
    {{-- Service Filter --}}
    <form method="GET" action="{{ $filterAction }}" class="flex items-center">
      @include('histories.partials.hidden-filters')
      <input type="hidden" name="search" value="{{ $search ?? '' }}">
      <input type="hidden" name="client_id" value="{{ $clientId ?? '' }}">
      <select name="service_id" onchange="this.form.submit()" class="text-sm border border-gray-300 rounded-lg px-4 py-2 bg-white hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors min-w-[140px]">
        <option value="">All Services</option>
        @foreach($services as $service)
          <option value="{{ $service->id }}" {{ $serviceId == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
        @endforeach
      </select>
    </form>
  @endif
</div>
