<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Monthly Report - {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}</title>
  <style>
    body {
      font-family: 'DejaVu Sans', Arial, sans-serif;
      font-size: 10px;
      color: #333;
    }

    .header {
      text-align: center;
      margin-bottom: 25px;
      border-bottom: 3px solid #F59E0B;
      padding-bottom: 15px;
    }

    .header h1 {
      margin: 0;
      color: #F59E0B;
      font-size: 26px;
      font-weight: bold;
    }

    .header p {
      margin: 5px 0 0 0;
      color: #666;
      font-size: 13px;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
      margin-bottom: 25px;
    }

    .stat-card {
      background: #F9FAFB;
      border: 1px solid #E5E7EB;
      border-radius: 6px;
      padding: 12px;
      text-align: center;
    }

    .stat-card .title {
      font-size: 9px;
      color: #6B7280;
      margin-bottom: 4px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .stat-card .value {
      font-size: 18px;
      font-weight: bold;
      color: #111827;
    }

    .stat-card.blue {
      border-left: 4px solid #3B82F6;
    }

    .stat-card.green {
      border-left: 4px solid #10B981;
    }

    .stat-card.purple {
      border-left: 4px solid #8B5CF6;
    }

    .stat-card.orange {
      border-left: 4px solid #F59E0B;
    }

    .stat-card.red {
      border-left: 4px solid #EF4444;
    }

    .stat-card.yellow {
      border-left: 4px solid #EAB308;
    }

    .section {
      margin-bottom: 25px;
    }

    .section-title {
      font-size: 14px;
      font-weight: bold;
      color: #111827;
      margin-bottom: 12px;
      padding-bottom: 6px;
      border-bottom: 2px solid #E5E7EB;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
    }

    .table th,
    .table td {
      padding: 8px 10px;
      text-align: left;
      border-bottom: 1px solid #E5E7EB;
    }

    .table th {
      background-color: #F9FAFB;
      font-weight: bold;
      font-size: 9px;
      color: #6B7280;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .table td {
      font-size: 10px;
      color: #111827;
    }

    .table tr:nth-child(even) {
      background-color: #F9FAFB;
    }

    .filter-info {
      background: #FEF3C7;
      border: 1px solid #F59E0B;
      border-radius: 6px;
      padding: 8px 12px;
      margin-bottom: 20px;
      font-size: 10px;
      color: #92400E;
    }

    .page-break {
      page-break-before: always;
    }

    .footer {
      position: fixed;
      bottom: 20px;
      left: 0;
      right: 0;
      text-align: center;
      font-size: 8px;
      color: #6B7280;
    }

    .text-right {
      text-align: right;
    }

    .text-center {
      text-align: center;
    }

    .font-bold {
      font-weight: bold;
    }

    .text-green {
      color: #10B981;
    }

    .text-red {
      color: #EF4444;
    }

    .text-blue {
      color: #3B82F6;
    }
  </style>
</head>

<body>
  <div class="header">
    <h1>Monthly Activity Report</h1>
    <p>{{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}</p>
    <p style="margin-top: 3px; font-size: 11px;">Generated on {{ now()->format('F d, Y - H:i:s') }}</p>
  </div>

  @if ($clientId || $serviceId)
    <div class="filter-info">
      <strong>🔍 Filter Applied:</strong>
      @if ($clientId)
        Client: {{ \App\Models\Client::find($clientId)->client_name ?? 'N/A' }}
      @endif
      @if ($serviceId)
        @if ($clientId) | @endif
        Service: {{ \App\Models\Service::find($serviceId)->name ?? 'N/A' }}
      @endif
    </div>
  @endif

  <!-- Summary Statistics -->
  <div class="stats-grid">
    <div class="stat-card blue">
      <div class="title">Total Transactions</div>
      <div class="value">{{ number_format($summary['Total Transactions']) }}</div>
    </div>
    <div class="stat-card green">
      <div class="title">Total Revenue</div>
      <div class="value">Rp {{ number_format($summary['Total Revenue'], 0, ',', '.') }}</div>
    </div>
    <div class="stat-card purple">
      <div class="title">Unique Clients</div>
      <div class="value">{{ number_format($summary['Unique Clients']) }}</div>
    </div>
    <div class="stat-card orange">
      <div class="title">Avg Transactions/Day</div>
      <div class="value">{{ number_format($summary['Avg Transactions/Day'], 1) }}</div>
    </div>
    <div class="stat-card red">
      <div class="title">Avg Revenue/Day</div>
      <div class="value">Rp {{ number_format($summary['Avg Revenue/Day'], 0, ',', '.') }}</div>
    </div>
    <div class="stat-card yellow">
      <div class="title">Avg Duration/Day</div>
      <div class="value">{{ number_format($summary['Avg Duration/Day'], 1) }}s</div>
    </div>
  </div>

  <!-- Transaction Types Breakdown -->
  <div class="section">
    <div class="section-title">Transaction Types Breakdown</div>
    <table class="table">
      <thead>
        <tr>
          <th>Type</th>
          <th class="text-right">Count</th>
          <th class="text-right">Revenue</th>
          <th class="text-right">Duration</th>
          <th class="text-right">Percentage</th>
        </tr>
      </thead>
      <tbody>
        @if(isset($transaction_types) && $transaction_types->count() > 0)
          @foreach ($transaction_types as $type => $data)
            <tr>
              <td class="font-bold">{{ $data['type'] == 1 ? 'Debit' : 'Credit' }}</td>
              <td class="text-right">{{ number_format($data['count'] ?? 0) }}</td>
              <td class="text-right">Rp {{ number_format($data['revenue'] ?? 0, 0, ',', '.') }}</td>
              <td class="text-right">{{ number_format($data['duration'] ?? 0, 1) }}s</td>
              <td class="text-right">{{ number_format(($data['count'] ?? 0) / max($summary['Total Transactions'], 1) * 100, 1) }}%</td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="5" class="text-center">No transaction data available</td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>

  <!-- Client Types Breakdown -->
  <div class="section">
    <div class="section-title">Client Types Breakdown</div>
    <table class="table">
      <thead>
        <tr>
          <th>Type</th>
          <th class="text-right">Count</th>
          <th class="text-right">Revenue</th>
          <th class="text-right">Duration</th>
          <th class="text-right">Percentage</th>
        </tr>
      </thead>
      <tbody>
        @if(isset($client_types) && $client_types->count() > 0)
          @foreach ($client_types as $type => $data)
            <tr>
              <td class="font-bold">{{ $data['type'] == 1 ? 'Prepaid' : 'Postpaid' }}</td>
              <td class="text-right">{{ number_format($data['count'] ?? 0) }}</td>
              <td class="text-right">Rp {{ number_format($data['revenue'] ?? 0, 0, ',', '.') }}</td>
              <td class="text-right">{{ number_format($data['duration'] ?? 0, 1) }}s</td>
              <td class="text-right">{{ number_format(($data['count'] ?? 0) / max($summary['Total Transactions'], 1) * 100, 1) }}%</td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="5" class="text-center">No client data available</td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>

  <div class="page-break"></div>

  <!-- Top Clients -->
  <div class="section">
    <div class="section-title">Top Clients by Revenue</div>
    <table class="table">
      <thead>
        <tr>
          <th>Rank</th>
          <th>Client Name</th>
          <th class="text-right">Transactions</th>
          <th class="text-right">Revenue</th>
          <th class="text-right">Duration</th>
          <th class="text-right">Avg Value</th>
        </tr>
      </thead>
      <tbody>
        @if(isset($top_clients) && $top_clients->count() > 0)
          @foreach ($top_clients as $index => $client)
            <tr>
              <td class="text-center font-bold">{{ $index + 1 }}</td>
              <td>{{ $client['client_name'] ?? 'Unknown' }}</td>
              <td class="text-right">{{ number_format($client['transaction_count'] ?? 0) }}</td>
              <td class="text-right">Rp {{ number_format($client['total_revenue'] ?? 0, 0, ',', '.') }}</td>
              <td class="text-right">{{ number_format($client['total_duration'] ?? 0, 1) }}s</td>
              <td class="text-right">Rp {{ number_format(($client['total_revenue'] ?? 0) / max($client['transaction_count'] ?? 1, 1), 0, ',', '.') }}</td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="6" class="text-center">No client data available</td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>

  <!-- Top Services -->
  <div class="section">
    <div class="section-title">Top Services by Usage</div>
    <table class="table">
      <thead>
        <tr>
          <th>Rank</th>
          <th>Service Name</th>
          <th class="text-right">Transactions</th>
          <th class="text-right">Revenue</th>
          <th class="text-right">Duration</th>
          <th class="text-right">Avg Value</th>
        </tr>
      </thead>
      <tbody>
        @if(isset($top_services) && $top_services->count() > 0)
          @foreach ($top_services as $index => $service)
            <tr>
              <td class="text-center font-bold">{{ $index + 1 }}</td>
              <td>{{ $service['service_name'] ?? 'Unknown' }}</td>
              <td class="text-right">{{ number_format($service['usage_count'] ?? 0) }}</td>
              <td class="text-right">Rp {{ number_format($service['total_revenue'] ?? 0, 0, ',', '.') }}</td>
              <td class="text-right">{{ number_format($service['total_duration'] ?? 0, 1) }}s</td>
              <td class="text-right">Rp {{ number_format(($service['total_revenue'] ?? 0) / max($service['usage_count'] ?? 1, 1), 0, ',', '.') }}</td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="6" class="text-center">No service data available</td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>

  <!-- Status Breakdown -->
  <div class="section">
    <div class="section-title">Transaction Status Breakdown</div>
    <table class="table">
      <thead>
        <tr>
          <th>Status</th>
          <th class="text-right">Count</th>
          <th class="text-right">Percentage</th>
        </tr>
      </thead>
      <tbody>
        @if(isset($status_breakdown) && $status_breakdown->count() > 0)
          @foreach ($status_breakdown as $status => $data)
            <tr>
              <td class="font-bold">{{ ucfirst($data['status'] ?? $status) }}</td>
              <td class="text-right">{{ number_format($data['count'] ?? 0) }}</td>
              <td class="text-right">{{ number_format(($data['count'] ?? 0) / max($summary['Total Transactions'], 1) * 100, 1) }}%</td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="3" class="text-center">No status data available</td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>

  <!-- Charge Breakdown -->
  <div class="section">
    <div class="section-title">Charge Breakdown</div>
    <table class="table">
      <thead>
        <tr>
          <th>Charge Type</th>
          <th class="text-right">Count</th>
          <th class="text-right">Revenue</th>
          <th class="text-right">Percentage</th>
        </tr>
      </thead>
      <tbody>
        @if(isset($charge_breakdown) && $charge_breakdown->count() > 0)
          @foreach ($charge_breakdown as $type => $data)
            <tr>
              <td class="font-bold">{{ $data['type'] ?? ($type == 1 ? 'Charged' : 'Free') }}</td>
              <td class="text-right">{{ number_format($data['count'] ?? 0) }}</td>
              <td class="text-right">Rp {{ number_format($data['revenue'] ?? 0, 0, ',', '.') }}</td>
              <td class="text-right">{{ number_format(($data['count'] ?? 0) / max($summary['Total Transactions'], 1) * 100, 1) }}%</td>
            </tr>
          @endforeach
        @else
          <tr>
            <td colspan="4" class="text-center">No charge data available</td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>

  <div class="footer">
    <p>Gateway Dashboard - Monthly Report | Generated on {{ now()->format('F d, Y - H:i:s') }}</p>
  </div>
</body>

</html>
