<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::all(['id', 'client_id', 'location_id', 'book_date', 'booking_status', 'consignment_no', 'quantity', 'quantity_type']);
        return response()->json($bookings);
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
        $validated = $request->validate([
            'consignment_no' => 'required|string|alpha_num:ascii|max:10|unique:m_booking',
            'client_id' => 'required|integer',
            'location_id' => 'required|integer',
            'city_address' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'quantity_type' => "required|in:KG,GM",
            'status' => 'required|string|max:50',
            'booking_status' => 'required|string|max:50',
            'book_date' => 'required|date',
        ]);

        $booking = Booking::create([...$validated, 'created_by' => $request->user()->id, 'booking_status' => 1]);

        return response()->json(['message' => 'Booking created successfully', 'booking' => $booking], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $consignment_no)
    {
        $booking = Booking::addSelect([
            'id',
            'client' => Client::select('name')->whereColumn('m_client.id', 'm_booking.client_id')->limit(1),
            'location_id' => Location::select('name')->whereColumn('m_location.id', 'm_booking.location_id')->limit(1),
            'book_date',
            'booking_status',
            'consignment_no',
            'quantity',
            'quantity_type'
        ])
            ->where(['consignment_no' => $consignment_no])
            ->first();

        if (!$booking) {
            return response()->json(['message' => 'No booking found with consignment number: ' . $consignment_no], 404);
        }

        return response()->json($booking);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Dispatch the specified booking to in-transit.
     */
    public function dispatchBooking(string $consignment_no)
    {
        $booking = Booking::addSelect([
            'id',
            'client' => Client::select('name')->whereColumn('m_client.id', 'm_booking.client_id')->limit(1),
            'location_id' => Location::select('name')->whereColumn('m_location.id', 'm_booking.location_id')->limit(1),
            'book_date',
            'booking_status',
            'consignment_no',
            'quantity',
            'quantity_type'
        ])
            ->where(['consignment_no' => $consignment_no, 'booking_status' => 1])
            ->first();

        if (!$booking) {
            return response()->json(['message' => 'No booking found with consignment number: ' . $consignment_no], 404);
        }

        $booking->booking_status = 2;
        $booking->save();

        return response()->json(['message' => 'Booking dispatched successfully', 'booking' => $booking]);
    }

    /**
     * Deliver the specified booking.
     */
    public function deliverBooking(Request $request, string $consignment_no)
    {
        $validated = $request->validate([
            'receiver_name' => 'required|string|max:100',
            'receiver_mobile' => 'required|integer|digits:10',
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg',
            'comment' => 'sometimes|string|max:255',
        ]);

        // Verify booking with consignment number and in-transit status
        $booking = Booking::where(['consignment_no' => $consignment_no, 'booking_status' => 2])->first();

        if (!$booking) {
            return response()->json(['message' => 'No booking found with consignment number: ' . $consignment_no], 404);
        }

        $booking->booking_status = 3;
        $booking->save();

        // Updating photo name with booking id
        $photo = null;
        if (isset($validated['photo'])) {
            $photo = time() . '-' . $booking->id . '.' . $request->file('photo')->extension();
            $request->file('photo')->storeAs('booking-pod', $photo, 'public');
        }

        // Update the booking with receiver details
        DB::table('m_booking_received')->insert([
            'book_id' => $booking->id,
            'receiver_name' => $validated['receiver_name'],
            'receiver_mobile' => $validated['receiver_mobile'],
            'photo' => $photo,
            'status' => '1'
        ]);

        return response()->json(['message' => 'Booking delivered successfully', 'booking' => $booking]);
    }
}
