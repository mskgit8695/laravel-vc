<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Exports\ReportExport;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class BookingController extends Controller
{
    /**
     * Report service addon in construct
     */
    public function __construct(
        protected ReportService $reportService
    ) {}

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
        // Fetch booking data
        $booking = Booking::leftJoin('m_booking_received', 'm_booking.id', '=', 'm_booking_received.book_id')
            ->where('m_booking.id', $id)
            ->select('party_name', 'city_address', 'receiver_name', 'receiver_mobile', 'photo', 'm_booking_received.updated_on as delivered_date')
            ->getBookings()
            ->first();

        // Render edit booking
        return view('dashboard.bookings.view', ['booking' => $booking]);
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
        $validated['updated_by'] = $request->user()->id;

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

    /**
     * Generate report based on input request
     */
    public function generate_report(Request $request)
    {
        // Report type
        $type = $request->input('report_type');
        // Fetch data
        $data = $this->reportService->bookingReport($request->all());
        if ($type === 'pdf') {
            // Load view
            $pdf = Pdf::loadView('dashboard.bookings.report', compact('data'));
            // Download
            return $pdf->download('booking-report-' . time() . '.pdf');
        } else if ($type === 'excel') {
            return Excel::download(new ReportExport($data), 'booking-report-' . time() . '.xlsx');
        } else {
            throw new Exception('The report type is not available to download');
        }
    }

    public function download($filename)
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
     * Prepare Dashboard for admin or stack holder
     */
    public function dashboard()
    {
        // Booking statatics
        $booking_statatics = DB::table('m_booking')->select(DB::raw('count(id) as total_booking'))->addSelect([
            DB::raw('sum(case when booking_status="1" then 1 else 0 end) as total_pending'),
            DB::raw('sum(case when booking_status="2" then 1 else 0 end) as total_dispatch'),
            DB::raw('sum(case when booking_status="3" then 1 else 0 end) as total_delivery')
        ])->first();

        // Bookings
        $bookings = Booking::where('booking_status', Booking::BOOKING)->getBookings()->latest()->get();
        // Dispatch
        $dispatch = Booking::where('booking_status', Booking::DISPATCHED)->getBookings()->latest()->get();
        // Delivery
        $delivery = Booking::where('booking_status', Booking::DELIVERED)->getBookings()->latest()->get();
        // Render
        return view('dashboard.dashboard', ['statatics' => $booking_statatics, 'bookings' => $bookings, 'dispatch' => $dispatch, 'delivery' => $delivery]);
    }
}
