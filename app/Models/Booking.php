<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'consignment_no',
        'client_id',
        'location_id',
        'city_address',
        'quantity',
        'quantity_type',
        'status',
        'booking_status'
    ];
}
