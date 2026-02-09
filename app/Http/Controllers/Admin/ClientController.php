<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all clients
        $clients = Client::all(['id', 'name', 'c_address', 'c_contact', 'status']);

        // Render clients on list
        return view('dashboard.clients.list', ['clients' => $clients, 'title' => 'Client Management']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Status List
        $status_list = get_status_list();

        // Render create client
        return view('dashboard.clients.create', ['status_list' => $status_list]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Handle add new client
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'c_address' => 'sometimes|required|string|max:100',
            'c_contact' => 'sometimes|required|integer|min:10|max_digits:10',
            'status' => 'sometimes|required|in:0,1'
        ], [
            'name.required' => 'The first name is required!',
            'name.string' => 'The first name must be a string!',
            'name.max' => 'The first name should not exceed 100 characters in length!',
            'c_address.required' => 'The last name is required!',
            'c_address.string' => 'The last name must be a string!',
            'c_address.max' => 'The last name should not exceed 100 characters in length!',
            'c_contact.required' => 'The mobile no is required!',
            'c_contact.integer' => 'The mobile no is not valid!',
            'c_contact.max_digits' => 'The mobile no should not exceed 10 characters in length!',
            'c_contact.min' => 'The password must be at least 10 characters long!',
            'status.required' => 'The status is required!',
            'status.in' => 'The status is not valid!',
        ]);

        // Including updated by
        $validated['created_by'] = Auth::user()->id;
        $validated['name'] = Str::upper($validated['name']);

        // Insert client data
        Client::create($validated);

        // Redirect to users page with success message
        return redirect()->route('clients.index')->with('success', 'A new client has been added successfully!');
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
        // Fetch client
        $client = Client::where('id', $id)->first();

        // Status List
        $status_list = get_status_list();

        // Render edit client
        return view('dashboard.clients.edit', ['status_list' => $status_list, 'client' => $client]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Handle add new client
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'c_address' => 'sometimes|required|string|max:100',
            'c_contact' => 'sometimes|required|integer|min:10|max_digits:10',
            'status' => 'sometimes|required|in:0,1'
        ], [
            'name.required' => 'The first name is required!',
            'name.string' => 'The first name must be a string!',
            'name.max' => 'The first name should not exceed 100 characters in length!',
            'c_address.required' => 'The last name is required!',
            'c_address.string' => 'The last name must be a string!',
            'c_address.max' => 'The last name should not exceed 100 characters in length!',
            'c_contact.required' => 'The mobile no is required!',
            'c_contact.integer' => 'The mobile no is not valid!',
            'c_contact.max_digits' => 'The mobile no should not exceed 10 characters in length!',
            'c_contact.min' => 'The password must be at least 10 characters long!',
            'status.required' => 'The status is required!',
            'status.in' => 'The status is not valid!',
        ]);

        // Including updated by
        $validated['updated_by'] = Auth::user()->id;
        $validated['name'] = Str::upper($validated['name']);

        // Fetch User Data
        $client = Client::findOrFail($id);
        $client->update($validated);

        // Redirect to users page with success message
        return redirect()->route('clients.index')->with('success', 'Client has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Soft delete user
        Client::where('id', $id)->update(['status' => 0, 'updated_by' => Auth::user()->id, 'deleted_at' => now()]);

        // Redirect to users page with success message
        return redirect()->route('clients.index')->with('success', 'Client has been deleted successfully!');
    }
}
