@extends('layouts.guest')

@section('content')
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <h1>Ely Dental Clinic</h1>
            </div>
            <div class="card-body">
                <form action="/login" method="post">
                    @csrf

                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        <input type="text" name="username" id="username"
                            class="form-control @error('username') is-invalid @enderror" placeholder="Username">
                        @error('username')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <input type="password" name="password" id="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col">
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mb-1 mt-3">
                    <a href="#">Lupa password</a>
                </p>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
@endsection
