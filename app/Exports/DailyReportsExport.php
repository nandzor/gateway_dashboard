<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DailyReportsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $data;
    protected $date;

    public function __construct($data)
    {
        $this->data = $data;
        $this->date = $data['date'] ?? now()->toDateString();
    }

    public function collection()
    {
        // Convert the daily report data to a collection for export
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

        // Add transaction types
        if (isset($this->data['transaction_types']) && $this->data['transaction_types']->count() > 0) {
            foreach ($this->data['transaction_types'] as $type => $details) {
                $collection->push((object) [
                    'type' => 'Transaction Type',
                    'metric' => $type,
                    'value' => $details['count'],
                    'details' => "Revenue: {$details['revenue']}, Duration: {$details['duration']}"
                ]);
            }
        }

        // Add client types
        if (isset($this->data['client_types']) && $this->data['client_types']->count() > 0) {
            foreach ($this->data['client_types'] as $type => $details) {
                $collection->push((object) [
                    'type' => 'Client Type',
                    'metric' => $type,
                    'value' => $details['count'],
                    'details' => "Revenue: {$details['revenue']}, Duration: {$details['duration']}"
                ]);
            }
        }

        // Add top clients
        if (isset($this->data['top_clients']) && $this->data['top_clients']->count() > 0) {
            foreach ($this->data['top_clients'] as $client) {
                $collection->push((object) [
                    'type' => 'Top Client',
                    'metric' => $client['client_name'],
                    'value' => $client['transaction_count'],
                    'details' => "Revenue: {$client['total_revenue']}, Duration: {$client['total_duration']}"
                ]);
            }
        }

        // Add top services
        if (isset($this->data['top_services']) && $this->data['top_services']->count() > 0) {
            foreach ($this->data['top_services'] as $service) {
                $collection->push((object) [
                    'type' => 'Top Service',
                    'metric' => $service['service_name'],
                    'value' => $service['usage_count'],
                    'details' => "Revenue: {$service['total_revenue']}, Duration: {$service['total_duration']}"
                ]);
            }
        }

        // Add hourly trends
        if (isset($this->data['hourly_trends']) && $this->data['hourly_trends']->count() > 0) {
            foreach ($this->data['hourly_trends'] as $hour => $details) {
                $collection->push((object) [
                    'type' => 'Hourly Trend',
                    'metric' => $hour,
                    'value' => $details['count'],
                    'details' => "Revenue: {$details['revenue']}, Duration: {$details['duration']}"
                ]);
            }
        }

        // Add status breakdown
        if (isset($this->data['status_breakdown']) && $this->data['status_breakdown']->count() > 0) {
            foreach ($this->data['status_breakdown'] as $status => $details) {
                $collection->push((object) [
                    'type' => 'Status',
                    'metric' => $status,
                    'value' => $details['count'],
                    'details' => "Revenue: {$details['revenue']}"
                ]);
            }
        }

        // Add charge breakdown
        if (isset($this->data['charge_breakdown']) && $this->data['charge_breakdown']->count() > 0) {
            foreach ($this->data['charge_breakdown'] as $charge => $details) {
                $collection->push((object) [
                    'type' => 'Charge Type',
                    'metric' => $charge,
                    'value' => $details['count'],
                    'details' => "Revenue: {$details['revenue']}"
                ]);
            }
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
        return 'Daily Report ' . $this->date;
    }
}
