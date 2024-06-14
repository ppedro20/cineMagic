<?php

namespace App\Models;

use App\Models\Movie;
use App\Models\Ticket;
use App\Models\Theater;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Screening extends Model
{
    use HasFactory;

    protected $table = 'screenings';

    protected $fillable = [
        'movie_id',
        'theater_id',
        'date',
        'start_time'
    ];

    /**
     * Get all of the tickets for the Screening
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'screening_id');
    }


    /**
     * Get the theater that owns the Screening
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function theater(): BelongsTo
    {
        return $this->belongsTo(Theater::class, 'theater_id')->withTrashed();
    }

    /**
     * Get the movie that owns the Screening
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class, 'movie_id')->withTrashed();
    }
}
