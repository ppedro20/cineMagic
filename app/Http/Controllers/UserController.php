<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        return redirect()->route('home')
            ->with('alert-msg', 'A senha foi alterada com sucesso')
            ->with('alert-type', 'success');
    }

    public function updateBlock(User $user){

        $user->blocked = !$user->blocked;
        $user->save();
        if ($user->blocked){
            $htmlMessage = "User <u>{$user->name}</u> has been <strong>Blocked</strong> successfully!";
        }else{
            $htmlMessage = "User <u>{$user->name}</u> has been <strong>Unblocked</strong> successfully!";
        }

        return redirect()->back()
            ->with('alert-msg', $htmlMessage)
            ->with('alert-type', 'success');
    }
}
