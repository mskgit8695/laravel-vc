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
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // Draft booking
    public function index()
    {
        //$bookings = Booking::all(['id', 'client_id', 'location_id', 'book_date', 'booking_status', 'consignment_no', 'quantity', 'quantity_type','party_name','city_address'])->where('created_by', '1');

        // $bookings = Booking::where('created_by', auth()->id())->where('booking_status', Booking::DRAFT_BOOKING)
        //     ->get([
        //         'id',
        //         'client_id',
        //         'location_id',
        //         'book_date',
        //         'booking_status',
        //         'consignment_no',
        //         'quantity',
        //         'weight',
        //         'quantity_type',
        //         'party_name',
        //         'city_address'
        //     ]);
        $bookings = Booking::where('created_by', auth()->id())->where('booking_status', Booking::DRAFT_BOOKING)->getBookings()->get();
        return response()->json($bookings);
    }

    // final booking
    public function getFinalBooking()
    {
        /*$bookings = Booking::where('created_by', auth()->id())->where('booking_status', Booking::BOOKING)
            ->get([
                'id',
                'client_id',
                'location_id',
                'book_date',
                'booking_status',
                'consignment_no',
                'quantity',
                'quantity_type',
                'weight',
                'party_name',
                'city_address'
            ]);*/
        $bookings = Booking::where('created_by', auth()->id())->where('booking_status', Booking::BOOKING)->getBookings()->get();
        return response()->json($bookings);
    }

    // Draft dispath booking
    public function getDraftDispatch()
    {
        // $bookings = Booking::where('created_by', auth()->id())->where('booking_status', Booking::DRAFT_DISPATCHED)
        //     ->get([
        //         'id',
        //         'client_id',
        //         'location_id',
        //         'book_date',
        //         'booking_status',
        //         'consignment_no',
        //         'quantity',
        //         'weight',
        //         'quantity_type',
        //         'party_name',
        //         'city_address'
        //     ]);

        $bookings = Booking::where('created_by', auth()->id())->where('booking_status', Booking::DRAFT_DISPATCHED)->getBookings()->get();
        return response()->json($bookings);
    }

    // final dispath booking
    public function getFinalDispatch()
    {

        // $bookings = Booking::where('created_by', auth()->id())->where('booking_status', Booking::DISPATCHED)
        //     ->get([
        //         'id',
        //         'client_id',
        //         'location_id',
        //         'book_date',
        //         'booking_status',
        //         'consignment_no',
        //         'quantity',
        //         'weight',
        //         'quantity_type',
        //         'party_name',
        //         'city_address'
        //     ]);

        $bookings = Booking::where('created_by', auth()->id())->where('booking_status', Booking::DISPATCHED)->getBookings()->get();

        return response()->json($bookings);
    }

    // get delivery details
    public function deliverBookingDetails(string $consignment_no)
    {

        $booking = Booking::leftJoin('m_booking_received', 'm_booking.id', '=', 'm_booking_received.book_id')
            ->where('m_booking.consignment_no', $consignment_no)
            ->where('m_booking.booking_status', Booking::DELIVERED)
            ->select('party_name', 'city_address', 'receiver_name', 'receiver_mobile', 'photo', 'updated_on as delivered_date')
            ->getBookings()
            ->first();


        return response()->json($booking);
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

    public function finalBooking(Request $request)
    {
        try {
            $rules = [
                'bookings' => 'required|array|min:1|max:10',
                'bookings.*.consignment_no' => 'required|string|alpha_num|max:10|exists:m_booking,consignment_no',
                'bookings.*.location_id' => 'required|integer',
                'bookings.*.client_id' => 'required|integer',
                'bookings.*.city_address' => 'required|string|max:255',
                'bookings.*.party_name' => 'required|string|max:255',
                'bookings.*.quantity' => 'required|numeric',
                'bookings.*.weight' => 'required|numeric',
                'bookings.*.quantity_type' => 'required|string|max:50',
            ];

            $validated = $request->validate($rules);

            $booking = $this->updateFinalBooking($validated);

            return response()->json([
                'message' => 'Booking updated successfully',
                'booking' => $booking
            ]);
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
     * Display the specified resource.
     */
    public function show(string $consignment_no)
    {
        try {
            $booking = Booking::where(['consignment_no' => $consignment_no, 'created_by' => auth()->id()])->getBookings()->first();
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
    public function dispatchDraftBooking(Request $request)
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

            return response()->json(['message' => 'Draft Booking dispatched successfully', 'booking' => $booking]);
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
            $booking = Booking::where(['consignment_no' => $consignment_no, 'booking_status' => Booking::DISPATCHED])->first();

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
            $booking->booking_status = Booking::DELIVERED;
            $booking->updated_by = $request->user()->id;
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
        // return Booking::addSelect([
        //     'id',
        //     'client' => Client::select('name')->whereColumn('m_client.id', 'm_booking.client_id')->limit(1),
        //     'location_id' => Location::select('name')->whereColumn('m_location.id', 'm_booking.location_id')->limit(1),
        //     'book_date',
        //     'booking_status',
        //     'city_address',
        //     'party_name',
        //     'consignment_no',
        //     'quantity',
        //     'weight',
        //     'quantity_type'
        // ])
        //     ->where('id', $id)
        //     ->first();

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

                $exists = Booking::where('consignment_no', $item['consignment_no'])->exists();
                if ($exists) {
                    throw ValidationException::withMessages([
                        'consignment_no' => ['Consignment number already exists.']
                    ]);
                }

                $item['created_by'] = $request->user()->id;
                // $item['booking_status'] = 1;

                $item['booking_status'] = Booking::DRAFT_BOOKING; //draft booking

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
    /*
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
                    $booking->booking_status = 5;
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
	*/

    public function updateBooking($bookingData, $request)
    {
        return DB::transaction(function () use ($bookingData, $request) {

            $updatedBooking = [];
            $notFoundConsignments = [];

            foreach ($bookingData['bookings'] as $item) {

                $booking = Booking::where([
                    'consignment_no' => $item['consignment_no'],
                    'booking_status' => Booking::BOOKING
                ])->first();

                if (!$booking) {
                    $notFoundConsignments[] = $item['consignment_no'];
                    continue; // continue checking others
                }

                // Update booking
                $booking->location_id = $item['location_id'];
                $booking->booking_status = Booking::DRAFT_DISPATCHED;
                $booking->updated_by = $request->user()->id;
                $booking->save();

                $updatedBooking[] = $this->getBookingDetails($booking->id);
            }

            // If any consignment numbers were not found
            if (!empty($notFoundConsignments)) {
                throw new \Exception(
                    'The following consignment numbers were not found or not eligible for dispatch: ' .
                        implode(', ', $notFoundConsignments)
                );
            }

            return $updatedBooking;
        });
    }


    // final booking
    /*
	public function updateFinalBooking($bookingData)
	{
    return DB::transaction(function () use ($bookingData) {

        $updatedBooking = [];

        foreach ($bookingData['bookings'] as $item) {

            $booking = Booking::where('consignment_no', $item['consignment_no'])
                ->where('booking_status', 4)
                ->lockForUpdate()
                ->first();

            if (!$booking) {
               // throw new NotFoundHttpException("Booking not found or not in booking draft state: {$item['consignment_no']}");

				//return response()->json(['message' => "Booking not found or not in booking draft state: {$item['consignment_no']}"], 400);
				//break;
				return response()-> json(['message' => "Booking not found or not in booking draft state: {$item['consignment_no']}"], 400);
            }
			else{
            $booking->update([
                'client_id'     => $item['client_id'],
                'location_id'   => $item['location_id'],
                'city_address'  => $item['city_address'],
                'party_name'    => $item['party_name'],
                'quantity'      => $item['quantity'],
                'quantity_type' => $item['quantity_type'],
                'booking_status'=> 1,
                'updated_by'    => auth()->id(),
            ]);

            $updatedBooking[] = $this->getBookingDetails($booking->id);
			}
        }

        return $updatedBooking;
    });
}
*/

    public function updateFinalBooking($bookingData)
    {
        return DB::transaction(function () use ($bookingData) {

            $updatedBooking = [];

            // Collect all consignment numbers from request
            $consignments = collect($bookingData['bookings'])
                ->pluck('consignment_no')
                ->toArray();

            // Fetch all draft bookings in ONE query
            $bookings = Booking::whereIn('consignment_no', $consignments)
                ->where('booking_status', Booking::DRAFT_BOOKING)
                ->lockForUpdate()
                ->get()
                ->keyBy('consignment_no');

            // Find missing consignments
            $missing = array_diff($consignments, $bookings->keys()->toArray());

            if (!empty($missing)) {
                throw new \Exception(
                    'Booking not found or not in Booking draft state for consignment no: ' . implode(', ', $missing)
                );
            }

            // Update bookings
            foreach ($bookingData['bookings'] as $item) {

                $booking = $bookings[$item['consignment_no']];

                $booking->update([
                    'client_id'     => $item['client_id'],
                    'location_id'   => $item['location_id'],
                    'city_address'  => $item['city_address'],
                    'party_name'    => $item['party_name'],
                    'quantity'      => $item['quantity'],
                    'weight'      => $item['weight'],
                    'quantity_type' => $item['quantity_type'],
                    'booking_status' => Booking::BOOKING,
                    'updated_by'    => auth()->id(),
                ]);

                $updatedBooking[] = $this->getBookingDetails($booking->id);
            }

            return $updatedBooking;
        });
    }




    /**
     *  Final Dispatch the specified booking to in-transit.
     */
    public function dispatchFinalBooking(Request $request)
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
            $booking = $this->updateFinalDispatchBooking($validated, $request);

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
     * Update booking with multiple data
     */
    /*
    public function updateFinalDispatchBooking($bookingData, $request)
    {
        return DB::transaction(function () use ($bookingData, $request) {
            $updatedBooking = [];
            foreach ($bookingData['bookings'] as $item) {
                // Fetch booking
                $booking = Booking::where(['consignment_no' => $item['consignment_no'], 'booking_status' => 5])->first();
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
	*/

    public function updateFinalDispatchBooking($bookingData, $request)
    {
        return DB::transaction(function () use ($bookingData, $request) {

            $updatedBooking = [];
            $notFoundConsignments = [];

            foreach ($bookingData['bookings'] as $item) {

                $booking = Booking::where([
                    'consignment_no' => $item['consignment_no'],
                    'booking_status' => Booking::DRAFT_DISPATCHED
                ])->first();

                if (!$booking) {
                    $notFoundConsignments[] = $item['consignment_no'];
                    continue; // check remaining consignments
                }

                // Update booking
                $booking->location_id = $item['location_id'];
                $booking->booking_status = Booking::DISPATCHED;
                $booking->updated_by = $request->user()->id;
                $booking->save();

                $updatedBooking[] = $this->getBookingDetails($booking->id);
            }

            // If any not found, throw error with all numbers
            if (!empty($notFoundConsignments)) {
                throw new \Exception(
                    'The following consignment numbers were not found or not eligible for final dispatch: ' .
                        implode(', ', $notFoundConsignments)
                );
            }

            return $updatedBooking;
        });
    }

    public function getPhoto($filename)
    {
        // Construct the full path within the storage disk
        $filePath = 'public/booking-pod/' . $filename;

        // Check if the file exists
        if (Storage::disk('local')->exists($filePath)) {
            // Return the file as a download
            return Storage::download($filePath, $filename);
        } else {
            abort(404, 'Image not found');
        }
    }

    /**
     * Get draft booking list by client id
     */
    public function getDraftBookingByClientId(int $clientId)
    {
        // Check if the client is exists in system
        $isClientExists = Client::where(['id' => $clientId, 'status' => 1])->first();
        if (!$isClientExists) {
            throw new \Exception('The client details not found in our system!');
        }

        // Fetch the draft booking details by client id and booking status as a draft booking.
        $draft_bookings = Booking::where(['client_id' => $clientId, 'booking_status' => Booking::DRAFT_BOOKING, 'created_by' => auth()->id()])->getBookings()->get();

        // Return response
        return response()->json($draft_bookings);
    }

    /**
     * Get draft dispatch booking list by client id
     */
    public function getDraftDispatchByClientId(int $clientId)
    {
        // Check if the client is exists in system
        $isClientExists = Client::where(['id' => $clientId, 'status' => 1])->first();
        if (!$isClientExists) {
            throw new \Exception('The client details not found in our system!');
        }

        // Fetch the draft dispatch details by client id and booking status as a draft dispatch.
        $draft_dispatch = Booking::where(['client_id' => $clientId, 'booking_status' => Booking::DRAFT_DISPATCHED, 'created_by' => auth()->id()])->getBookings()->get();

        // Return response
        return response()->json($draft_dispatch);
    }

    /**
     * Get all pending bookings that are supposed to be dispatched
     */
    public function getPendingDispatch()
    {
        // Fetch the pending bookings to be dispatched
        $pending_bookings = Booking::where(['booking_status' => Booking::BOOKING, 'created_by' => auth()->id()])->getBookings()->get();

        // Return response
        return response()->json($pending_bookings);
    }
}
