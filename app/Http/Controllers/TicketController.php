<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\View\View;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $userId = Auth::user()->id;
        $tickets = Ticket::query()
        ->whereHas('purchase', function($query) use ($userId) {
            $query->where('customer_id', $userId);
        })
        ->with(['purchase', 'seat', 'screening'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket): View
    {
        return view('tickets.show', compact('ticket'));
    }

    public function validate(Ticket $ticket): View
    {
        return view('tickets.validate', compact('ticket'));
    }

}
