@extends('layouts.app')

@section('meta_title', "Edit booking - Verma Courier's Admin")

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
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">Booking
                                            Managment</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Edit Booking
                                    </li>
                                </ol>
                            </nav>
                            <div class="back-btn">
                                <a href="#" class="btn-link-orange" onclick="history.back()"><span>&#10140;</span>
                                    Back</a>
                            </div>
                        </div>
                        <form action="{{ route('bookings.update', $booking->id) }}" method="POST" autocomplete="off">
                            @csrf
                            @method('PATCH')
                            <section>
                                <div class="card border-btm">
                                    <div class="card-header">
                                        <div class="title-icon">
                                            <span><img src="{{ asset('img/icons/icon-01.png') }}"
                                                    alt="add edit booking"></span>
                                            Edit Booking
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-sm-6 col-md-4 col-12">
                                                    <div
                                                        class="custom-field @error('consignment_no') has-validation @enderror">
                                                        <input type="text"
                                                            class="form-control @error('consignment_no') is-invalid @enderror"
                                                            id="consignment_no" name="consignment_no"
                                                            placeholder="Enter consignment no" required
                                                            value="{{ old('consignment_no', $booking->consignment_no) }}"
                                                            @readonly($booking->consignment_no != '') />
                                                        <label for="consignment_no" class="form-label">Consignment
                                                            No.</label>
                                                        @error('consignment_no')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-4 col-12">
                                                    <div class="custom-field @error('book_date') has-validation @enderror">
                                                        <input type="text"
                                                            class="form-control @error('book_date') is-invalid @enderror"
                                                            id="book_date" name="book_date" placeholder="Enter booking date"
                                                            required value="{{ old('book_date', $booking->book_date) }}"
                                                            @readonly($booking->book_date != '0000-00-00') />
                                                        <label for="book_date" class="form-label">Booking
                                                            Date</label>
                                                        @error('book_date')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-4 col-12">
                                                    <div
                                                        class="custom-field select @error('booking_status') has-validation @enderror">
                                                        <select name="booking_status" id="booking_status"
                                                            class="form-control @error('booking_status') is-invalid @enderror"
                                                            required @if ($booking->booking_status == 3) disabled @endif>
                                                            <option value="">-- Booking Status --</option>
                                                            @foreach ($booking_status as $i => $b)
                                                                <option value="{{ $b->id }}"
                                                                    @if ($b->id == $booking->booking_status) selected @endif>
                                                                    {{ $b->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <label for="booking_status" class="form-label">Booking
                                                            Status</label>
                                                        @error('booking_status')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <div class="action-btn">
                                <ul>
                                    <li>
                                        <a class="btn btn-lg btn-orange-outline" href="#"
                                            onclick="history.back()">Back</a>
                                    </li>
                                    <li>
                                        <button class="btn btn-lg btn-orange-outline" type="submit">
                                            Update Booking
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </form>
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
