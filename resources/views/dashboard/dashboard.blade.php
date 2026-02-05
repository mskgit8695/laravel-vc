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
                    <div class="upper-tiles">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="tile blue">
                                        <div class="icon"><img src="img/icons/dashbord/dash-01.png" alt="">
                                        </div>
                                        <div class="info">
                                            <h4>8879</h4>
                                            <h6>Total Application</h6>
                                        </div>
                                    </div><!-- End of tile Div -->
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="tile gold">
                                        <div class="icon"><img src="img/icons/dashbord/dash-02.png" alt="">
                                        </div>
                                        <div class="info">
                                            <h4>07</h4>
                                            <h6>Total Pending</h6>
                                        </div>
                                    </div><!-- End of tile Div -->
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="tile green">
                                        <div class="icon"><img src="img/icons/dashbord/dash-04.png" alt="">
                                        </div>
                                        <div class="info">
                                            <h4>8664</h4>
                                            <h6>Total Assigned</h6>
                                        </div>
                                    </div><!-- End of tile Div -->
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="tile red">
                                        <div class="icon"><img src="img/icons/dashbord/dash-03.png" alt="">
                                        </div>
                                        <div class="info">
                                            <h4>07</h4>
                                            <h6>Return </h6>
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
                                        aria-controls="pills-home" aria-selected="true">New Application</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-profile" type="button" role="tab"
                                        aria-controls="pills-profile" aria-selected="false">Returned From
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-contact" type="button" role="tab"
                                        aria-controls="pills-contact" aria-selected="false">Activity Log</button>
                                </li>
                            </ul>
                            <ul class="calender-fields">
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
                            </ul>
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
                                                            <th>SN.</th>
                                                            <th>Consign. No.</th>
                                                            <th>Date</th>
                                                            <th>Question</th>
                                                            <th>Mode </th>
                                                            <th>Status</th>
                                                            <th>Date of Pendancy</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1.</td>
                                                            <td>9723</td>
                                                            <td>15 June 2024</td>
                                                            <td>hhhh</td>
                                                            <td>Online</td>
                                                            <td title="active">Active</td>
                                                            <td>02</td>
                                                            <td><a href="#" class="">VIEW</a></td>
                                                        </tr>
                                                        <tr>
                                                            <td>2.</td>
                                                            <td>9723</td>
                                                            <td>15 June 2024</td>
                                                            <td>hhh</td>
                                                            <td>Online</td>
                                                            <td title="active">Active</td>
                                                            <td>03</td>
                                                            <td><a href="#" class="">VIEW</a></td>
                                                        </tr>

                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th>SN.</th>
                                                            <th>Consign. No.</th>
                                                            <th>Date</th>
                                                            <th>Question</th>
                                                            <th>Mode </th>
                                                            <th>Status</th>
                                                            <th>Date of Pendancy</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>


                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                aria-labelledby="pills-profile-tab">

                                Content 01
                            </div>
                            <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                                aria-labelledby="pills-contact-tab">

                                Content 02
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
