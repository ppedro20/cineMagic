<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Spatie\FlareClient\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TicketController extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Ticket::class);
    }

    public function index(Request $request): View
    {
        $tickets = Ticket::query()
        ->where('customer_id', Auth::user()->id)
        ->with(['purchase', 'seat', 'screening'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('tickets.show', compact('tickets'));
    }

    public function show(Ticket $ticket): View
    {
        return view('tickets.show', compact('ticket'));
    }

    // ??É preciso??
    public function validate(Ticket $ticket): View
    {
        return view('tickets.validate', compact('ticket'));
    }

}
