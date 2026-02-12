<?php

namespace App\Services;

use App\Models\Booking;

class ReportService
{
    public function bookingReport(array $filters = [])
    {
        return $query = Booking::filter($filters)->getBookings()->latest('book_date')->get();
    }
}
