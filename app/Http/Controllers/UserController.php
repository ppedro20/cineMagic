<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function password(){
        return view('auth.reset-password');
    }

    public function store_password(Request $request){
        $request->validate([
            'old_pasword' => ['required', new MatchOldPassword],
            'password' => ['required'],
            'confirm_password' => ['same:password'],
        ]);

        User::find(auth()->user()->id)->update(['password' => Hash::make($request->password)]);

        return redirect()->route('movies.index')
            ->with('alert-msg', 'A senha foi alterada com sucesso')
            ->with('alert-type', 'success');
    }
}
