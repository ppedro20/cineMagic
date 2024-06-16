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
        $user = Auth::user();
        $ticketsQuery = Ticket::query();

        if ($user->type !== 'A'){
            $ticketsQuery->whereHas('purchase', function($query) use ($user) {
                $query->where('customer_id', $user->Id);
            });

        }
        $filterByKeyword = $request->query('keyword');
        if ($filterByKeyword) {
            $ticketsQuery->whereHas('purchase', function ($query) use ($filterByKeyword) {
                $query->where('customer_name', 'like', "%$filterByKeyword%")
                    ->orWhere('customer_email', 'like', "%$filterByKeyword%");
            });
        }

        $filterByMovie = $request->query('movie');
        if ($filterByMovie) {
            $ticketsQuery->whereHas('screening.movie', function($query) use ($filterByMovie) {
                $query->where('title', 'like', "%$filterByMovie%")
                ->orWhere('synopsis', 'like', "%$filterByMovie%");
            });
        }

        $filterByTheater = $request->query('theater');
        if ($filterByTheater) {
            $ticketsQuery->whereHas('screening.theater', function($query) use ($filterByTheater) {
                $query->where('name', 'like', "%$filterByTheater%");
            });
        }

        $tickets = $ticketsQuery
            ->with(['purchase', 'seat', 'screening'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        return view(
            'tickets.index',
            compact('tickets', 'filterByKeyword', 'filterByTheater' ,'filterByMovie')
        );
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
