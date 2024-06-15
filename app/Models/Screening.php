<?php

namespace App\Models;

use Carbon\Carbon;
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

    public static function filterPastScreenings(array $screenings)
    {
        $currentDate = Carbon::now();
        $currentTime = $currentDate->subMinutes(5)->format('H:i:s');

        return array_filter($screenings, function ($screening) use ($currentDate, $currentTime) {
            $screeningDate = Carbon::parse($screening['date']);
            $screeningTime = $screening['start_time'];

            if ($screeningDate->greaterThan($currentDate)) {
                return true;
            }

            if ($screeningDate->equalTo($currentDate) && $screeningTime >= $currentTime) {
                return true;
            }

            return false;
        });
    }

    public function occupation(): array
    {
        $totalSeats = $this->theater->seats()->count();
        $reservedSeats = $this->tickets()->count();

        return [$reservedSeats, $totalSeats];
    }
}
