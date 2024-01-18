@extends('layouts.app')

@section('title', 'Edit Penyakit')

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Penyakit</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/diseases">Penyakit</a></li>
                        <li class="breadcrumb-item active">Edit Penyakit</li>
                    </ol>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">

                    <div class="card">
                        <form action="/diseases/{{ $disease->id }}" method="POST" autocomplete="off" spellcheck="false">
                            @csrf
                            @method('put')
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="disease_code">Kode Penyakit<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('disease_code') is-invalid @enderror" required
                                                id="disease_code" name="disease_code" placeholder="Masukkan kode penyakit..." value="{{ old('disease_code', $disease->disease_code) }}">
                                            @error('disease_code')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="name">Penyakit<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" required
                                                id="name" name="name" placeholder="Masukkan Penyakit..." value="{{ old('name', $disease->name) }}">
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>


                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Edit</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection