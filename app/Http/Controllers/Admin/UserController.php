<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        // Retrieve all users except those with role = 1 (admin)
        $users = (Auth::check() && Auth::user()->role == 1) ? User::all(['id', 'first_name', 'last_name', 'email', 'mobile_no', 'role', 'employee_id', 'status']) : User::where('role', '!=', 1)->get(['id', 'first_name', 'last_name', 'email', 'mobile_no', 'role', 'employee_id', 'status']);

        // Render the user management view with users data
        return view('dashboard.users.index', ['users' => $users, 'title' => 'User Management']);
    }

    public function create() {}
}
