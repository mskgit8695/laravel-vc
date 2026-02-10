<aside class="left-section">
    <nav class="menu">
        {{-- <div class="v-logo"><a href="#"></a></div> --}}
        <ul>
            <li>
                <a href="{{ route('dashboard') }}" class="@if (url()->current() == route('dashboard')) active @endif">
                    <i>
                        <img src="{{ asset('img/icons/dashbord/dashboard.png') }}" alt="">
                    </i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('dashboard.users') }}" class="@if (str_contains(url()->current(), 'users')) active @endif">
                    <i>
                        <img src="{{ asset('img/icons/dashbord/dash-04.png') }}" alt="">
                    </i>
                    User Managment
                </a>
            </li>
            <li>
                <a href="{{ route('clients.index') }}" class="@if (str_contains(url()->current(), 'clients')) active @endif">
                    <i>
                        <img src="{{ asset('img/icons/dashbord/dash-04.png') }}" alt="">
                    </i>
                    Client Managment
                </a>
            </li>
            <li>
                <a href="{{ route('locations.index') }}" class="@if (str_contains(url()->current(), 'locations')) active @endif">
                    <i>
                        <img src="{{ asset('img/icons/dashbord/dash-04.png') }}" alt="">
                    </i>
                    Location Managment
                </a>
            </li>
            <li>
                <a href="{{ route('roles.index') }}" class="@if (str_contains(url()->current(), 'roles')) active @endif">
                    <i>
                        <img src="{{ asset('img/icons/dashbord/dash-04.png') }}" alt="">
                    </i>
                    Role Managment
                </a>
            </li>
            <li>
                <a href="{{ route('bookings.index') }}" class="@if (str_contains(url()->current(), 'bookings')) active @endif">
                    <i>
                        <img src="{{ asset('img/icons/dashbord/dash-04.png') }}" alt="">
                    </i>
                    Booking Managment
                </a>
            </li>
            <li>
                <a href="{{ route('dashboard.change-password') }}"
                    class="@if (str_contains(url()->current(), 'change-password')) active @endif">
                    <i>
                        <img src="{{ asset('img/icons/dashbord/manage.png') }}" alt="">
                    </i>
                    Update Password
                </a>
            </li>
        </ul>
    </nav>
</aside>
