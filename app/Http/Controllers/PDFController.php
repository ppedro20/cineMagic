<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Mail\PurchaseReceipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class PDFController extends Controller
{
    public static function generatePDF(Purchase $purchase): String {
        $pdf = Pdf::loadView('pdf.receipt', ['purchase' => $purchase]);

        $receipt_filename = $purchase->id . '.pdf';
        Storage::put("pdf_purchases/$receipt_filename", $pdf->output());
        $path = storage_path("/app/pdf_purchases/$receipt_filename");
        Mail::to($purchase->customer_email)->send(new PurchaseReceipt($purchase, $receipt_filename));

        return $receipt_filename;
    }

    public static function viewUrl(Purchase $purchase) {
        if (!$purchase->receipt_pdf_filename) {
            $purchase->receipt_pdf_filename = PDFController::generatePDF($purchase);
            $purchase->save();
        }
        return route('pdf.show', ['filename' => $purchase->receipt_pdf_filename]);
    }

    public function showPDF($filename) {
        $path = "pdf_purchases/$filename";

        if (!Storage::exists($path)) {
            abort(404);
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        return Response::make($file, 200)->header("Content-Type", $type);
    }
}
