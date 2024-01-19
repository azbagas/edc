@extends('layouts.app')

@section('title', 'Edit Dokter')

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Dokter</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/doctors">Dokter</a></li>
                        <li class="breadcrumb-item active">Edit Dokter</li>
                    </ol>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')
    <div class="content">
        <form action="/doctors/{{ $doctor->id }}" method="POST" autocomplete="off" spellcheck="false">
            @csrf
            @method('put')
            <div class="container-fluid">
                <div class="row">
                    <div class="col">

                        <div class="card">


                            <div class="card-header">
                                <h3 class="card-title">Data Diri Dokter</h3>
                            </div>

                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="name">Nama Dokter<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                required id="name" name="name" placeholder="Masukkan nama dokter..."
                                                value="{{ old('name', $doctor->user->name) }}">
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="sip">SIP<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('sip') is-invalid @enderror"
                                                required id="sip" name="sip" placeholder="Masukkan SIP..."
                                                value="{{ old('sip', $doctor->sip) }}">
                                            @error('sip')
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
                                                value="{{ old('email', $doctor->user->email) }}">
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
                                                    placeholder="Masukkan nomor telepon..." value="{{ old('phone', $doctor->user->phone) }}"
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
                                                required placeholder="Masukkan alamat...">{{ old('address', $doctor->user->address) }}</textarea>
                                            @error('address')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="doctor_percentage">Persen Gaji<span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('doctor_percentage') is-invalid @enderror"
                                                required id="doctor_percentage" name="doctor_percentage"
                                                placeholder="Masukkan persen gaji..."
                                                value="{{ old('doctor_percentage', $doctor->doctor_percentage) }}">
                                            <small class="form-text text-muted">
                                                Contoh: 0.5, 0.3, dll.
                                            </small>
                                            @error('doctor_percentage')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="is_active">Status<span class="text-danger">*</span></label>
                                            <select class="form-control" name="is_active" id="is_active" required>
                                                <option value="1" @selected(old('is_active', $doctor->user->is_active) == 1)>Aktif</option>
                                                <option value="0" @selected(old('is_active', $doctor->user->is_active) == 0)>Nonaktif</option>
                                            </select>
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
                            <button type="submit" class="btn btn-primary">Edit</button>

                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </form>
    </div>
@endsection