<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\MovieFormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MovieController extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('can:create,App\Models\Movie')->only(['create', 'store']);
        $this->middleware('can:viewAny,App\Models\Movie')->only(['index']);
        $this->middleware('can:update,movie')->only(['edit', 'update']);
        $this->middleware('can:delete,movie')->only(['destroy']);
    }

    public function index(Request $request): View
    {
        $moviesQuery = Movie::orderBy('title');

        $filterByKeyword = $request->query('keyword');
        if ($filterByKeyword) {
            $moviesQuery->where(function ($query) use ($filterByKeyword) {
                $query->where('title', 'like', "%$filterByKeyword%")
                    ->orWhere('synopsis', 'like', "%$filterByKeyword%");
            });
        }
        $filterByGenre = $request->query('genre');
        if ($filterByGenre) {
            $moviesQuery->where('genre_code', "$filterByGenre");
        }
        $movies = $moviesQuery
            ->with('genre')
            ->with('screenings')
            ->paginate(20)
            ->withQueryString();

        $listGenres = Genre::select('code', 'name')->get()->pluck('name', 'code')->toArray();

        return view(
            'movies.index',
            compact('movies', 'listGenres', 'filterByKeyword', 'filterByGenre')
        );
    }

    public function showMovies(Request $request): View
    {
        $moviesQuery = Movie::orderBy('title');

        $currentDate = Carbon::today()->toDateString();
        $currentTime = Carbon::now()->subMinutes(5)->format('H:i');
        $endDate = Carbon::now()->addWeeks(2)->toDateString();

        $moviesQuery = $moviesQuery->whereHas('screenings', function($query) use ($currentDate, $currentTime, $endDate) {
            $query->where('date', '>', $currentDate)
                ->where('date', '<=', $endDate)
                ->orWhere(function($query) use ($currentDate, $currentTime) {
                    $query->where('date', '=', $currentDate)
                        ->where('start_time', '>=', $currentTime);
                });
        });

        $filterByKeyword = $request->query('keyword');
        if ($filterByKeyword) {
            $moviesQuery->where(function ($query) use ($filterByKeyword) {
                $query->where('title', 'like', "%$filterByKeyword%")
                    ->orWhere('synopsis', 'like', "%$filterByKeyword%");
            });
        }
        $filterByGenre = $request->query('genre');
        if ($filterByGenre) {
            $moviesQuery->where('genre_code', "$filterByGenre");
        }
        $movies = $moviesQuery
            ->with('genre')
            ->with(['screenings' => function($query) {
                $query->orderBy('date')->orderBy('start_time');
            }])
            ->leftJoin('screenings', 'movies.id', '=', 'screenings.movie_id')
            ->select('movies.*', DB::raw('MIN(screenings.date) as screening_date'), DB::raw('MIN(screenings.start_time) as screening_time'))
            ->groupBy('movies.id')
            ->orderBy('screening_date')
            ->orderBy('screening_time')
            ->paginate(20)
            ->withQueryString();

        $listGenres = Genre::select('code', 'name')->get()->pluck('name', 'code')->toArray();

        return view(
            'movies.showmovies',
            compact('movies', 'listGenres', 'filterByKeyword', 'filterByGenre')
        );
    }


    public function show(Movie $movie): View
    {
        // Get the current date and time
        $currentDate = Carbon::today()->toDateString();
        $currentTime = Carbon::now()->subMinutes(5)->format('H:i');
        $endDate = Carbon::now()->addWeeks(2)->toDateString();

        // Get screenings following the specified logic
        $screenings = $movie->screenings()
            ->where( function($query) use ($currentDate, $currentTime, $endDate) {
                $query->where('date', '>', $currentDate)
                    ->where('date', '<=', $endDate)
                    ->orWhere(function($query) use ($currentDate, $currentTime) {
                    $query->where('date', '=', $currentDate)
                        ->where('start_time', '>=', $currentTime);
                });
            })
            ->get();
        return view('movies.show', compact('movie', 'screenings'));
    }

    public function create(): View
    {
        $newMovie = new Movie();
        $listGenres = Genre::select('code', 'name')->get()->pluck('name', 'code')->toArray();
        return view('movies.create')
            ->withMovie($newMovie)
            ->with('listGenres', $listGenres);
    }

    public function store(MovieFormRequest $request) : RedirectResponse
    {
        $validatedData = $request->validated();
        $newMovie = DB::transaction(function () use ($validatedData, $request) {
            $newMovie = new Movie();
            $newMovie->title = $validatedData['title'];
            $newMovie->movie_code = $validatedData['movie_code'];
            $newMovie->year = $validatedData['year'];
            $newMovie->synopsis = $validatedData['synopsis'];

            if ($request->hasFile('poster_file')) {
                $path = $request->poster_file->store('public/posters');
                $newMovie->poster_filename = basename($path);
            }

            $newMovie->save();
            return $newMovie;
        });

        $url = route('movies.show', ['movie' => $newMovie]);
        $htmlMessage = "Movie <a href='$url'><u>{$newMovie->title}</u></a> has been created successfully!";
        return redirect()->route('movies.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function edit(Movie $movie): View
    {
        $listGenres = Genre::select('code', 'name')->get()->pluck('name', 'code')->toArray();
        return view('movies.edit', compact('movie', 'listGenres'));
    }

    public function update(MovieFormRequest $request, Movie $movie): RedirectResponse
    {
        $validatedData = $request->validated();
        $movie = DB::transaction(function () use ($validatedData, $movie, $request) {
            $movie->title = $validatedData['title'];
            $movie->movie_code = $validatedData['movie_code'];
            $movie->year = $validatedData['year'];
            $movie->synopsis = $validatedData['synopsis'];

            if ($request->hasFile('poster_file')) {
                if ($movie->poster_filename && Storage::fileExists("public/posters/$movie->poster_filename")) {
                    Storage::delete("public/posters/$movie->poster_filename");
                }
                $path = $request->poster_file->store('public/posters');
                $movie->poster_filename = basename($path);

            }
            $movie->save();
            return $movie;
        });
        $url = route('movies.show', ['movie' => $movie]);
        $htmlMessage = "Movie <a href='$url'><u>{$movie->title}</u></a> has been updated successfully!";


        return redirect()->route('movies.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function destroy(Movie $movie): RedirectResponse
    {
        $url = route('movies.show', ['movie' => $movie]);


        $totalScreenings = $movie->screenings->count();

        if ($totalScreenings == 0){
            $movie->delete();
            $alertType = 'success';
            $alertMsg = "Movie <u>{$movie->title}</u> has been deleted successfully!";
        } else {
            $alertType = 'warning';
            $justification = match (true) {
                $totalScreenings <= 0 => "",
                $totalScreenings == 1 => "there is 1 screening in the movie",
                $totalScreenings > 1 => "there are $totalScreenings screenings in the movie",
            };
            $alertMsg = "Movie <a href='$url'><u>{$movie->title}</u></a> ({$movie->id}) cannot be deleted because $justification.";
        }

        return redirect()->route('movies.index')
            ->with('alert-type', $alertType)
            ->with('alert-msg', $alertMsg);
    }


    public function destroyPoster(Movie $movie): RedirectResponse
    {
        $this->authorize('update', $movie);

        if ($movie->poster_filename && Storage::fileExists("public/posters/$movie->poster_filename")) {
            Storage::delete("public/posters/$movie->poster_filename");

            $movie->poster_filename = null;
            $movie->save();
            $url = route('movies.show', ['movie' => $movie]);
            return redirect()->route('movies.index')
                ->with('alert-type', 'success')
                ->with('alert-msg', "Poster of <a href='$url'><u>{$movie->title}</u></a> has been deleted.");
        }
        return redirect()->back();
    }
}
