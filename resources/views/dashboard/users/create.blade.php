@extends('layouts.app')

@section('meta_title', "Add new user - Verma Courier's Admin")

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
                                        Add User
                                    </li>
                                </ol>
                            </nav>
                            <div class="back-btn">
                                <a href="#" class="btn-link-orange"><span>&#10140;</span> Back</a>
                            </div>
                        </div>
                        <form action="{{ route('dashboard.users.store') }}" method="POST" autocomplete="off">
                            @csrf
                            <section>
                                <div class="card border-btm">
                                    <div class="card-header">
                                        <div class="title-icon">
                                            <span><img src="{{ asset('img/icons/icon-01.png') }}" alt="add new user"></span>
                                            Add User
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-sm-6 col-md-4 col-12">
                                                    <div class="custom-field @error('fullname') has-validation @enderror">
                                                        <input type="text"
                                                            class="form-control @error('fullname') is-invalid @enderror"
                                                            id="fullname" name="fullname" placeholder="Enter Full Name"
                                                            required value="{{ old('fullname') }}">
                                                        <label for="fullname" class="form-label">Full Name</label>
                                                        @error('fullname')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-4 col-12">
                                                    <div class="custom-field @error('email') has-validation @enderror">
                                                        <input type="email"
                                                            class="form-control @error('email') is-invalid @enderror"
                                                            id="email" name="email" placeholder="Enter email" required
                                                            value="{{ old('email') }}">
                                                        <label for="email" class="form-label">Email</label>
                                                        @error('email')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-4 col-12">
                                                    <div class="custom-field @error('mobile_no') has-validation @enderror">
                                                        <input type="text"
                                                            class="form-control @error('mobile_no') is-invalid @enderror"
                                                            id="mobile_no" name="mobile_no" placeholder="Enter mobile no"
                                                            required minlength="10" maxlength="10"
                                                            value="{{ old('mobile_no') }}">
                                                        <label for="mobile_no" class="form-label">Mobile No.</label>
                                                        @error('mobile_no')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-4 col-12">
                                                    <div
                                                        class="custom-field @error('employee_id') has-validation @enderror">
                                                        <input type="text"
                                                            class="form-control @error('employee_id') is-invalid @enderror"
                                                            id="employee_id" name="employee_id"
                                                            placeholder="Enter employee id" required
                                                            value="{{ old('employee_id') }}">
                                                        <label for="employee_id" class="form-label">Employee Id</label>
                                                        @error('employee_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-4 col-12">
                                                    <div
                                                        class="custom-field select @error('role') has-validation @enderror">
                                                        <select name="role" id="role"
                                                            class="form-control @error('role') is-invalid @enderror"
                                                            required>
                                                            <option value="">-- Select Role --</option>
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role->id }}"
                                                                    @if ($role->id === 3) selected @endif>
                                                                    {{ $role->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <label for="role" class="form-label">Role</label>
                                                        @error('role')
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
                                                                    @if ($id === 1) selected @endif>
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
                                        <a class="btn btn-lg btn-orange-outline" href="#">Back</a>
                                    </li>
                                    <li>
                                        <button class="btn btn-lg btn-orange-outline" type="submit">
                                            Add User
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
