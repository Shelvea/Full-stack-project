<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    //
    public function Dashboard()
    {
        return view('dashboard');// normal user
        
    }

    public function index()
    {
        //All users include admin
        $users = User::all();

        //Return Json response
        return response()->json(['users' => $users], 200);
        
    }

    public function destroy($id)
    {
    
        // Must be authenticated (Sanctum handles this)
        $authUser = auth()->user();

        //  Must be admin
        if (!$authUser->is_admin) {
            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        }

        //user detail
        $user = User::find($id);

        if(!$user){

            return response()->json(
                ['message' => 'User Not Found.'], 404);
        }

         // Prevent admin deleting self
        if ($user->id === $authUser->id) {
            return response()->json([
                'message' => 'You cannot delete your own account.'
            ], 403);
        }

        //Delete user
        $user->delete();

        // Return JSON Response
        return response()->json(
            ['message' => 'User successfully deleted.'], 200);
    }
}
