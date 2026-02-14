@extends('layouts.app')

@section('meta_title', "Add new role - Verma Courier's Admin")

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
                                    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Role Managment</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Add Role
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <form action="{{ route('roles.store') }}" method="POST" autocomplete="off">
                            @csrf
                            <section>
                                <div class="card border-btm">
                                    <div class="card-header">
                                        <div class="title-icon">
                                            <span><img src="{{ asset('img/icons/icon-01.png') }}" alt="add new role"></span>
                                            Add Role
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
                                                            value="{{ old('name') }}">
                                                        <label for="name" class="form-label">Name</label>
                                                        @error('name')
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
                                        <button class="btn btn-lg btn-orange-outline" type="submit">
                                            Add Role
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
