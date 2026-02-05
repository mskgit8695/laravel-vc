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
                        <img src="{{ asset('img/icons/dashbord/manage.png') }}" alt="">
                    </i>
                    User Managment
                </a>
            </li>
            <li>
                <a href="add-new-section.html">
                    <i>
                        <img src="{{ asset('img/icons/dashbord/add.png') }}" alt="">
                    </i>
                    Sections / Add New
                </a>
            </li>
        </ul>
    </nav>
</aside>
