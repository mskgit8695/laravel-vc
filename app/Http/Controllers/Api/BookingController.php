<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Location;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use PDOException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * Store a newly created resource in storage.
     */
    public function store(BookingRequest $request)
    {
        try {
            // Booking request validation
            $validated = $request->validated();

            // Create booking
            $booking = $this->createBooking($validated, $request);

            // Return response
            return response()->json(['message' => 'Booking created successfully', 'bookings' => $booking], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 400);
        } catch (PDOException $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 400);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage(), 'errors' => $e->errors()], 400);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $consignment_no)
    {
        try {
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
        } catch (QueryException $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 400);
        } catch (PDOException $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 400);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage(), 'errors' => $e->errors()], 400);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Dispatch the specified booking to in-transit.
     */
    public function dispatchBooking(Request $request)
    {
        try {
            // Validation rules
            $rules = [
                'bookings' => 'required|array|min:1|max:10',
                'bookings.*.consignment_no' => 'required|string|alpha_num:ascii|max:10|exists:m_booking',
                'bookings.*.location_id' => 'required|integer',
            ];

            // Validation message
            $messages = [
                'bookings.required' => 'The bookings is required!',
                'bookings.min' => 'The bookings is required!',
                'bookings.max' => 'The bookings is required!',
                'bookings.*.consignment_no.required' => 'The consignment no is required!',
                'bookings.*.consignment_no.exists' => 'The consignment no is not available!',
                'bookings.*.location_id.required' => 'The location is required!',
                'bookings.*.location_id.integer' => 'The location is required!',
            ];

            // Validate the request
            $validated = $request->validate($rules, $messages);

            // Update the booking
            $booking = $this->updateBooking($validated, $request);

            return response()->json(['message' => 'Booking dispatched successfully', 'booking' => $booking]);
        } catch (QueryException $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 400);
        } catch (PDOException $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 400);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage(), 'errors' => $e->errors()], 400);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'An unexpected error occurred.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Deliver the specified booking.
     */
    public function deliverBooking(Request $request, string $consignment_no)
    {
        try {
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

            // Update the booking with delivered status
            $booking->booking_status = 3;
            $booking->updated_by = Request::user()->id;
            $booking->save();

            return response()->json(['message' => 'Booking delivered successfully', 'booking' => $booking]);
        } catch (QueryException $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 400);
        } catch (PDOException $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 400);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage(), 'errors' => $e->errors()], 400);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }


    /**
     * Get Booking Details
     */
    public function getBookingDetails($id)
    {
        return Booking::addSelect([
            'id',
            'client' => Client::select('name')->whereColumn('m_client.id', 'm_booking.client_id')->limit(1),
            'location_id' => Location::select('name')->whereColumn('m_location.id', 'm_booking.location_id')->limit(1),
            'book_date',
            'booking_status',
            'consignment_no',
            'quantity',
            'quantity_type'
        ])
            ->where('id', $id)
            ->first();
    }

    /**
     * Create new Booking with multiple data
     */
    public function createBooking($bookingData, $request)
    {
        return DB::transaction(function () use ($bookingData, $request) {
            $bookingDetails = [];
            foreach ($bookingData['bookings'] as $item) {
                $item['created_by'] = $request->user()->id;
                $item['booking_status'] = 1;

                $booking = Booking::create($item);
                $booking = $this->getBookingDetails($booking->id);

                array_push($bookingDetails, $booking);
            }

            return $bookingDetails;
        });
    }

    /**
     * Update booking with multiple data
     */
    public function updateBooking($bookingData, $request)
    {
        return DB::transaction(function () use ($bookingData, $request) {
            $updatedBooking = [];
            foreach ($bookingData['bookings'] as $item) {
                // Fetch booking
                $booking = Booking::where(['consignment_no' => $item['consignment_no'], 'booking_status' => 1])->first();
                if (empty($booking)) {
                    throw new NotFoundHttpException('No bookings found with these consignment no.');
                    break;
                } else if (!empty($booking->id)) {
                    // Modify value
                    $booking->location_id = $item['location_id'];
                    $booking->booking_status = 2;
                    $booking->updated_by = $request->user()->id;

                    // Save the data
                    $booking->save();

                    // Combine arr
                    array_push($updatedBooking, $this->getBookingDetails($booking->id));
                }
            }
            return $updatedBooking;
        });
    }
}
