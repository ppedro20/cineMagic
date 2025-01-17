<?php

namespace App\Models;

use App\Models\Ticket;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'date',
        'total_price',
        'customer_name',
        'customer_email',
        'nif',
        'payment_type',
        'payment_ref',
        'receipt_pdf_filename'
    ];

   /**
    * Get the customer associated with the Purchase
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasOne
    */
   public function customer(): HasOne
   {
       return $this->hasOne(customer::class, 'id', 'customer_id');
   }

    /**
     * Get all of the tickets for the Purchase
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function getReciept(Purchase $purchase){
		if ($purchase->receipt_pdf_filename)
			return Storage::response('pdf_purchase/'.$purchase->receipt_pdf_filename);
		return null;
	}

	public function getRecieptFullUrlAttribute(){
		if ($this->receipt_pdf_filename && Storage::exists("pdf_purchases/{$this->receipt_pdf_filename}")){
			return route('purchase.receipt');
		}
		return null;
	}
}
