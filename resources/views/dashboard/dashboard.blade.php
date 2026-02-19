@extends('layouts.app')

@section('meta_title', "Dashboard - Verma Courier's Admin")

@section('content')
    <main class="main">
        <div class="wrap-box">
            {{-- Sidebar --}}
            @include('layouts.sidebar')
            {{-- Content --}}
            <section class="right-section">
                <div class="scroller">
                    {{-- <h1 style="text-align: center; margin-top: 15%">Welcome to the Verma Couriers</h1> --}}
                    <div class="upper-tiles">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="tile blue">
                                        <div class="icon"><img src="img/icons/dashbord/dash-01.png" alt="">
                                        </div>
                                        <div class="info">
                                            <h4>{{ $statatics->total_booking }}</h4>
                                            <h6>Total Booking</h6>
                                        </div>
                                    </div><!-- End of tile Div -->
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="tile gold">
                                        <div class="icon"><img src="img/icons/dashbord/dash-02.png" alt="">
                                        </div>
                                        <div class="info">
                                            <h4>{{ $statatics->total_pending }}</h4>
                                            <h6>Total Pending</h6>
                                        </div>
                                    </div><!-- End of tile Div -->
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="tile green">
                                        <div class="icon"><img src="img/icons/dashbord/dash-04.png" alt="">
                                        </div>
                                        <div class="info">
                                            <h4>{{ $statatics->total_dispatch }}</h4>
                                            <h6>Total Dispatched</h6>
                                        </div>
                                    </div><!-- End of tile Div -->
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="tile red">
                                        <div class="icon"><img src="img/icons/dashbord/dash-03.png" alt="">
                                        </div>
                                        <div class="info">
                                            <h4>{{ $statatics->total_delivery }}</h4>
                                            <h6>Total Delivered</h6>
                                        </div>
                                    </div><!-- End of tile Div -->
                                </div>
                            </div>
                        </div>
                    </div><!-- End of upper-titles Div -->
                    <section class="custom-tabs">
                        <div class="dateCalender-tabs">
                            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-home" type="button" role="tab"
                                        aria-controls="pills-home" aria-selected="true">Booking</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-dispatch-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-dispatch" type="button" role="tab"
                                        aria-controls="pills-dispatch" aria-selected="false">Dispatch
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-delivery-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-delivery" type="button" role="tab"
                                        aria-controls="pills-delivery" aria-selected="false">Delivery</button>
                                </li>
                            </ul>
                            {{-- <ul class="calender-fields">
                                <li>
                                    <!-- <label for="startDate">Start</label> -->
                                    <input id="startDate" class="form-control" type="date" />
                                    <!-- <span id="startDateSelected"></span> -->
                                </li>
                                <li>
                                    <!-- <label for="endDate">End</label> -->
                                    <input id="endDate" class="form-control" type="date" />
                                    <!-- <span id="endDateSelected"></span> -->
                                </li>
                            </ul> --}}
                        </div><!-- End of dateCalender Div -->
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                aria-labelledby="pills-home-tab">
                                <div class="custom-dataTable">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table id="dataTable" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Consignment No.</th>
                                                            <th>Booking Date</th>
                                                            <th>Client</th>
                                                            <th>Location</th>
                                                            <th>Quantity</th>
                                                            <th>Weight</th>
                                                            <th>Name</th>
                                                            <th>Address</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($bookings as $i => $b)
                                                            <tr>
                                                                <td>{{ $i + 1 }}</td>
                                                                <td>{{ $b->consignment_no }}</td>
                                                                <td>{{ $b->book_date_format }}</td>
                                                                <td>{{ $b->client_name }}</td>
                                                                <td>{{ $b->location_name }}</td>
                                                                <td>{{ $b->quantity }} </td>
                                                                <td>{{ $b->weight }} {{ $b->quantity_type }}</td>
                                                                <td>{{ $b->party_name }}</td>
                                                                <td>{{ $b->city_address }}</td>
                                                                <td>{{ get_booking_status($b->booking_status) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-dispatch" role="tabpanel"
                                aria-labelledby="pills-dispatch-tab">
                                <div class="custom-dataTable">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table id="dataTable-dispatch" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Consignment No.</th>
                                                            <th>Booking Date</th>
                                                            <th>Client</th>
                                                            <th>Location</th>
                                                            <th>Quantity</th>
                                                            <th>Weight</th>
                                                            <th>Name</th>
                                                            <th>Address</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($dispatch as $j => $dt)
                                                            <tr>
                                                                <td>{{ $j + 1 }}</td>
                                                                <td>{{ $dt->consignment_no }}</td>
                                                                <td>{{ $dt->book_date_format }}</td>
                                                                <td>{{ $dt->client_name }}</td>
                                                                <td>{{ $dt->location_name }}</td>
                                                                <td>{{ $dt->quantity }} </td>
                                                                <td>{{ $dt->weight }} {{ $dt->quantity_type }}</td>
                                                                <td>{{ $dt->party_name }}</td>
                                                                <td>{{ $dt->city_address }}</td>
                                                                <td>{{ get_booking_status($dt->booking_status) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-delivery" role="tabpanel"
                                aria-labelledby="pills-delivery-tab">
                                <div class="custom-dataTable">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table id="dataTable-delivery" class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Consignment No.</th>
                                                            <th>Booking Date</th>
                                                            <th>Client</th>
                                                            <th>Location</th>
                                                            <th>Quantity</th>
                                                            <th>Weight</th>
                                                            <th>Name</th>
                                                            <th>Address</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($delivery as $k => $dv)
                                                            <tr>
                                                                <td>{{ $k + 1 }}</td>
                                                                <td>{{ $dv->consignment_no }}</td>
                                                                <td>{{ $dv->book_date_format }}</td>
                                                                <td>{{ $dv->client_name }}</td>
                                                                <td>{{ $dv->location_name }}</td>
                                                                <td>{{ $dv->quantity }} </td>
                                                                <td>{{ $dv->weight }} {{ $dv->quantity_type }}</td>
                                                                <td>{{ $dv->party_name }}</td>
                                                                <td>{{ $dv->city_address }}</td>
                                                                <td>{{ get_booking_status($dv->booking_status) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div><!-- End of scroller Div -->
            </section>
        </div>
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

    <script type="text/javascript">
        new DataTable('#dataTable');
        new DataTable('#dataTable-dispatch');
        new DataTable('#dataTable-delivery');

        // Form To Date Calander Function
        let startDate = document.getElementById('startDate');
        let endDate = document.getElementById('endDate');

        startDate.addEventListener('change', (e) => {
            let startDateVal = e.target.value
            document.getElementById('startDateSelected').innerText = startDateVal
        });

        endDate.addEventListener('change', (e) => {
            let endDateVal = e.target.value
            document.getElementById('endDateSelected').innerText = endDateVal
        });
    </script>
@endsection
