@extends('layouts.app')

@section('meta_title', "Client Management - Verma Courier's Admin")

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
                                        {{ $title ?? 'Client Management' }}
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
                                    <div class="add-btn">
                                        <a href="{{ route('clients.create') }}" class="btn btn-orange">
                                            <i><img src="{{ asset('img/plus-white.png') }}" alt="add client"></i>
                                            Add client
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered custom-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Address</th>
                                                    <th>Contact No.</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($clients as $index => $client)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $client->name }}</td>
                                                        <td>{{ $client->c_address }}</td>
                                                        <td>{{ $client->c_contact }}</td>
                                                        <td title="@if ($client->status !== 0) active @endif">
                                                            @if ($client->status === 0)
                                                                Inactive
                                                            @else
                                                                Active
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('clients.edit', $client->id) }}">Edit</a>
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
