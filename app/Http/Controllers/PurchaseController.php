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
        $this->middleware('can:view,purchase')->only('show');
        $this->middleware('can:viewAny,App\Models\Purchase')->only('index');
        $this->middleware('can:view,purchase')->only('showReciept');
    }

    public function index(Request $request): View
    {
        $user = Auth::user();
        $purchasesQuery = Purchase::query();

        if ($user->type !== 'A'){
            $purchasesQuery->where('customer_id', $user->id);
        }

        $filterByKeyword = $request->query('keyword');
        if ($filterByKeyword) {
            $purchasesQuery->where(function ($query) use ($filterByKeyword) {
                $query->where('customer_name', 'like', "%$filterByKeyword%")
                    ->orWhere('customer_email', 'like', "%$filterByKeyword%");
            });
        }

        $filterByBefore = $request->query('before');
        if ($filterByBefore) {
            $purchasesQuery->whereDate('date', '<', $filterByBefore);
        }

        $filterByAfter = $request->query('after');
        if ($filterByAfter) {
            $purchasesQuery->whereDate('date', '>', $filterByAfter);
        }

        $purchases = $purchasesQuery
                ->with(['tickets', 'customer'])
                ->orderBy('date', 'desc')
                ->paginate(20);


        return view(
            'purchases.index',
            compact('purchases', 'filterByKeyword', 'filterByBefore', 'filterByAfter')
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
