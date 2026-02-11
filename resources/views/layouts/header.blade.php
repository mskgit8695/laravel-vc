<header class="header">
    <div class="mob-menu-btn">
        <img src="{{ asset('img/menu.png') }}" alt="">
    </div>
    <div class="close-btn">
        <img src="{{ asset('img/close.png') }}" alt="">
    </div>
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-6">
                <a href="#" class="eci-v">Verma Couriers</a>
            </div>
            <div class="col-6">
                <div class="menu-hnd-btn">
                    <ul>
                        <li><a href="javascript:void(0)">Hello, {{ Auth::user()->first_name }}</a></li>
                        <li><a href="{{ route('logout') }}">Logout</a></li>
                        {{-- <li>
                            <div class="dropdown">
                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Hello, {{ Auth::user()->first_name }}
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                                </ul>
                            </div>
                        </li> --}}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
