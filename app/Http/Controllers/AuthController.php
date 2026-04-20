<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    //
    public function register(Request $request){

        //validate input
        
        $request->validate([

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

        return response()->json([
            'message' => 'Registered successfully'
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([

            'email' => ['required', 'email'],
            'password' => ['required'],

        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
            'message' => 'Invalid email or password'
            ], 401);
        }

        $request->session()->regenerate();

        $user = Auth::user();
            
        return response()->json([
            'user' => $user,
            'role' => $user->is_admin ? 'admin' : 'user'
        ]);

    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');

    }
}
