<?php

namespace App\Models;

use App\Models\Ticket;
use App\Models\Theater;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seat extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'seats';

    protected $fillable = [
        'theater_id',
        'row',
        'seat_number'
    ];

    public $timestamps=false;
    /**
     * Get the theater that owns the Seat
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function theater(): BelongsTo
    {
        return $this->belongsTo(Theater::class, 'theater_id')->withTrashed();
    }

    /**
     * Get all of the tickets for the Seat
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'seat_id');
    }

    public function isReserved($screening_id)
    {
        return $this->tickets()->where('screening_id', $screening_id)->count() > 0;
    }

    public static function seatsPerRow($seats)
    {
        $seatsPerRow = [];
        foreach ($seats as $seat) {
            $seatsPerRow[$seat->row][] = $seat;
        }
        return $seatsPerRow;
    }
    public function getNameAttribute()
    {
        return $this->row.$this->seat_number;
    }
}
