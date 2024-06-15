<?php

namespace App\Models;

use App\Models\Seat;
use App\Models\Purchase;
use App\Models\Screening;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'screening_id',
        'seat_id',
        'purchase_id',
        'price',
        'qrcode_url',
        'status'
    ];

    /**
     * Get the purchase that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Get the seat that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class)->withTrashed();
    }

    /**
     * Get the screening that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function screening(): BelongsTo
    {
        return $this->belongsTo(Screening::class);
    }

    public function getisValidAttribute()
    {
        return $this->status == 'valid';
    }
}
