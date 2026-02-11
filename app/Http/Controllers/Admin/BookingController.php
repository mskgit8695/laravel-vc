<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch booking
        $bookings = Booking::getBookings()->get();

        // Render the user management view with users data
        return view('dashboard.bookings.list', ['bookings' => $bookings, 'title' => 'Booking Management']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        // Fetch booking status master
        $booking_status_master = get_booking_master();

        // Fetch booking data
        $booking = Booking::where('id', $id)->first();

        // Render edit booking
        return view('dashboard.bookings.edit', ['booking_status' => $booking_status_master, 'booking' => $booking]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Handle update booking
        $validated = $request->validate([
            'book_date' => 'sometimes|required|date_format:Y-m-d',
            'booking_status' => 'sometimes|required|in:1,2,3',
        ], [
            'book_date.required' => 'The booking date is required!',
            'book_date.date_format' => 'The booking daate must be in Y-m-d date format!',
            'booking_status.required' => 'The booking status is required!',
            'booking_status.in' => 'The booking status should be Booking, Dispatch or Delivered!',
        ]);

        // Including updated by
        $validated['updated_by'] = Auth::user()->id;

        // Insert location data
        $booking = Booking::findOrFail($id);
        $booking->update($validated);

        // Redirect to locations page with success message
        return redirect()->route('bookings.index')->with('success', 'The booking has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Show get report form
     */
    public function show_report_form()
    {
        // Fetch booking status master
        $booking_status_master = get_booking_master();

        // Fetch booking
        $bookings = Booking::getBookings()->orderByDesc('book_date')->get();

        // Render view
        return view('dashboard.bookings.report-list', [
            'booking_status' => $booking_status_master,
            'bookings' => $bookings,
            'startDate' => '',
            'endDate' => '',
            'bookingType' => '',
        ]);
    }

    /**
     * Filter the report data based on selection
     */
    public function filter_report_data(Request $request)
    {
        // Fetch bookings based input
        $bookings = Booking::filter($request->all())->getBookings()->orderByDesc('book_date')->get();

        // Fetch booking status master
        $booking_status_master = get_booking_master();

        // Render view
        return view(
            'dashboard.bookings.report-list',
            [
                'booking_status' => $booking_status_master,
                'bookings' => $bookings,
                'startDate' => $request->input('startDate'),
                'endDate' => $request->input('endDate'),
                'bookingType' => $request->input('bookingType'),
            ]
        );
    }
}
