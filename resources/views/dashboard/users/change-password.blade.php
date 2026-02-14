@extends('layouts.app')

@section('meta_title', "Change password - Verma Courier's Admin")

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
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard.users') }}">User Managment</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Change password
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <form action="{{ route('dashboard.update-password') }}" method="POST" autocomplete="off">
                            @csrf
                            <section>
                                <div class="card border-btm">
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    <div class="card-header">
                                        <div class="title-icon">
                                            <span><img src="{{ asset('img/icons/icon-01.png') }}" alt="add new user"></span>
                                            Change password
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-sm-6 col-md-4 col-12">
                                                    <div
                                                        class="custom-field @error('current_password') has-validation @enderror">
                                                        <input type="password"
                                                            class="form-control @error('current_password') is-invalid @enderror"
                                                            id="current_password" name="current_password"
                                                            placeholder="Enter current password" required minlength="8" />
                                                        <label for="current_password" class="form-label">Current
                                                            Password</label>
                                                        @error('current_password')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-4 col-12">
                                                    <div class="custom-field @error('password') has-validation @enderror">
                                                        <input type="password"
                                                            class="form-control @error('password') is-invalid @enderror"
                                                            id="password" name="password" placeholder="Enter password"
                                                            required minlength="8" />
                                                        <label for="password" class="form-label">Password</label>
                                                        @error('password')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-4 col-12">
                                                    <div
                                                        class="custom-field @error('password_confirmation') has-validation @enderror">
                                                        <input type="password"
                                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                                            id="password_confirmation" name="password_confirmation"
                                                            placeholder="Enter confirm password" required minlength="8" />
                                                        <label for="password_confirmation" class="form-label">Confirm
                                                            Password</label>
                                                        @error('password_confirmation')
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
                                        <button class="btn btn-lg btn-orange-outline" type="submit">
                                            Update Password
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
