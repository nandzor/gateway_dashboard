<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlyReportsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $data;
    protected $month;

    public function __construct($data)
    {
        $this->data = $data;
        $this->month = $data['month'] ?? now()->format('Y-m');
    }

    public function collection()
    {
        // Convert the monthly report data to a collection for export
        $collection = collect();

        // Add summary data
        $collection->push((object) [
            'type' => 'Summary',
            'metric' => 'Total Transactions',
            'value' => $this->data['summary']['Total Transactions'],
            'details' => ''
        ]);

        $collection->push((object) [
            'type' => 'Summary',
            'metric' => 'Total Revenue',
            'value' => $this->data['summary']['Total Revenue'],
            'details' => ''
        ]);

        $collection->push((object) [
            'type' => 'Summary',
            'metric' => 'Total Duration',
            'value' => $this->data['summary']['Total Duration'],
            'details' => ''
        ]);

        $collection->push((object) [
            'type' => 'Summary',
            'metric' => 'Unique Users',
            'value' => $this->data['summary']['Unique Users'],
            'details' => ''
        ]);

        $collection->push((object) [
            'type' => 'Summary',
            'metric' => 'Unique Clients',
            'value' => $this->data['summary']['Unique Clients'],
            'details' => ''
        ]);

        $collection->push((object) [
            'type' => 'Summary',
            'metric' => 'Avg Transactions/Day',
            'value' => $this->data['summary']['Avg Transactions/Day'],
            'details' => ''
        ]);

        $collection->push((object) [
            'type' => 'Summary',
            'metric' => 'Avg Revenue/Day',
            'value' => $this->data['summary']['Avg Revenue/Day'],
            'details' => ''
        ]);

        $collection->push((object) [
            'type' => 'Summary',
            'metric' => 'Avg Duration/Day',
            'value' => $this->data['summary']['Avg Duration/Day'],
            'details' => ''
        ]);

        // Add transaction types
        foreach ($this->data['transaction_types'] as $type => $details) {
            $collection->push((object) [
                'type' => 'Transaction Type',
                'metric' => $type,
                'value' => $details['count'],
                'details' => "Revenue: {$details['revenue']}, Duration: {$details['duration']}"
            ]);
        }

        // Add client types
        foreach ($this->data['client_types'] as $type => $details) {
            $collection->push((object) [
                'type' => 'Client Type',
                'metric' => $type,
                'value' => $details['count'],
                'details' => "Revenue: {$details['revenue']}, Duration: {$details['duration']}"
            ]);
        }

        // Add top clients
        foreach ($this->data['top_clients'] as $client) {
            $collection->push((object) [
                'type' => 'Top Client',
                'metric' => $client['client_name'],
                'value' => $client['transaction_count'],
                'details' => "Revenue: {$client['total_revenue']}, Duration: {$client['total_duration']}"
            ]);
        }

        // Add top services
        foreach ($this->data['top_services'] as $service) {
            $collection->push((object) [
                'type' => 'Top Service',
                'metric' => $service['service_name'],
                'value' => $service['usage_count'],
                'details' => "Revenue: {$service['total_revenue']}, Duration: {$service['total_duration']}"
            ]);
        }

        // Add daily trends
        foreach ($this->data['daily_trends'] as $date => $details) {
            $collection->push((object) [
                'type' => 'Daily Trend',
                'metric' => $date,
                'value' => $details['count'],
                'details' => "Revenue: {$details['revenue']}, Duration: {$details['duration']}"
            ]);
        }

        // Add weekly trends
        foreach ($this->data['weekly_trends'] as $week => $details) {
            $collection->push((object) [
                'type' => 'Weekly Trend',
                'metric' => $week,
                'value' => $details['count'],
                'details' => "Revenue: {$details['revenue']}, Duration: {$details['duration']}"
            ]);
        }

        // Add status breakdown
        foreach ($this->data['status_breakdown'] as $status => $details) {
            $collection->push((object) [
                'type' => 'Status',
                'metric' => $status,
                'value' => $details['count'],
                'details' => "Revenue: {$details['revenue']}"
            ]);
        }

        // Add charge breakdown
        foreach ($this->data['charge_breakdown'] as $charge => $details) {
            $collection->push((object) [
                'type' => 'Charge Type',
                'metric' => $charge,
                'value' => $details['count'],
                'details' => "Revenue: {$details['revenue']}"
            ]);
        }

        return $collection;
    }

    public function headings(): array
    {
        return [
            'Type',
            'Metric',
            'Value',
            'Details',
        ];
    }

    public function map($item): array
    {
        return [
            $item->type,
            $item->metric,
            $item->value,
            $item->details,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'],
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Monthly Report ' . $this->month;
    }
}
