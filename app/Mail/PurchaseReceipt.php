<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Purchase;

class PurchaseReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public $purchase;
    public $receipt_filename;

    public function __construct(Purchase $purchase, $receipt_filename)
    {
        $this->purchase = $purchase;
        $this->receipt_filename = $receipt_filename;
    }

    public function build()
    {
        return $this->subject('Purchase Receipt')->view('emails.receipt')
                    ->attach(storage_path("app/pdf_purchases/{$this->receipt_filename}"), [
                        'as' => $this->receipt_filename,
                        'mime' => 'application/pdf',
                    ]);
    }
}
