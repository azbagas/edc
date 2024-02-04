@extends('layouts.app')

@section('title', 'Buat Pertemuan Baru')

@section('styles')
    <style>
        #date_time:hover {
            cursor: pointer;
        }

        #date_time {
            caret-color: transparent;
        }
    </style>
@endsection

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

                    <div class="card">
                        <form action="/appointments" method="POST" autocomplete="off" spellcheck="false">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-8">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="date_time">Tanggal<span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="far fa-calendar-alt"></i>
                                                            </span>
                                                        </div>
                                                        <input type="hidden" name="date_time">
                                                        <input type="text" class="form-control float-right" id="date_time">
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-xl-3">
                                                <div class="form-group">
                                                    <label for="patient_id">No Pasien<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id" value="{{ $patient->id }}" readonly>
                                                    @error('patient_id')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-xl-9">
                                                <div class="form-group">
                                                    <label for="patient_name">Nama Pasien<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control"
                                                        id="patient_name" name="patient_name" value="{{ $patient->name }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="doctor_id">Dokter<span class="text-danger">*</span></label>
                                                    <select class="form-control @error('doctor_id') is-invalid @enderror" name="doctor_id" id="doctor_id" required>
                                                        <option value="">-- Pilih dokter --</option>
                                                        @foreach ($doctors as $doctor)
                                                            <option value="{{ $doctor->id }}" @selected($doctor->id == session('todayDoctor'))>
                                                                {{ $doctor->user->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('doctor_id')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-xl-3">
                                                <div class="form-group">
                                                    <label for="assistant_id">Asisten<span class="text-danger">*</span></label>
                                                    <select class="form-control @error('assistant_id') is-invalid @enderror" name="assistant_id" id="assistant_id" required>
                                                        <option value="">-- Pilih asisten --</option>
                                                        @foreach ($assistants as $assistant)
                                                            <option value="{{ $assistant->id }}" @selected($assistant->id == session('todayAssistant'))>
                                                                {{ $assistant->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('assistant_id')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-xl-3">
                                                <div class="form-group">
                                                    <label for="admin">Admin<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control"
                                                        id="admin" name="admin" value="{{ Auth::user()->name }}" readonly>
                                                </div>
                                            </div>
        
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="complaint">Keluhan<span class="text-danger">*</span></label>
                                                    <textarea id="complaint" name="complaint" class="form-control @error('complaint') is-invalid @enderror" rows="3"  required
                                                        placeholder="Masukkan keluhan pasien...">{{ old('complaint') }}</textarea>
                                                    @error('complaint')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col ml-xl-5">
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>Kondisi Pasien</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="is_pregnant" name="is_pregnant" value="1" @checked(old('is_pregnant') == 1)>
                                                        <label class="form-check-label" for="is_pregnant">Sedang hamil</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                    

                            </div>
                            <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary">Buat pertemuan</button>
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
        // Initial date_time
        let start = moment();
        @if(old('date_time'))
            startStr = "{{ old('date_time') }}";
            start = moment(startStr, "Y-MM-DD HH:mm");
        @endif
        $('input[name="date_time"]').val(start.format('Y-MM-DD HH:mm'));

        $('#date_time').daterangepicker({
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: true,
            // timePickerIncrement: 5,
            showDropdowns: true,
            startDate: start,
            locale: {
                format: 'D MMMM YYYY, HH:mm'
            },
            minDate: moment("01-01-2020", "DD-MM-YYYY"),
            maxDate: moment().add(5, 'years').endOf('year')
        }, function(start, end, label) {
            $('input[name="date_time"]').val(start.format('Y-MM-DD HH:mm'));
        });
    });
</script>
@endpush