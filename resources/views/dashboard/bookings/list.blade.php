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
                    <div class="page-wrap">
                        <div class="nav-back">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ $title ?? 'Booking Management' }}
                                    </li>
                                </ol>
                            </nav>
                            <div class="back-btn">
                                <a href="#" class="btn-link-orange"><span>&#10140;</span> Back</a>
                            </div>
                        </div>
                        <section>
                            <div class="card border-btm">
                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                <div class="card-header">
                                    <div class="title-icon">
                                        <span>
                                            <img src="{{ asset('img/icons/icon-01.png') }}" alt="">
                                        </span>
                                        {{ $title }}
                                    </div>
                                    {{-- <div class="add-btn">
                                        <a href="{{ route('bookings.create') }}" class="btn btn-orange">
                                            <i><img src="{{ asset('img/plus-white.png') }}" alt="add booking"></i>
                                            Add location
                                        </a>
                                    </div> --}}
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered custom-table">
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
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>
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
