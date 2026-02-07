<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        // Retrieve all users except those with role = 1 (admin)
        $users = (Auth::check() && Auth::user()->role == 1) ? User::all(['id', 'first_name', 'last_name', 'email', 'mobile_no', 'role', 'employee_id', 'status']) : User::where('role', '!=', 1)->get(['id', 'first_name', 'last_name', 'email', 'mobile_no', 'role', 'employee_id', 'status']);

        // Render the user management view with users data
        return view('dashboard.users.index', ['users' => $users, 'title' => 'User Management']);
    }

    public function create()
    {
        // Roles
        $roles = Role::where('id', '!=', 1)->where('status', '=', '1')->get(['id', 'name']);

        // Status List
        $status_list = get_status_list();

        return view('dashboard.users.create', ['roles' => $roles, 'status_list' => $status_list]);
    }

    public function store(Request $request)
    {
        // Handle add new user
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:100',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users',
            'employee_id' => 'required|string|max:50|unique:users',
            'mobile_no' => 'required|integer|min:10|max_digits:10',
            'role' => 'required|in:1,2,3',
            'status' => 'required|in:0,1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Split fullname into first_name and last_name
        $first_name = $request->input('fullname');
        $last_name = '';
        $name_parts = explode(' ', $request->input('fullname'), 2);
        if (is_array($name_parts) && sizeof($name_parts) > 1) {
            $first_name = trim($name_parts[0]) ?? '';
            $last_name = trim($name_parts[1]) ?? '';
        }

        // Create the user
        User::create([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => Str::lower($request->input('email')),
            'employee_id' => $request->input('employee_id'),
            'mobile_no' => $request->input('mobile_no'),
            'role' => $request->input('role'),
            'status' => $request->input('status'),
            'password' => Hash::make(Str::lower($request->input('email'))),
            'created_by' => Auth::user()->id
        ]);

        // Redirect to user page with success message
        return redirect()->route('dashboard.users')->with('success', 'A new user has been created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function edit(string $id)
    {
        // Fetch user
        $user = User::where('id', $id)->first();

        // Roles
        $roles = Role::where('id', '!=', 1)->where('status', '=', '1')->get(['id', 'name']);

        // Status List
        $status_list = get_status_list();

        // dd($user);

        return view('dashboard.users.edit', ['user' => $user, 'roles' => $roles, 'status_list' => $status_list]);
    }

    /**
     * Update the specified resource
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Handle add new user
        $validated = $request->validate([
            'fullname' => 'required|string|max:100',
            'employee_id' => 'required|string|max:50',
            'mobile_no' => 'required|integer|min:10|max_digits:10',
            'role' => 'required|in:1,2,3',
            'status' => 'required|in:0,1'
        ]);

        // Fetch User Data
        $user = User::findOrFail($id);

        // Update the first_name and last_name
        $first_name = $validated['fullname'];
        $last_name = '';
        $name_parts = explode(' ', $validated['fullname'], 2);
        if (is_array($name_parts) && sizeof($name_parts) > 1) {
            $first_name = trim($name_parts[0]) ?? '';
            $last_name = trim($name_parts[1]) ?? '';
        }

        $validated['first_name'] = $first_name;
        $validated['last_name'] = $last_name;

        $user->update([...$validated, 'updated_by' => Auth::user()->id]);

        // Redirect to users page with success message
        return redirect()->route('dashboard.users')->with('success', 'User has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Soft delete user
        User::where('id', $id)->delete();

        // Redirect to users page with success message
        return redirect()->route('dashboard.users')->with('success', 'User has been deleted successfully!');
    }
}
