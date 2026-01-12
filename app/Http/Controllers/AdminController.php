<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class AdminController extends Controller
{
    //
    public function Dashboard(){
        
        return view('admin.dashboard');
    }

    public function viewCustomerDashboard()
    {
        abort_unless(Gate::allows('is_admin'), 403);

        session(['as_customer' => true]);

        return view('dashboard');//user dashboard
    }

    public function exitCustomerPreview()
    {
        session()->forget('as_customer');

        return view('admin.dashboard');//back to admin dashboard
    }


}
