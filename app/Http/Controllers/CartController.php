<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Seat;
use App\Models\Ticket;
use App\Models\Purchase;
use App\Models\Screening;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Configuration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CartConfirmationFormRequest;

class CartController extends Controller
{
    public function show(): View
    {
        $cart = session('cart', []);
        return view('cart.show', compact('cart'));
    }

    public function addToCart(Request $request, Screening $screening, Seat $seat): RedirectResponse
    {
        $cart = session('cart', []);
        if ($seat->isReserved($screening->id)){
            $alertType = 'warning';
            $htmlMessage = "Ticket with the Seat
                <strong>\"{seat->name}\"</strong> was not added to the cart because it is already reserved!";
            return back()
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', $alertType);
        }

        $ticket = new Ticket();
        $ticket->screening_id = $screening->id;
        $ticket->seat_id = $seat->id;


        if (!$cart) {

            $cart = collect([$ticket]);
            $request->session()->put('cart', $cart);
        } else {
            $ticketInCart = $cart->first(function ($item) use ($ticket) {
                return $item->screening_id === $ticket->screening_id && $item->seat_id === $ticket->seat_id;
            });

            if ($ticketInCart) {
                $alertType = 'warning';
                $htmlMessage = "Ticket with the Seat
                <strong>\"{$seat->name}\"</strong> was not added to the cart because it is already there!";
                return back()
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType);
            } else {
                $cart->push($ticket);
            }
        }
        $alertType = 'success';
        $htmlMessage = "Ticket with the Seat
                <strong>\"{$ticket->seat->name}\"</strong> was added to the cart.";
        return back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', $alertType);
    }

    public function removeFromCart(Request $request, Screening $screening, Seat $seat): RedirectResponse
    {
        $cart = session('cart', []);
        if (!$cart) {
            $alertType = 'warning';
            $htmlMessage = "Ticket with the Seat
                <strong>\"{$seat->name}\"</strong> was not removed from the cart because cart is empty!";
            return back()
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', $alertType);
        } else {
            $ticketInCart = $cart->first(function ($item) use ($screening, $seat) {
                return $item->screening_id === $screening->id && $item->seat_id === $seat->id;
            });

            if ($ticketInCart) {
                $cart->forget($cart->search($ticketInCart));
                if ($cart->count() == 0) {
                    $request->session()->forget('cart');
                }
                $alertType = 'success';
                $htmlMessage = "Ticket with the Seat
                    <strong>\"{$seat->name}\"</strong> was removed from the cart.";

                return back()
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType);
            } else {
                $alertType = 'warning';
                $htmlMessage = "Ticket with the Seat
                    <strong>\"{$seat->name}\"</strong> was not removed from the cart because cart does not have it!";

                return back()
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType);
            }
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');
        return back()
            ->with('alert-type', 'success')
            ->with('alert-msg', 'Shopping Cart has been cleared');
    }


    public function confirm(CartConfirmationFormRequest $request): RedirectResponse
    {
        // Feito na aula com a stora
        $cart = session('cart', []);
        if (!$cart || (count($cart) == 0)) {
            return back()
                ->with('alert-type', 'danger')
                ->with('alert-msg', "Cart was not confirmed, because cart is empty!");
        } else {
            $purchase = new Purchase();
			$purchase->fill($request->validated());
            $purchase->customer_id = Auth::user()?->id;
            $purchase->date = Carbon::today();
            $purchase->total_price = 0;

            $totalPrice = 0;
            $ignored = 0;
            $configuration = Configuration::first();
            $currentDate = Carbon::today()->toDateString();
            $currentTime = Carbon::now()->subMinutes(5)->format('H:i');
            $insertTicket = [];

            foreach ($cart as $item) {
                // VERIFICAR formatos e condições
                if (
                    $item['screening']->tickets()->where('seat_id', $item['seat']->id)->count() == 0 &&
                    ($item['screening']->date > $currentDate ||
                    $item['screening']->date == $currentDate &&  $item['screening']->start_time >= $currentTime)
                ) {
                    $price = Auth::user()?
                        $configuration->ticket_price - $configuration->registered_customer_ticket_discount:
                        $configuration->ticket_price;

                    $insertTicket[] = [
                        'screening_id' => $item['screening']->id,
                        'seat_id' => $item['seat']->id,
                        'price' =>$price
                    ];

                    $totalPrice += $price;
                } else {
                    $ignored++;
                }
            }
            $ignoredStr = match($ignored) {
                0 => "",
                1 => "<br>(1 ticket was ignored because the seat was already occupied)",
                default => "<br>($ignored tickets were ignored because the seats were already occupied)"
            };
            $totalInserted = count($insertTicket);
            $totalInsertedStr = match($totalInserted) {
                0 => "",
                1 => "1 ticket was bought",
                default => "$totalInserted tickets were bought"
            };
            if ($totalInserted == 0) {
                $request->session()->forget('cart');
                return back()
                    ->with('alert-type', 'danger')
                    ->with('alert-msg', "No ticket was bought! $ignoredStr");
            } else {
                DB::transaction(function () use ($purchase, $insertTicket, $totalPrice) {
                    $purchase->total_price = $totalPrice;
                    $purchase->save();

                    foreach ($insertTicket as $t) {
                        $ticket = new Ticket();
                        $ticket->fill($t);
                        $ticket->purchase_id = $purchase->id;
                        $ticket->save();// antes do qr code??
                        $ticket->qrcode_url = route('home');// tickets.show, ['ticket' => $t]

                    }
                });
                $request->session()->forget('cart');
                if ($ignored == 0) {
                    return redirect()->route('home')
                        ->with('alert-type', 'success')
                        ->with('alert-msg', "$totalInsertedStr.");
                } else {
                    return redirect()->route('home')
                        ->with('alert-type', 'warning')
                        ->with('alert-msg', "$totalInsertedStr. $ignoredStr");
                }
            }
        }
    }
}
