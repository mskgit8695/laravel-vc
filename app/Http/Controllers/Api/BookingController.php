<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use PDOException;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all bookings
        $bookings = Booking::getConsignmentStatus()
            ->filter(['bookingType' => 1])
            ->select('m_booking_status.name as status')
            ->getBookings()
            ->get();

        // Response
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
            $booking = Booking::where(['consignment_no' => $consignment_no])
                ->getBookings()
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
            $bookingResult = $this->updateBooking($validated, $request);

            // Not Found
            $notFound = implode(',', $bookingResult['not_found'] ?? []);

            // Processed
            $alreadyProcessed = collect($bookingResult['already_processed'] ?? [])
                ->map(function ($item) {
                    [$consignmentNo, $status] = explode(' ', $item);
                    return "The consignment no {$consignmentNo} is already " . strtolower($status);
                })
                ->toArray();

            // Dispatch booking response
            $booking = [
                'not_found' => $notFound,
                'processed' => $alreadyProcessed,
                'dispatched' => $bookingResult['dispatched'] ?? []
            ];

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
        $validated = $request->validate([
            'receiver_name' => 'required|string|max:100',
            'receiver_mobile' => 'required|integer|digits:10',
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg',
            'comment' => 'sometimes|string|max:255',
        ]);

        // Verify booking with consignment number and in-transit status
        $booking = Booking::where('consignment_no', $consignment_no)->first();
        if (!$booking) {
            return response()->json(['message' => 'No booking found with consignment number: ' . $consignment_no], 404);
        }

        if ($booking->isBlocked()) {
            return response()->json(['message' => 'The status of consignment number is: ' . get_booking_status($booking->booking_status)], 400);
        }

        // Get user id
        $user_id = $request->user()->id;

        // Updating photo name with booking id
        $photo = null;
        if (isset($validated['photo'])) {
            $photo = time() . '-' . $booking->id . '.' . $request->file('photo')->extension();
            $request->file('photo')->storeAs('booking-pod', $photo, 'public');
        }

        // Updating previous all previous delivery data
        DB::table('m_booking_received')->where('book_id', $booking->id)->update(['status' => 0, 'updated_by' => $user_id]);

        // Update the booking with receiver details
        DB::table('m_booking_received')->insert([
            'book_id' => $booking->id,
            'receiver_name' => $validated['receiver_name'],
            'receiver_mobile' => $validated['receiver_mobile'],
            'photo' => $photo,
            'status' => '1',
            'created_by' => $user_id
        ]);

        // Update the booking with delivered status
        $booking->update([
            'booking_status' => Booking::STATUS_DELIVERED,
            'updated_by' => $user_id
        ]);

        // Fetch Updated
        $booking = $this->getBookingDetails($booking->id);

        return response()->json(['message' => 'Booking delivered successfully', 'booking' => $booking]);
    }


    /**
     * Get Booking Details
     */
    public function getBookingDetails($id)
    {
        return Booking::where('id', $id)->getBookings()->first();
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
                $item['booking_status'] = Booking::STATUS_BOOKED;

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
            $updatedBooking = [
                'not_found'   => [],
                'already_processed' => [],
                'dispatched'  => [],
            ];

            $consignments = collect($bookingData['bookings'])
                ->pluck('consignment_no')
                ->toArray();

            // Fetch all bookings at once
            $bookings = Booking::whereIn('consignment_no', $consignments)
                ->get()
                ->keyBy('consignment_no');

            foreach ($bookingData['bookings'] as $item) {
                // Fetch booking
                $booking = $bookings->get($item['consignment_no']);

                // Not found
                if (!$booking) {
                    $updatedBooking['not_found'][] = $item['consignment_no'];
                    continue;
                }

                // Already dispatched or delivered
                if ($booking->isAlreadyProcessed()) {
                    $updatedBooking['already_processed'][] = $item['consignment_no'] . ' ' . get_booking_status($booking->booking_status);
                    continue;
                }

                // Only update if consignment is booked
                if ($booking->canDispatched()) {
                    // Update the bookings
                    $booking->update([
                        'location_id'    => $item['location_id'],
                        'booking_status' => Booking::STATUS_DISPATCHED,
                        'updated_by'     => $request->user()->id,
                    ]);

                    // Update the dispatched arr
                    $updatedBooking['dispatched'][] = $this->getBookingDetails($booking->id);
                }
            }
            return $updatedBooking;
        });
    }
}
