<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

class Booking extends Model
{
    use HasFactory, HasApiTokens;

    // Update timestamp constants
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'updated_on';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_booking';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'book_date',
        'consignment_no',
        'client_id',
        'location_id',
        'city_address',
        'quantity',
        'quantity_type',
        'status',
        'booking_status',
        'created_by',
        'updated_by'
    ];

    /**
     * Scope a query to fetch booking for api response or list page.
     */
    public function scopeGetBookings(Builder $query): void
    {
        $query->addSelect([
            'id',
            'client' => Client::select('name')->whereColumn('m_client.id', 'm_booking.client_id')->limit(1),
            'location' => Location::select('name')->whereColumn('m_location.id', 'm_booking.location_id')->limit(1),
            'book_date',
            'booking_status',
            'consignment_no',
            'quantity',
            'quantity_type'
        ]);
    }

    // format book date into d-m-Y
    protected function bookDateFormat(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => Carbon::parse($attributes['book_date'])->format('d-m-Y'),
        );
    }

    /**
     * Scope a query to filter booking based on input request
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when(isset($filters['startDate'], $filters['endDate']), function ($query) use ($filters) {
            $query->whereBetween('book_date', [$filters['startDate'], $filters['endDate']]);
        })->when($filters['bookingType'] ?? null, function ($query, $bookingType) {
            $query->where('booking_status', $bookingType);
        });
    }
}
