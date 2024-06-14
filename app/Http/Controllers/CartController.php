<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartConfirmationFormRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;
use App\Models\Student;

class CartController extends Controller
{
    public function show(): View
    {
        $cart = session('cart', []);
        return view('cart.show', compact('cart'));
    }

    public function addToCart(Request $request, Ticket $ticket): RedirectResponse
    {
        if ($ticket->seat->isReserved){
            $alertType = 'warning';
                $url = route('tickets.show', ['ticket' => $ticket]);
                $htmlMessage = "Ticket <a href='$url'>#{$ticket->id}</a>
                <strong>\"{$ticket->seat->name}\"</strong> was not added to the cart because it is already reserved!";
                return back()
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType);
        }

        $cart = session('cart', []);
        if (!$cart) {
            $cart = collect([$ticket]);
            $request->session()->put('cart', $cart);
        } else {
            if ($cart->firstWhere('id', $ticket->id)) {
                $alertType = 'warning';
                $url = route('tickets.show', ['ticket' => $ticket]);
                $htmlMessage = "Ticket <a href='$url'>#{$ticket->id}</a>
                <strong>\"{$ticket->seat->name}\"</strong> was not added to the cart because it is already there!";
                return back()
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType);
            } else {
                $cart->push($ticket);
            }
        }
        $alertType = 'success';
        $url = route('tickets.show', ['ticket' => $ticket]);
        $htmlMessage = "Ticket <a href='$url'>#{$ticket->id}</a>
                <strong>\"{$ticket->seat->name}\"</strong> was added to the cart.";
        return back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', $alertType);
    }

    public function removeFromCart(Request $request, Ticket $ticket): RedirectResponse
    {
        $url = route('screenings.show', ['screening' => $screening]);// Verify
        $cart = session('cart', []);
        if (!$cart) {
            $alertType = 'warning';
            $htmlMessage = "Ticket <a href='$url'>#{$S->id}</a>
                <strong>\"{$ticket->seat->name}\"</strong> was not removed from the cart because cart is empty!";
            return back()
                ->with('alert-msg', $htmlMessage)
                ->with('alert-type', $alertType);
        } else {
            $element = $cart->firstWhere('id', $ticket->id);
            if ($element) {
                $cart->forget($cart->search($element));
                if ($cart->count() == 0) {
                    $request->session()->forget('cart');
                }
                $alertType = 'success';
                $htmlMessage = "Ticket <a href='$url'>#{$ticket->id}</a>
                <strong>\"{$ticket->seat->name}\"</strong> was removed from the cart.";
                return back()
                    ->with('alert-msg', $htmlMessage)
                    ->with('alert-type', $alertType);
            } else {
                $alertType = 'warning';
                $htmlMessage = "Ticket <a href='$url'>#{$ticket->seat->name}</a>
                <strong>\"{$ticket->seat->name}\"</strong> was not removed from the cart because cart does not include it!";
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
        $cart = session('cart', []);
        if (!$cart || (count($cart) == 0)) {
            return back()
                ->with('alert-type', 'danger')
                ->with('alert-msg', "Cart was not confirmed, because cart is empty!");
        } else {
            $purchase = new Purchase();
			$purchase->fill($request->validated());
            $purchase->customer_id = Auth::user()? Auth::user()->id : null;
            if (!$purchase->customer_id) {
                // User Not Registered
                return back()
                    ->with('alert-type', 'danger')
                    ->with('alert-msg', "Student number does not exist on the database!");
            }
            $purchase->date = Carbon::today();
            $purchase->total_price = 0;

            $ignored = 0;
            $configuration = Configuration::firts();
            $date = Carbon::today();
            $insertTicket = [];

            foreach ($cart as $item) {
                // VERIFICAR formatos e condições
                if (
                    $item['screening']->tickets()->where('seat_id', $item['seat']->id)->count() == 0 &&
                    ($item['screening']->date > $date ||
                    $item['screening']->date == $date &&  $item['screening']->start_time >= Carbon::now()->subMinutes(5)->format('h:i:s'))
                ) {
                    $price = Auth::user()?
                        $configuration->ticket_price - $configuration->registered_customer_ticket_discount:
                        $configuration->ticket_price;

                    $insertTicket[] = [
                        'screening_id' => $item['screening']->id,
                        'seat' => $item['seat']->id,
                        'price' =>$price
                    ]

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
                        $ticket->qrcode_url = route('tickets.show', ['ticket' => $t]);
                        $ticket->save();
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
