<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all locations
        $locations = Location::all(['id', 'name', 'status']);

        // Render locations on list
        return view('dashboard.locations.list', ['locations' => $locations, 'title' => 'Location Management']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Status List
        $status_list = get_status_list();

        // Render create client
        return view('dashboard.locations.create', ['status_list' => $status_list]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Handle add new location
        $validated = $request->validate([
            'name' => 'required|string|max:100',
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
        Location::create($validated);

        // Redirect to locations page with success message
        return redirect()->route('locations.index')->with('success', 'A new location has been added successfully!');
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
        $location = Location::where('id', $id)->first();

        // Status List
        $status_list = get_status_list();

        // Render edit location
        return view('dashboard.locations.edit', ['status_list' => $status_list, 'location' => $location]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Handle update location
        $validated = $request->validate([
            'name' => 'required|string|max:100',
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

        // Insert location data
        $location = Location::findOrFail($id);
        $location->update($validated);

        // Redirect to locations page with success message
        return redirect()->route('locations.index')->with('success', 'The location has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Soft delete
        Location::where('id', $id)->update(['status' => 0, 'updated_by' => Auth::user()->id, 'deleted_at' => now()]);

        // Redirect to locations page with success message
        return redirect()->route('locations.index')->with('success', 'Location has been deleted successfully!');
    }
}
