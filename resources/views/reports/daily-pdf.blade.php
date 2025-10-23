<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Report - {{ $date }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #4F46E5;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        .summary-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        .summary-card h3 {
            margin: 0 0 10px 0;
            color: #4F46E5;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .summary-card .value {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            color: #4F46E5;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        .table th {
            background-color: #f8fafc;
            font-weight: bold;
            color: #374151;
        }
        .table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        .no-data {
            text-align: center;
            color: #6b7280;
            font-style: italic;
            padding: 40px;
            background-color: #f9fafb;
            border-radius: 8px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Daily Report</h1>
        <p>Date: {{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}</p>
        <p>Generated on: {{ now()->format('d F Y, H:i') }}</p>
    </div>

    <!-- Summary Cards -->
    <div class="summary-grid">
        <div class="summary-card">
            <h3>Total Transactions</h3>
            <div class="value">{{ number_format($summary['Total Transactions']) }}</div>
        </div>
        <div class="summary-card">
            <h3>Total Revenue</h3>
            <div class="value">Rp {{ number_format($summary['Total Revenue'], 0, ',', '.') }}</div>
        </div>
        <div class="summary-card">
            <h3>Total Duration</h3>
            <div class="value">{{ number_format($summary['Total Duration']) }}s</div>
        </div>
        <div class="summary-card">
            <h3>Unique Users</h3>
            <div class="value">{{ number_format($summary['Unique Users']) }}</div>
        </div>
        <div class="summary-card">
            <h3>Unique Clients</h3>
            <div class="value">{{ number_format($summary['Unique Clients']) }}</div>
        </div>
        <div class="summary-card">
            <h3>Success Rate</h3>
            <div class="value">{{ $summary['Success Rate'] }}</div>
        </div>
        <div class="summary-card">
            <h3>Avg Transaction Value</h3>
            <div class="value">Rp {{ number_format($summary['Avg Transaction Value'], 0, ',', '.') }}</div>
        </div>
        <div class="summary-card">
            <h3>Peak Hour</h3>
            <div class="value">{{ $summary['Peak Hour'] }}</div>
        </div>
    </div>

    <!-- Top Clients -->
    @if(isset($top_clients) && $top_clients->count() > 0)
    <div class="section">
        <h2>Top Clients by Revenue</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Client Type</th>
                    <th>Transactions</th>
                    <th>Total Revenue</th>
                    <th>Total Duration</th>
                </tr>
            </thead>
            <tbody>
                @foreach($top_clients as $client)
                <tr>
                    <td>{{ $client['client_name'] }}</td>
                    <td>
                        <span class="badge {{ $client['client_type'] == 1 ? 'badge-success' : 'badge-warning' }}">
                            {{ $client['client_type'] == 1 ? 'Prepaid' : 'Postpaid' }}
                        </span>
                    </td>
                    <td>{{ number_format($client['transaction_count']) }}</td>
                    <td>Rp {{ number_format($client['total_revenue'], 0, ',', '.') }}</td>
                    <td>{{ number_format($client['total_duration']) }}s</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Top Services -->
    @if(isset($top_services) && $top_services->count() > 0)
    <div class="section">
        <h2>Top Services by Usage</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th>Service ID</th>
                    <th>Usage Count</th>
                    <th>Total Revenue</th>
                    <th>Total Duration</th>
                </tr>
            </thead>
            <tbody>
                @foreach($top_services as $service)
                <tr>
                    <td>{{ $service['service_name'] }}</td>
                    <td>{{ $service['service_id'] }}</td>
                    <td>{{ number_format($service['usage_count']) }}</td>
                    <td>Rp {{ number_format($service['total_revenue'], 0, ',', '.') }}</td>
                    <td>{{ number_format($service['total_duration']) }}s</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Transaction Types -->
    @if(isset($transaction_types) && $transaction_types->count() > 0)
    <div class="section">
        <h2>Transaction Types Breakdown</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Count</th>
                    <th>Revenue</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction_types as $type => $details)
                <tr>
                    <td>{{ $type }}</td>
                    <td>{{ number_format($details['count']) }}</td>
                    <td>Rp {{ number_format($details['revenue'], 0, ',', '.') }}</td>
                    <td>{{ number_format($details['duration']) }}s</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Status Breakdown -->
    @if(isset($status_breakdown) && $status_breakdown->count() > 0)
    <div class="section">
        <h2>Status Breakdown</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($status_breakdown as $status => $details)
                <tr>
                    <td>
                        <span class="badge {{ $status === 'success' ? 'badge-success' : ($status === 'failed' ? 'badge-danger' : 'badge-warning') }}">
                            {{ ucfirst($status) }}
                        </span>
                    </td>
                    <td>{{ number_format($details['count']) }}</td>
                    <td>Rp {{ number_format($details['revenue'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- No Data Message -->
    @if($summary['Total Transactions'] == 0)
    <div class="no-data">
        <h3>No Data Available</h3>
        <p>There are no transactions for the selected date.</p>
    </div>
    @endif

    <div class="footer">
        <p>Generated by Gateway Dashboard System</p>
        <p>Report Date: {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</p>
    </div>
</body>
</html>
