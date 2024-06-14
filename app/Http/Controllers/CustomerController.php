<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $user = Auth::user();
        if ($user->type == 'Customer') {
            $customer = Customer::select('*')->where('user_id', $user->id)->first();
        else
            return view('home');

        return view('customer.index')
            ->with('customer', $customer);
        }
    }

    public function update(CustomerRequest $request){
        $validated = $request->validated();

        $customer = customer$customer::find(Auth::user()->id);

        if ($request->name)
            $customer->user->name = $validated['name'];

        if ($request->email) {
            $customer->user->email = $validated['email'];
        }

        if ($request->nif)
            $customer->nif = $validated['nif'];

        if ($request->payment_type)
            $customer->payment_type = $validated['payment_type'];


        $customer->save();

        return view('customer$customers.perfil');
    }
}
