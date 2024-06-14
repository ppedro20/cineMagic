<?php

namespace App\Models;

use App\Models\Seat;
use App\Models\Screening;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Theater extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    /**
     * Get all of the seats for the Theater
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    /**
     * Get all of the screenings for the Theater
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function screenings(): HasMany
    {
        return $this->hasMany(Screening::class);
    }

    public function getRowsAttribute(){
		return $this->steats()->orderby('row')->pluck('row')->unique();
	}

	public function seatsRow($row){
		return $this->steats()->where('row', $row)->orderby('seat_number')->get();
	}
}
