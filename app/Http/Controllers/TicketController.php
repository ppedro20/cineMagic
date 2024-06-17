<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Screening;

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
        $userId = $user->id;
        $ticketsQuery = Ticket::query();

        if ($user->type === 'C'){
            $ticketsQuery->whereHas('purchase', function($query) use ($userId) {
                $query->where('customer_id', $userId);
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

/*
        $request->validate(
            ['ticket_id' => 'required|integer'],
            [
                'ticket_id.required' => 'The Ticket ID is required.',
                'ticket_id.integer'  => 'The Ticket ID must be a number.',
            ],
        );

        $ticketId = $request->input('ticket_id');
        $ticket = Ticket::find($ticketId);
        */
    public function validate(Request $request, Screening $screening)
    {
        // Validate the ticket_id here if needed, or proceed with your validation logic
        $ticket_id = $request->input('ticket_id');
        if(!$ticket_id){
            return view('tickets.showvalidate', compact('screening'));
        }
        // Example validation logic
        $ticket = Ticket::where('id', $ticket_id)->first();

        if (!$ticket) {
            return view(
                'tickets.showvalidate',
                [
                    'screening' => $screening,
                    'alert_type' => 'danger',
                    'alert_msg' => "Ticket no found!"
                ]
            );
        }
        if ($screening->id !== $ticket->screening_id) {
            return view(
                'tickets.showvalidate',
                [
                    'screening' => $screening,
                    'alert_type' => 'danger',
                    'alert_msg' => "Ticket does not belong to this screening!"
                ]
            );
        }
        if (!$ticket->isValid) {
            return view(
                'tickets.showvalidate',
                [
                    'screening' => $screening,
                    'alert_type' => 'danger',
                    'alert_msg' => "Ticket is invalid!"
                ]
            );
        }

        return redirect()->route('tickets.show', compact('ticket'));
    }


    public function updateStatus(Ticket $ticket)
    {
        if (!$ticket){
            return redirect()->back()
                ->with('alert-type', 'danger')
                ->with('alert-msg', "Ticket has not sent!");
        }
        if(!$ticket->isValid){
            return redirect()->back()
                ->with('alert-type', 'warning')
                ->with('alert-msg', "Ticket is already invalid! Cannot be Changed.");
        }

        $ticket->status = 'invalid';
        $ticket->save();

        return redirect()->route('tickets.validate', ['screening' => $ticket->screening])
                ->with('alert-type', 'success')
                ->with('alert-msg', "Ticket was Valid. Ticket update.");


    }
}
