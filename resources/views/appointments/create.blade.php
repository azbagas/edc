@extends('layouts.app')

@section('title', 'Buat Pertemuan Baru')

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Buat Pertemuan Baru</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/appointments">Pertemuan</a></li>
                        <li class="breadcrumb-item active">Buat pertemuan baru</li>
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

                    <a href="{{ url()->previous() }}" class="btn btn-primary btn-sm mb-3">
                        <i class="fa fa-arrow-left mr-2"></i>Kembali
                    </a>

                    <div class="card">
                        <form action="/appointments" method="POST" autocomplete="off" spellcheck="false">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-xl-2">
                                        <div class="form-group">
                                            <label for="patient_id">No Pasien</label>
                                            <input type="text" class="form-control @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id" value="{{ $patient->id }}" readonly>
                                            @error('patient_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-xl-6">
                                        <div class="form-group">
                                            <label for="patient_name">Nama Pasien</label>
                                            <input type="text" class="form-control"
                                                id="patient_name" name="patient_name" value="{{ $patient->name }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <label for="doctor_id">Dokter</label>
                                            <select class="form-control @error('doctor_id') is-invalid @enderror" name="doctor_id" id="doctor_id" required>
                                                <option value="">-- Pilih dokter --</option>
                                                @foreach ($doctors as $doctor)
                                                    <option value="{{ $doctor->id }}">
                                                        {{ $doctor->user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('doctor_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <label for="assistant_id">Asisten</label>
                                            <select class="form-control @error('assistant_id') is-invalid @enderror" name="assistant_id" id="assistant_id" required>
                                                <option value="">-- Pilih asisten --</option>
                                                @foreach ($assistants as $assistant)
                                                    <option value="{{ $assistant->id }}">
                                                        {{ $assistant->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('assistant_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-xl-8">
                                        <div class="form-group">
                                            <label for="complaint">Keluhan</label>
                                            <textarea id="complaint" name="complaint" class="form-control @error('complaint') is-invalid @enderror" rows="3" required
                                                placeholder="Masukkan keluhan pasien...">{{ old('complaint') }}</textarea>
                                            @error('complaint')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                    

                            </div>
                            <div class="card-footer d-flex">
                                <div class="mr-auto">
                                    <button type="reset" class="btn btn-default">Reset</button>
                                </div>
                                <div>
                                    
                                    <button type="submit" class="btn btn-primary">Buat pertemuan</button>

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

{{-- @push('scripts')
    <script>
        $(document).ready(function() {
            $('input[name="date_of_birth"]').inputmask('datetime', {
                inputFormat: 'dd-mm-yyyy',
                prefillYear: false
            });
        });
    </script>
@endpush --}}
