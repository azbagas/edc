@extends('layouts.app')

@section('title', 'Buat Pasien baru')

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Buat Pasien Baru</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ session('patients_url') ?? '/patients'  }}">Data Pasien</a></li>
                        <li class="breadcrumb-item active">Buat Pasien baru</li>
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
                <div class="col-md-10 col-lg-8 col-xl-6">

                    <div class="card">
                        {{-- <div class="card-header">
                            <h3 class="card-title">Buat Pasien Baru</h3>
                        </div> --}}
                        <form action="/patients" method="POST" autocomplete="off" spellcheck="false">
                            @csrf
                            <div class="card-body">

                                <div class="form-group">
                                    <label for="id">No Pasien<span class="text-danger">*</span></label>
                                    {{-- Cuma tampilan aja, gak dipassing --}}
                                    <input type="text" class="form-control" id="id" name="id"
                                        value="{{ $newPatientId }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="name">Nama Pasien<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" required autofocus
                                        id="name" name="name" placeholder="Masukkan nama pasien..." value="{{ old('name') }}">
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_of_birth">Tanggal Lahir<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="text" id="date_of_birth" name="date_of_birth" required
                                                    class="form-control @error('date_of_birth') is-invalid @enderror"
                                                    placeholder="dd-mm-yyyy" value="{{ old('date_of_birth') }}">
                                                @error('date_of_birth')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jenis Kelamin<span class="text-danger">*</span></label>

                                            <div class="row">
                                                <div class="col-sm-4 col-md-5">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="laki-laki"
                                                            name="gender" value="Laki-laki" required {{ old('gender') == 'Laki-laki' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="laki-laki">Laki-laki</label>
                                                    </div>

                                                </div>
                                                <div class="col-sm-8 col-md-7">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="perempuan"
                                                            name="gender" value="Perempuan" required {{ old('gender') == 'Perempuan' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="perempuan">Perempuan</label>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('gender')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phone">No Telepon</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="text" id="phone" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            placeholder="Masukkan nomor telepon..." value="{{ old('phone') }}">
                                        @error('phone')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address">Alamat<span class="text-danger">*</span></label>
                                    <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="3" required
                                        placeholder="Masukkan alamat...">{{ old('address') }}</textarea>
                                    @error('address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                            <div class="card-footer d-flex">
                                <div class="mr-auto">
                                    <button type="reset" class="btn btn-default">Reset</button>
                                </div>
                                <div>
                                    
                                    <button type="submit" class="btn btn-primary">Buat pasien</button>

                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('input[name="date_of_birth"]').inputmask('datetime', {
                inputFormat: 'dd-mm-yyyy',
                prefillYear: false
            });
        });
    </script>
@endpush
