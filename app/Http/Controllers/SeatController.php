<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\SeatFormRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SeatController extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Seat::class);
    }

    public function index(Request $request): View
    {
        $theaterId = $request->query('theaterId');
        $filterByRow = $request->query('filterByRow');

        $seatsQuery = Seat::with('theater')->where($theaterId);

        if ($filterByRow && strlen($filterByRow)==1) {
            $moviesQuery->where('row', "$filterByRow");
        }

        $listRows = $seatsQuery->distinct('row')->pluck('row')->toArray();

        $seats = $seatsQuery
            ->orderBy('row')
            ->orderBy('seat_number')
            ->paginate(20)
            ->withQueryString();

        return view(
            'seats.index',
            compact('seats', 'listRows', 'filterByRow')
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Seat $seat): View
    {
        return view('seats.show')->with('seat', $seat);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(String $theaterId): View
    {
        $seat = new Seat();
        $seat->theater_id = $theaterId;


        return view('seats.create',compact('seat'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SeatFormRequest $request): RedirectResponse
    {
        //TODO
        $newSeat = Seat::create($request->validated());

        $theater = $newSeat->theater()->withTrashed()->first();

        $url = route('seats.show', ['seat' => $newSeat]);
        $htmlMessage = "Seat <a href='$url'><u>{".$newSeat->row.$newSeat->seat_number."}</u></a> has been created successfully!";
        return redirect()->route('theaters.edit', compact('theater') )
        ->with('alert-type', 'success')
        ->with('alert-msg', $htmlMessage);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seat $seat): View
    {
        return view('seats.edit',compact('seat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SeatFormRequest $request, Seat $seat): RedirectResponse
    {
        $seat->update($request->validated());

        $theater = $seat->theater()->withTrashed()->first();

        $url = route('seats.show', ['seat' => $seat]);
        $htmlMessage = "Seat <a href='$url'><u>{".$seat->row.$seat->seat_number."}</u></a> has been updated successfully!";
        return redirect()->route('theaters.edit', compact('theater'))
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seat $seat): RedirectResponse
    {
        $alertType = 'success';
        $alertMsg = "Seat {".$seat->row.$seat->seat_number."} has been deleted successfully!";
        $theater = $seat->theater()->withTrashed()->first();
        $seat->delete();

        return redirect()->route('theaters.show', compact('theater') )
            ->with('alert-type', $alertType)
            ->with('alert-msg', $alertMsg);
    }
}
