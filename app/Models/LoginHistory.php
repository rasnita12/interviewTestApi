<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'city',
        'ip',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
