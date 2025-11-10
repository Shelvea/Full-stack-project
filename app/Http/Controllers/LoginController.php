<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    //
    public function login(Request $request)
    {

        $credentials = $request->validate([

            'email' => ['required', 'email'],

            'password' => ['required'],

        ]);

        //check if email exists
        $user = User::where('email', $request->email)->first();
        
        if(!$user){
            //Email not found
            return back()->withErrors([
                'email' => 'Email not registered.',
            ])->onlyInput('email');
        }

        //check password
        if(!Hash::check($request->password, $user->password)){
            return back()->withErrors([
            'password' => 'Password is incorrect.',
            ])->onlyInput('email'); // keep email in form
        }

            Auth::login($user);
            
            $request->session()->regenerate();

            if(Auth::user()->is_admin)
            {
                return redirect()->route('admin.dashboard');
            }
            
            
        return redirect()->route('dashboard');

    }
}
