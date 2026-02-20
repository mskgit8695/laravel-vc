<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return [
                'Consignment No' => $item->consignment_no,
                'Booking Date' => $item->book_date_format,
                'Client' => $item->client_name,
                'Location' => $item->location_name,
                'Quantity' => $item->quantity,
                'Weight' => $item->quantity_type,
                'Status' => get_booking_status($item->booking_status)
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Consignment No',
            'Booking Date',
            'Client',
            'Location',
            'Quantity',
            'Weight',
            'Status'
        ];
    }
}
