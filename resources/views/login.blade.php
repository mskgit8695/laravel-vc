<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="fonts/FontAwesome/FontAwesome.6.2.1.css" />
    <title>Login - Verma Courier's Admin</title>
</head>

<body class="login-banner">
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
                            @if (Session::has('success'))
                                <div class="alert alert-success">{{ Session::get('success') }}</div>
                            @endif
                            @if (Session::has('error'))
                                <div class="alert alert-danger">{{ Session::get('error') }}</div>
                            @endif
                            <div class="logo-v"></div>
                            <h5><b>Login to Verma Courier's Admin</b></h5>
                            <form action="{{ route('login') }}" class="form" method="POST" autocapitalize="off">
                                @csrf
                                <div class="custom-field @error('email') has-validation @enderror">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" placeholder="Enter email"
                                        value="{{ old('email') }}" />
                                    <label for="email" class="form-label">Email</label>
                                    <span><img src="img/icons/envelope.png" alt="email"></span>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="custom-field">
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Enter password" required minlength="8" />
                                    <label for="password" class="form-label">Enter Password</label>
                                    <span><img src="img/icons/lock.png" alt="password"></span>
                                </div>

                                <div class="sumt-btn">
                                    <button type="submit" class="btn btn-lg btn-orange w-100">Submit</button>
                                </div>

                                <p>
                                    Don't have an account? <a href="{{ url('/register') }}">Register here</a>
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
</body>

</html>
