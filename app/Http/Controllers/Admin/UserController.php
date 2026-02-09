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
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users',
            'employee_id' => 'required|string|max:50|unique:users',
            'mobile_no' => 'required|integer|min:10|max_digits:10',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:1,2,3',
            'status' => 'required|in:0,1'
        ], [
            'first_name.required' => 'The first name is required!',
            'first_name.string' => 'The first name must be a string!',
            'first_name.max' => 'The first name should not exceed 100 characters in length!',
            'last_name.required' => 'The last name is required!',
            'last_name.string' => 'The last name must be a string!',
            'last_name.max' => 'The last name should not exceed 100 characters in length!',
            'email.required' => 'The email is required!',
            'email.regex' => 'The email is not valid!',
            'email.max' => 'The email should not exceed 250 characters in length!',
            'email.unique' => 'The email is already registered with us!',
            'employee_id.required' => 'The employee id is required!',
            'employee_id.unique' => 'The employee id is already on file with us!',
            'employee_id.max' => 'The employee id should not exceed 50 characters in length!',
            'mobile_no.required' => 'The mobile no is required!',
            'mobile_no.integer' => 'The mobile no is not valid!',
            'mobile_no.max_digits' => 'The mobile no should not exceed 10 characters in length!',
            'mobile_no.min' => 'The password must be at least 10 characters long!',
            'password.required' => 'The password is required!',
            'password.min' => 'The password must be at least 8 characters long!',
            'password.confirmed' => 'The confirmed password does not match the entered password!',
            'role.required' => 'The role is required!',
            'role.in' => 'The role is not valid!',
            'status.required' => 'The status is required!',
            'status.in' => 'The status is not valid!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create the user
        User::create([
            'first_name' => Str::lower($request->input('first_name')),
            'last_name' => Str::lower($request->input('last_name')),
            'email' => Str::lower($request->input('email')),
            'employee_id' => $request->input('employee_id'),
            'mobile_no' => $request->input('mobile_no'),
            'role' => $request->input('role'),
            'status' => $request->input('status'),
            'password' => Hash::make($request->input('password')),
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
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'mobile_no' => 'required|integer|min:10|max_digits:10',
            'role' => 'required|in:1,2,3',
            'status' => 'required|in:0,1'
        ], [
            'first_name.required' => 'The first name is required!',
            'first_name.string' => 'The first name must be a string!',
            'first_name.max' => 'The first name should not exceed 100 characters in length!',
            'last_name.required' => 'The last name is required!',
            'last_name.string' => 'The last name must be a string!',
            'last_name.max' => 'The last name should not exceed 100 characters in length!',
            'mobile_no.required' => 'The mobile no is required!',
            'mobile_no.integer' => 'The mobile no is not valid!',
            'mobile_no.max_digits' => 'The mobile no should not exceed 10 characters in length!',
            'mobile_no.min' => 'The password must be at least 10 characters long!',
            'role.required' => 'The role is required!',
            'role.in' => 'The role is not valid!',
            'status.required' => 'The status is required!',
            'status.in' => 'The status is not valid!',
        ]);

        // Including updated by
        $validated['updated_by'] = Auth::user()->id;

        // Fetch User Data
        $user = User::findOrFail($id);

        $user->update($validated);

        // Redirect to users page with success message
        return redirect()->route('dashboard.users')->with('success', 'User has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Soft delete user
        User::where('id', $id)->update(['status' => 0, 'updated_by' => Auth::user()->id, 'deleted_at' => now()]);

        // Redirect to users page with success message
        return redirect()->route('dashboard.users')->with('success', 'User has been deleted successfully!');
    }
}
