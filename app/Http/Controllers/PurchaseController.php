<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Barryvdh\DomPDF\PDF;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PDFController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PurchaseController extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        //$this->authorizeResource(Purchase::class);
    }

    public function index(Request $request): View
    {
        $user = Auth::user();
        $purchases = Purchase::query();

        if ($user->type !== 'A'){
            $purchases = $purchases->where('customer_id', $user->id);
        }

        $filterByKeyword = $request->query('keyword');
        if ($filterByKeyword) {
            $moviesQuery->where(function ($query) use ($filterByKeyword) {
                $query->where('customer_name', 'like', "%$filterByKeyword%")
                    ->orWhere('customer_email', 'like', "%$filterByKeyword%");
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

        $purchases = $purchases
                ->with(['tickets', 'customer'])
                ->orderBy('date', 'desc')
                ->paginate(20);


        return view(
            'purchases.index',
            compact('purchases')
        );
    }

    public function show(Purchase $purchase): View
    {
        return view('purchases.show')->with('purchase', $purchase);
    }

    public function showReciept(Purchase $purchase): View
    {
        $url = PDFController::viewUrl($purchase);
        return view(
            'purchases.receipt',
            compact('url')
        );
    }
}
