<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Movie;
use App\Models\Theater;
use App\Models\Screening;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ScreeningFormRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ScreeningController extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        //$this->authorizeResource(Screening::class);
    }

    public function index(Request $request): View
    {
        $screeningsQuery = Screening::query()
            ->orderBy('date')
            ->orderBy('start_time');

        $filterByMovie = $request->query('movie');
        if ($filterByMovie) {
            $screeningsQuery->whereHas('movie', function($query) use ($filterByMovie) {
                $query->where('title', 'like', "%$filterByMovie%");
            });
        }

        $filterByTheater = $request->query('theater');
        if ($filterByTheater) {
            $screeningsQuery->whereHas('theater', function($query) use ($filterByTheater) {
                $query->where('name', 'like', "%$filterByTheater%");
            });
        }

        $filterByBefore = $request->query('before');
        if ($filterByBefore) {
            $screeningsQuery->whereDate('date', '<', $filterByBefore);
        }

        $filterByAfter = $request->query('after');
        if ($filterByAfter) {
            $screeningsQuery->whereDate('date', '>', $filterByAfter);
        }

        $screenings = $screeningsQuery
            ->with('movie')
            ->with('theater')
            ->paginate(20)
            ->withQueryString();

        return view(
            'screenings.index',
            compact('screenings', 'filterByMovie', 'filterByBefore', 'filterByAfter', 'filterByTheater')
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Screening $screening): View
    {
        return view('screenings.show')->with('screening', $screening);
    }

    /**
     * Display the specified resource.
     */
    public function showScreenings(Request $request): View
    {
        $currentDate = Carbon::today()->toDateString();
        $currentTime = Carbon::now()->subMinutes(5)->format('H:i');
        $endDate = Carbon::now()->addWeeks(2)->toDateString();

        $screeningsQuery = Screening::query()
            ->where(function($query) use ($currentDate, $currentTime, $endDate) {
            $query->where('date', '>', $currentDate)
                    ->where('date', '<=', $endDate)
                    ->orWhere(function($query) use ($currentDate, $currentTime) {
                    $query->where('date', '=', $currentDate)
                        ->where('start_time', '>=', $currentTime);
                    });
        });


        $filterByMovie = $request->query('movie');
        if ($filterByMovie) {
            $screeningsQuery->whereHas('movie', function($query) use ($filterByMovie) {
                $query->where('title', 'like', "%$filterByMovie%");
            });
        }

        $filterByTheater = $request->query('theater');
        if ($filterByTheater) {
            $screeningsQuery->whereHas('theater', function($query) use ($filterByTheater) {
                $query->where('name', 'like', "%$filterByTheater%");
            });
        }

        $filterByBefore = $request->query('before');
        if ($filterByBefore) {
            $screeningsQuery->whereDate('date', '<', $filterByBefore);
        }

        $filterByAfter = $request->query('after');
        if ($filterByAfter) {
            $screeningsQuery->whereDate('date', '>', $filterByAfter);
        }


        $screenings = $screeningsQuery
            ->with('movie')
            ->with('theater')
            ->paginate(20)
            ->withQueryString();

        return view(
            'screenings.showscreenings',
            compact('screenings', 'filterByMovie', 'filterByBefore', 'filterByAfter', 'filterByTheater')
        );
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $screening = new Screening();
        $listTheaters = Theater::select('id', 'name')->get()->pluck('name', 'id')->toArray();
        $listMovies = Movie::select('id', 'title')->get()->pluck('title', 'id')->toArray();

        return view(
            'screenings.create',
            compact('screening', 'listTheaters', 'listMovies')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function  store(ScreeningFormRequest $request): RedirectResponse
    {
        //TODO
        $NewScreening = Screening::create($request->validated());
        $url = route('screenings.show', ['screening' => $NewScreening]);
        $htmlMessage = "Screening <a href='$url'><u>{$NewScreening->name}</u></a> has been created successfully!";
        return redirect()->route('screenings.index')
        ->with('alert-type', 'success')
        ->with('alert-msg', $htmlMessage);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Screening $screening): View
    {
        $listTheaters = Theater::select('id', 'name')->get()->pluck('name', 'id')->toArray();
        $listMovies = Movie::select('id', 'title')->get()->pluck('title', 'id')->toArray();
        return view(
            'screenings.edit',
            compact('screening', 'listTheaters', 'listMovies')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ScreeningFormRequest $request, Screening $screening): RedirectResponse
    {
        $screening->update($request->validated());
        $url = route('screenings.show', ['screening' => $screening]);
        $htmlMessage = "Screening <a href='$url'><u>{$screening->name}</u></a> has been updated successfully!";
        return redirect()->route('screenings.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Screening $screening): RedirectResponse
    {
        try {
            $url = route('screenings.show', ['screening' => $screening]);
            $totalScreenings = $screening->screenings()->count();

            if ($totalScreenings == 0) {
                $screeningSeats = $screening->seats;
                foreach ($screeningSeats as $seat) {
                    $seat->delete();
                }

                $screening->delete();
                $alertType = 'success';
                $alertMsg = "Screening {$screening->name} has been deleted successfully!";
            } else {
                $alertType = 'warning';
                $justification = match (true) {
                    $totalScreenings <= 0 => "",
                    $totalScreenings == 1 => "there is 1 screening enrolled in it",
                    $totalScreenings > 1 => "there are $totalScreenings screening enrolled in it",
                };

                $alertMsg = "Screening <a href='$url'><u>{$screening->name}</u></a> cannot be deleted because $justification.";
            }
        } catch (\Exception $error) {
            $alertType = 'danger';
            $alertMsg = "It was not possible to delete the screening
                            <a href='$url'><u>{$screening->name}</u></a>
                            because there was an error with the operation!";
        }
        return redirect()->route('screenings.index')
            ->with('alert-type', $alertType)
            ->with('alert-msg', $alertMsg);
    }
}
