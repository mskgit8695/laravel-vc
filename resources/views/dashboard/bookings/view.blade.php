@extends('layouts.app')

@section('meta_title', "Booking Details - Verma Courier's Admin")

@section('content')
    <main class="main">
        <div class="wrap-box">
            {{-- Sidebar --}}
            @include('layouts.sidebar')
            {{-- End Sidebar --}}

            {{-- Content --}}
            <section class="right-section">
                <div class="scroller">
                    <div class="page-wrap">
                        <div class="nav-back">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ $title ?? 'Booking Details' }}
                                    </li>
                                </ol>
                            </nav>
                            <div class="back-btn">
                                <a href="{{ url()->previous() }}" class="btn-link-orange"><span>&#10140;</span> Back</a>
                            </div>
                        </div>
                        <div class="custom-accordion">
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                                            aria-controls="panelsStayOpen-collapseOne">
                                            <div class="title-icon">
                                                <span>
                                                    <img src="{{ asset('img/icons/icon-01.png') }}"
                                                        alt="Consignment Details" />
                                                </span>
                                                Consignment Details
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="panelsStayOpen-headingOne">
                                        <div class="accordion-body bottom-border">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped rti-table">
                                                            <tbody>
                                                                <tr>
                                                                    <td><strong>Consignment No.</strong></td>
                                                                    <td>{{ $booking->consignment_no }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Booking Date.</strong></td>
                                                                    <td>{{ $booking->book_date_format }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Client</strong></td>
                                                                    <td>{{ $booking->client }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Location</strong></td>
                                                                    <td>{{ $booking->location }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Quantity</strong></td>
                                                                    <td>{{ $booking->quantity }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Weight</strong></td>
                                                                    <td>{{ $booking->quantity_type }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><strong>Status</strong></td>
                                                                    <td>{{ get_booking_status($booking->booking_status) }}
                                                                    </td>
                                                                </tr>
                                                                @if (!empty($booking->receiver_name))
                                                                    <tr>
                                                                        <td><strong>Delivery Date</strong></td>
                                                                        <td>{{ convert_date_format($booking->delivered_date) }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Recipient Name</strong></td>
                                                                        <td>{{ $booking->receiver_name }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Recipient Contact</strong></td>
                                                                        <td>{{ $booking->receiver_mobile }}</td>
                                                                    </tr>
                                                                    @if ($booking->photo)
                                                                        <tr>
                                                                            <td><strong>Uploaded POD</strong></td>
                                                                            <td>
                                                                                <a
                                                                                    href="{{ route('download.image', ['filename' => $booking->photo]) }}">Download</a>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="action-btn">
                                    <ul>
                                        <li>
                                            <a href="{{ url()->previous() }}"
                                                class="btn btn-lg btn-orange-outline">Back</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of page-wrap Div -->
                </div><!-- End of scroller Div -->
            </section>
        </div>
    </main>
    {{-- Footer --}}
    @include('layouts.footer')
    {{-- End Footer --}}
@endsection
