@extends('layouts.app')

@section('meta_title', "Register - Verma Courier's admin");

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
                            <h5><b>Create an account</b></h5>
                            <form action="{{ route('register') }}" class="form" method="POST" autocomplete="off">
                                @csrf
                                <div class="custom-field @error('fullname') has-validation @enderror">
                                    <input type="text" class="form-control @error('fullname') is-invalid @enderror"
                                        id="fullname" name="fullname" placeholder="Enter Full Name"
                                        value="{{ old('fullname') }}" required />
                                    <label for="fullname" class="form-label">Full Name</label>
                                    <span><img src="img/icons/envelope.png" alt="full name"></span>
                                    @error('fullname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

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

                                <div class="custom-field @error('password') has-validation @enderror">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Enter password" required
                                        minlength="8" />
                                    <label for="password" class="form-label">Enter Password</label>
                                    <span><img src="img/icons/lock.png" alt="password"></span>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="custom-field">
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="Confirm password" required
                                        minlength="8" />
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <span><img src="img/icons/lock.png" alt="password"></span>
                                </div>

                                <div class="custom-field @error('employee_id') has-validation @enderror">
                                    <input type="text" class="form-control @error('employee_id') is-invalid @enderror"
                                        id="employee_id" name="employee_id" placeholder="Enter Employee ID"
                                        value="{{ old('employee_id') }}" required />
                                    <label for="employee_id" class="form-label">Employee ID</label>
                                    <span><img src="img/icons/envelope.png" alt="employee id"></span>
                                    @error('employee_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="sumt-btn">
                                    <button type="submit" class="btn btn-lg btn-orange w-100">Submit</button>
                                </div>

                                <p>
                                    if you have an account? <a href="{{ url('/login') }}">Login here</a>
                                </p>
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
