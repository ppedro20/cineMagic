<?php

namespace App\Http\Controllers;

use App\Models\Theater;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\TheaterFormRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TheaterController extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Theater::class);
    }

    public function index(Request $request): View
    {
        $filterByName = $request->query('name');
        $theatersQuery = Theater::query();

        if ($filterByName !== null) {
            $theatersQuery->where('name', 'like', "%$filterByName%");
        }

        $theaters = $theatersQuery
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view(
            'theaters.index',
            compact('theaters', 'filterByName')
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Theater $theater): View
    {
        return view('theaters.show')->with('theater', $theater);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $theater = new Theater();
        return view('theaters.create',compact('theater'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function  store(TheaterFormRequest $request): RedirectResponse
    {
        //TODO
        $NewTheater = Theater::create($request->validated());
        $url = route('theaters.show', ['theater' => $NewTheater]);
        $htmlMessage = "Theater <a href='$url'><u>{$NewTheater->name}</u></a> has been created successfully!";
        return redirect()->route('theaters.index')
        ->with('alert-type', 'success')
        ->with('alert-msg', $htmlMessage);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Theater $theater): View
    {
        return view('theaters.edit',compact('theater'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TheaterFormRequest $request, Theater $theater): RedirectResponse
    {
        $theater->update($request->validated());
        $url = route('theaters.show', ['theater' => $theater]);
        $htmlMessage = "Theater <a href='$url'><u>{$theater->name}</u></a> has been updated successfully!";
        return redirect()->route('theaters.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Theater $theater): RedirectResponse
    {
        try {
            $url = route('theaters.show', ['theater' => $theater]);
            $totalScreenings = $theater->screenings()->count();

            if ($totalScreenings == 0) {
                $theaterSeats = $theater->seats;
                foreach ($theaterSeats as $seat) {
                    $seat->delete();
                }

                $theater->delete();
                $alertType = 'success';
                $alertMsg = "Theater {$theater->name} has been deleted successfully!";
            } else {
                $alertType = 'warning';
                $justification = match (true) {
                    $totalScreenings <= 0 => "",
                    $totalScreenings == 1 => "there is 1 screening enrolled in it",
                    $totalScreenings > 1 => "there are $totalScreenings screening enrolled in it",
                };

                $alertMsg = "Theater <a href='$url'><u>{$theater->name}</u></a> cannot be deleted because $justification.";
            }
        } catch (\Exception $error) {
            $alertType = 'danger';
            $alertMsg = "It was not possible to delete the theater
                            <a href='$url'><u>{$theater->name}</u></a>
                            because there was an error with the operation!";
        }

        return redirect()->route('theaters.index')
            ->with('alert-type', $alertType)
            ->with('alert-msg', $alertMsg);
    }
}
