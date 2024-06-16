<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;


class StatisticsController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View {



        $totalPurchases = Purchase::count();
        $totalAllUsers = User::count();

        $todayPurchases = Purchase::whereDate('created_at', Carbon::today())->count();
        $thisMonthPurchases = Purchase::whereMonth('created_at', Carbon::now()->month)->count();

        $mostWatchedFilm = DB::table('movies')
            ->join('screenings', 'movies.id', '=', 'screenings.movie_id')
            ->join('tickets', 'screenings.id', '=', 'tickets.screening_id')
            ->select('movies.title', DB::raw('count(*) as total'))
            ->groupBy('movies.title')
            ->orderBy('total', 'desc')
            ->first();

        //Best Theater
        $bestTheater = DB::table('theaters')
            ->join('screenings', 'theaters.id', '=', 'screenings.theater_id')
            ->join('tickets', 'screenings.id', '=', 'tickets.screening_id')
            ->select('theaters.name', DB::raw('count(*) as total'))
            ->groupBy('theaters.name')
            ->orderBy('total', 'desc')
            ->first();

        //Best Genre
        $bestGenre = DB::table('genres')
            ->join('movies', 'genres.code', '=', 'movies.genre_code')
            ->join('screenings', 'movies.id', '=', 'screenings.movie_id')
            ->join('tickets', 'screenings.id', '=', 'tickets.screening_id')
            ->select('genres.name', DB::raw('count(*) as total'))
            ->groupBy('genres.name')
            ->orderBy('total', 'desc')
            ->first();


        return view('statistics.index', compact('totalPurchases', 'totalAllUsers', 'todayPurchases', 'thisMonthPurchases', 'mostWatchedFilm', 'bestTheater','bestGenre'));
    }
}
