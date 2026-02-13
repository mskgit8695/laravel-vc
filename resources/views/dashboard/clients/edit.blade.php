@extends('layouts.app')

@section('meta_title', "Edit client - Verma Courier's Admin")

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
                                    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Client
                                            Managment</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Edit Client
                                    </li>
                                </ol>
                            </nav>
                            <div class="back-btn">
                                <a href="#" onclick="history.back()" class="btn-link-orange"><span>&#10140;</span>
                                    Back</a>
                            </div>
                        </div>
                        <form action="{{ route('clients.update', $client->id) }}" method="POST" autocomplete="off">
                            @csrf
                            @method('PATCH')
                            <section>
                                <div class="card border-btm">
                                    <div class="card-header">
                                        <div class="title-icon">
                                            <span><img src="{{ asset('img/icons/icon-01.png') }}"
                                                    alt="add new client"></span>
                                            Edit client
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-sm-6 col-md-4 col-12">
                                                    <div class="custom-field @error('name') has-validation @enderror">
                                                        <input type="text"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            id="name" name="name" placeholder="Enter name" required
                                                            value="{{ old('name', $client->name) }}">
                                                        <label for="name" class="form-label">Name</label>
                                                        @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-4 col-12">
                                                    <div class="custom-field @error('c_address') has-validation @enderror">
                                                        <input type="text"
                                                            class="form-control @error('c_address') is-invalid @enderror"
                                                            id="c_address" name="c_address" placeholder="Enter address"
                                                            required value="{{ old('c_address', $client->c_address) }}">
                                                        <label for="c_address" class="form-label">Address</label>
                                                        @error('c_address')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-4 col-12">
                                                    <div class="custom-field @error('c_contact') has-validation @enderror">
                                                        <input type="text"
                                                            class="form-control @error('c_contact') is-invalid @enderror"
                                                            id="c_contact" name="c_contact" placeholder="Enter contact no"
                                                            required minlength="10" maxlength="10"
                                                            value="{{ old('c_contact', $client->c_contact) }}">
                                                        <label for="c_contact" class="form-label">Contact No.</label>
                                                        @error('c_contact')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-4 col-12">
                                                    <div
                                                        class="custom-field select @error('status') has-validation @enderror">
                                                        <select name="status" id="status"
                                                            class="form-control @error('status') is-invalid @enderror"
                                                            required>
                                                            <option value="">-- Select Status --</option>
                                                            @foreach ($status_list as $id => $value)
                                                                <option value="{{ $id }}"
                                                                    @if ($id == $client->status) selected @endif>
                                                                    {{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <label for="status" class="form-label">Status</label>
                                                        @error('status')
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
                                            Update Client
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
