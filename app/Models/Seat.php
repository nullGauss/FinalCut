<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'studio_id',
        'seat_number',
        'seat_type',
    ];

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }
}
