<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BookingSeat extends Pivot
{
    protected $table = 'booking_seats';

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'seat_id',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
}
