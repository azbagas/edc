@extends('layouts.app')

@section('title', 'Edit Asisten')

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Asisten</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/assistants">Asisten</a></li>
                        <li class="breadcrumb-item active">Edit Asisten</li>
                    </ol>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')
    <div class="content">
        <form action="/assistants/{{ $assistant->id }}" method="POST" autocomplete="off" spellcheck="false">
            @csrf
            @method('put')
            <div class="container-fluid">

                <div class="row">
                    <div class="col">

                        <div class="card">

                            <div class="card-header">
                                <h3 class="card-title">Data Diri Asisten</h3>
                            </div>

                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="name">Nama Asisten<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                required id="name" name="name" placeholder="Masukkan nama asisten..."
                                                value="{{ old('name', $assistant->name) }}">
                                            @error('name')
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
                                                    placeholder="Masukkan nomor telepon..." value="{{ old('phone', $assistant->phone) }}"
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
                                                required placeholder="Masukkan alamat...">{{ old('address', $assistant->address) }}</textarea>
                                            @error('address')
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
                                                <option value="1" @selected(old('is_active', $assistant->is_active) == 1)>Aktif</option>
                                                <option value="0" @selected(old('is_active', $assistant->is_active) == 0)>Nonaktif</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>

                        </div>

                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </form>
    </div>
@endsection