<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRcodeGenerateController extends Controller
{
    public static function store(string $route, string $ticketId)
    {
        $qrCode = QrCode::size(200)
        ->format('png')
        ->generate($route);

        $filename = "$ticketId.png";

        Storage::put("ticket_qrcodes/$filename", $qrCode);
        return $filename;
    }
}
