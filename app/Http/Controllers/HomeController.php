<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif (Auth::user()->role === 'vendor') {
                return redirect()->route('vendor.dashboard');
            } else {
                return redirect()->route('customer.dashboard');
            }
        }

        return view('welcome'); 
    }
}
