<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PurchaseController extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Purchase::class);
    }

    public function index(Request $request): View
    {
        $purchaseQuery = Purchase::orderBy('date', 'desc');

        $purchases = $purchaseQuery
            ->paginate(20)
            ->withQueryString();

        return view(
            'purchase.index',
            compact('purchase', 'filterByName')
        );
    }

    public function show(Purchase $purchase): View
    {
        return view('purchases.show')->with('purchase', $purchase);
    }
}
