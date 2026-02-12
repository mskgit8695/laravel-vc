<!DOCTYPE html>
<html>

<head>
    <title>Verma Courier Delivery Report</title>
    <style>
        body {
            font-family: DejaVu Sans;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 12px;
        }

        th {
            background: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Booking Report</h2>
    <table>
        <thead>
            <tr>
                <th>Consignment No</th>
                <th>Booking Date</th>
                <th>Client</th>
                <th>Location</th>
                <th>Quantity</th>
                <th>Weight</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->consignment_no }}</td>
                    <td>{{ $item->book_date_format }}</td>
                    <td>{{ $item->client }}</td>
                    <td>{{ $item->location }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->quantity_type }}</td>
                    <td>{{ get_booking_status($item->booking_status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
