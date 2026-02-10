@extends('layouts.app')

@section('meta_title', "Reset password - Verma Courier's admin");

@section('content')
    <header class="login-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-12"><a href="#" class="v-logo"></a></div>
            </div>
        </div>
    </header>
    <main class="main">
        <section class="login-box">
            <div class="container-fluid">
                <div class="shape-box">
                    <div class="login-wrap">
                        <div class="login-form">
                            <div class="logo-v"></div>
                            <h5><b>Reset password</b></h5>
                            <form action="{{ route('password.update') }}" class="form" method="POST" autocomplete="off">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="custom-field @error('email') has-validation @enderror">
                                    <input type="text" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" placeholder="Enter email"
                                        value="{{ old('email', $email) }}" required @readonly($email) />
                                    <label for="email" class="form-label">Email</label>
                                    <span><img src="{{ asset('img/icons/envelope.png') }}" alt="email"></span>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="custom-field @error('password') has-validation @enderror">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Enter password" required
                                        minlength="8" />
                                    <label for="password" class="form-label">Enter Password</label>
                                    <span><img src="{{ asset('img/icons/lock.png') }}" alt="password"></span>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="custom-field">
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="Confirm password" required
                                        minlength="8" />
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <span><img src="{{ asset('img/icons/lock.png') }}" alt="confirm password"></span>
                                </div>

                                <div class="sumt-btn">
                                    <button type="submit" class="btn btn-lg btn-orange w-100">Submit</button>
                                </div>
                            </form>
                        </div><!-- End of login-form Div -->
                    </div><!-- End of login-wrap Div -->
                </div>
            </div>
        </section>
    </main>
    {{-- <footer class="stickyFooter">

    </footer> --}}
    <!-- Optional JavaScript; choose one of the two! -->
    <script src="js/jquery-3.7.1.min.js" type="text/javascript"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="js/bootstrap.bundle.min.js" type="text/javascript"></script>
    <!-- Option 3: Custom jQuery Lab -->
    <script src="js/custom-jQuery.js" type="text/javascript"></script>
@endsection
