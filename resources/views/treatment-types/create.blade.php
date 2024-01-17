@extends('layouts.app')

@section('title', 'Tambah Jenis Tindakan')

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Jenis Tindakan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/treatment-types">Jenis Tindakan</a></li>
                        <li class="breadcrumb-item active">Tambah Jenis Tindakan</li>
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
                        <form action="/treatment-types" method="POST" autocomplete="off" spellcheck="false">
                            @csrf
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="name">Nama Jenis Tindakan<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" required autofocus
                                                id="name" name="name" placeholder="Masukkan nama jenis tindakan..." value="{{ old('name') }}">
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>


                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Tambah</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection