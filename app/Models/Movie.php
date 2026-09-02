<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'sinopsis',
        'durasi',
        'rating_umur',
        'poster',
        'trailer_url',
        'release_date',
        'director',
    ];

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'movie_genre');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
}
