<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    //
    public function register(Request $request){

        //validate input
        
        $credentials = $request->validate([

            'name' => ['required', 'string', 'min:3' ,'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            
        ], [
            'name.min' => 'Name must at least have 3 characters',
            'email.unique' => 'Email have been registered.',
            'password.min' => 'Password must at least have 8 characters'
        ]);

        //create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
