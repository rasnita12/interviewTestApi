<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthCenter extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'line_1',
        'line_2',
        'postcode',
        'county',
        'city',
        'latitude',
        'latitude',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];
}
