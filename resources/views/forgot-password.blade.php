@extends('layouts.app')

@section('meta_title', "Forgot password - Verma Courier's admin");

@section('content')
    <main class="main">
        <section class="login-box">
            <div class="container-fluid">
                <div class="shape-box">
                    <div class="login-wrap">
                        <div class="login-form">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            <div class="logo-v"></div>
                            <h5><b>Forgot password</b></h5>
                            <form action="{{ route('forgot-password') }}" class="form" method="POST" autocapitalize="off">
                                @csrf
                                <div class="custom-field @error('email') has-validation @enderror">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" placeholder="Enter email" value="{{ old('email') }}"
                                        required />
                                    <label for="email" class="form-label">Email</label>
                                    <span><img src="img/icons/envelope.png" alt="email"></span>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="sumt-btn">
                                    <button type="submit" class="btn btn-lg btn-orange w-100">Send Password Reset
                                        Link</button>
                                </div>
                            </form>
                        </div><!-- End of login-form Div -->
                    </div><!-- End of login-wrap Div -->
                </div>
            </div>
        </section>
    </main>
    <!-- Optional JavaScript; choose one of the two! -->
    <script src="js/jquery-3.7.1.min.js" type="text/javascript"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="js/bootstrap.bundle.min.js" type="text/javascript"></script>
    <!-- Option 3: Custom jQuery Lab -->
    <script src="js/custom-jQuery.js" type="text/javascript"></script>
@endsection
