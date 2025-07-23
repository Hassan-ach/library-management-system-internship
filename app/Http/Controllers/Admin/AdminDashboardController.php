<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    //
    public function index(){
        return view("admin/dashboard");
    }

    public function profile(){
        try {
            // Get the currently authenticated user
            $user = Auth::user();
            return view("admin/profile",compact('user'));
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'User not found: '.$e->getMessage());
        }
        
    }
}
