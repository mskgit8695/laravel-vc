<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    // Update timestamp constants
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'updated_on';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_client';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'c_address',
        'c_contact',
        'status',
        'created_by',
        'updated_by'
    ];
}
