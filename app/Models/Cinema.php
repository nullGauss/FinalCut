<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cinema extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'address',
        'city',
        'latitude',
        'longitude',
    ];

    public function studios()
    {
        return $this->hasMany(Studio::class);
    }
}
