<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WatchedDiary extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'watched_diary';

    protected $fillable = [
        'user_id',
        'movie_id',
        'watched_date',
    ];

    protected function casts(): array
    {
        return [
            'watched_date' => 'date',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}
