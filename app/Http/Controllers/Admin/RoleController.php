<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all roles
        $roles = Role::all(['id', 'name', 'status']);

        // Render roles on list
        return view('dashboard.roles.list', ['roles' => $roles, 'title' => 'Role Management']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Status List
        $status_list = get_status_list();

        // Render create client
        return view('dashboard.roles.create', ['status_list' => $status_list]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Handle add new role
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:m_roles',
            'status' => 'sometimes|required|in:0,1'
        ], [
            'name.required' => 'The first name is required!',
            'name.string' => 'The first name must be a string!',
            'name.max' => 'The first name should not exceed 100 characters in length!',
            'status.required' => 'The status is required!',
            'status.in' => 'The status is not valid!',
        ]);

        // Including updated by
        $validated['created_by'] = Auth::user()->id;

        // Insert location data
        Role::create($validated);

        // Redirect to roles page with success message
        return redirect()->route('roles.index')->with('success', 'A new role has been added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Fetch location
        $role = Role::where('id', $id)->first();

        // Status List
        $status_list = get_status_list();

        // Render edit location
        return view('dashboard.roles.edit', ['status_list' => $status_list, 'role' => $role]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Handle update existing role
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:100',
            'status' => 'sometimes|required|in:0,1'
        ], [
            'name.required' => 'The first name is required!',
            'name.string' => 'The first name must be a string!',
            'name.max' => 'The first name should not exceed 100 characters in length!',
            'status.required' => 'The status is required!',
            'status.in' => 'The status is not valid!',
        ]);

        // Including updated by
        $validated['updated_by'] = Auth::user()->id;

        // Insert role data
        $role = Role::findOrFail($id);
        $role->update($validated);

        // Redirect to roles page with success message
        return redirect()->route('roles.index')->with('success', 'The role has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Soft delete
        Role::where('id', $id)->update(['status' => 0, 'updated_by' => Auth::user()->id, 'deleted_at' => now()]);

        // Redirect to roles page with success message
        return redirect()->route('roles.index')->with('success', 'Location has been deleted successfully!');
    }
}
