@extends('layouts.app')

@section('title', 'Tambah Admin')

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Admin</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admins">Admin</a></li>
                        <li class="breadcrumb-item active">Tambah Admin</li>
                    </ol>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')
    <div class="content">
        <form action="/admins" method="POST" autocomplete="off" spellcheck="false">
            @csrf
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Akun</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="username">Username<span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('username') is-invalid @enderror" required autofocus
                                                id="username" name="username" placeholder="Masukkan username..."
                                                value="{{ old('username') }}">
                                            @error('username')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="password">Password<span class="text-danger">*</span></label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror" required
                                                id="password" name="password" placeholder="Masukkan password...">
                                            @error('password')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="password_confirmation">Konfirmasi Password<span
                                                    class="text-danger">*</span></label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                required id="password_confirmation" name="password_confirmation"
                                                placeholder="Masukkan ulang password...">
                                            @error('password')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">

                        <div class="card">


                            <div class="card-header">
                                <h3 class="card-title">Data Diri Admin</h3>
                            </div>

                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="name">Nama Admin<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                required id="name" name="name" placeholder="Masukkan nama admin..."
                                                value="{{ old('name') }}">
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                id="email" name="email" placeholder="Masukkan email..."
                                                value="{{ old('email') }}">
                                            @error('email')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="phone">No Telepon<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                </div>
                                                <input type="text" id="phone" name="phone"
                                                    class="form-control @error('phone') is-invalid @enderror"
                                                    placeholder="Masukkan nomor telepon..." value="{{ old('phone') }}"
                                                    required>
                                                @error('phone')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="address">Alamat<span class="text-danger">*</span></label>
                                            <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="3"
                                                required placeholder="Masukkan alamat...">{{ old('address') }}</textarea>
                                            @error('address')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col">
                        <div class="text-right mb-3">
                            <button type="submit" class="btn btn-primary">Tambah</button>

                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </form>
    </div>
@endsection