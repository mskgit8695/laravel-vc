@extends('layouts.app')

@section('meta_title', "Booking Management - Verma Courier's Admin")

@section('content')
    <main class="main">
        <div class="wrap-box">
            {{-- Sidebar --}}
            @include('layouts.sidebar')
            {{-- End Sidebar --}}

            {{-- Content --}}
            <section class="right-section">
                <div class="scroller">
                    <section class="custom-tabs">
                        <div class="card border-btm">
                            <div class="card-header">
                                <div class="title-icon">
                                    <span><img src="{{ asset('img/icons/icon-01.png') }}" alt="report"></span> Report
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="container-fluid">
                                    <form action="{{ route('dashboard.filter-report-data') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-6 col-md-3 col-12">
                                                <div class="custom-field">
                                                    <input id="startDate" name="startDate" class="form-control"
                                                        type="date" placeholder="Pick from date"
                                                        value="{{ $startDate }}" />
                                                    <label for="startDate" class="form-label">From Date</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-md-3 col-12">
                                                <div class="custom-field">
                                                    <input id="endDate" name="endDate" class="form-control" type="date"
                                                        placeholder="Pick to date" value="{{ $endDate }}" />
                                                    <label for="endDate" class="form-label">To Date</label>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-md-3 col-12">
                                                <div class="custom-field select">
                                                    <select name="bookingType" id="bookingType" class="form-control">
                                                        <option value="">Type</option>
                                                        @foreach ($booking_status as $bs)
                                                            <option value="{{ $bs->id }}"
                                                                @if (!empty($bookingType) && $bookingType == $bs->id) selected @endif>
                                                                {{ $bs->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label for="bookingType" class="form-label">Type</label>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-md-3 col-12">
                                                <div class="custom-field">
                                                    <button class="btn btn-md btn-orange-outline"
                                                        type="submit">Search</button>
                                                    &nbsp;
                                                    <a href="javascript:void(0);"
                                                        onclick="getDownloadReport('excel')"><i><img
                                                                src="{{ asset('img/icons/download-01.png') }}"
                                                                alt="download excel" /></i></a>
                                                    &nbsp;
                                                    <a href="javascript:void(0);" onclick="getDownloadReport('pdf')"><i><img
                                                                src="{{ asset('img/icons/pdf-file.png') }}"
                                                                alt="download pdf" /></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

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
                                                            <th>Booking Date.</th>
                                                            <th>Client</th>
                                                            <th>Location</th>
                                                            <th>Quantity</th>
                                                            <th>Weights</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($bookings as $i => $b)
                                                            <tr>
                                                                <td>{{ $i + 1 }}</td>
                                                                <td>{{ $b->consignment_no }}</td>
                                                                <td>{{ $b->book_date_format }}</td>
                                                                <td>{{ $b->client }}</td>
                                                                <td>{{ $b->location }}</td>
                                                                <td>{{ $b->quantity }}</td>
                                                                <td>{{ $b->quantity_type }}</td>
                                                                <td>{{ get_booking_status($b->booking_status) }}</td>
                                                                <td>
                                                                    <a href="{{ route('bookings.edit', $b->id) }}">Edit</a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Consignment No.</th>
                                                            <th>Booking Date.</th>
                                                            <th>Client</th>
                                                            <th>Location</th>
                                                            <th>Quantity</th>
                                                            <th>Weights</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </tfoot>
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
    {{-- End Footer --}}

    <script type="text/javascript">
        new DataTable('#dataTable');

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

        // Get download report
        function getDownloadReport(reportType) {

        }
    </script>
@endsection
