@extends('layouts.app')

@section('title', 'Buat Janji Baru')

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
                    <h1 class="m-0">Buat Janji Baru</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/patient-promises">Janji Pasien</a></li>
                        <li class="breadcrumb-item active">Buat Janji Baru</li>
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
                        <form action="/patient-promises" method="POST" autocomplete="off" spellcheck="false">
                            @csrf
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="date_time">Tanggal<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <input type="hidden" name="date_time">
                                                <input type="text" class="form-control float-right" id="date_time" required>
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 col-xl-2">
                                        <div class="form-group">
                                            <label for="patient_id">No Pasien<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id" value="{{ $patient->id }}" readonly>
                                            @error('patient_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-8 col-xl-6">
                                        <div class="form-group">
                                            <label for="patient_name">Nama Pasien<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control"
                                                id="patient_name" name="patient_name" value="{{ $patient->name }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-8">
                                        <div class="form-group">
                                            <label for="note">Catatan</label>
                                            <textarea id="note" name="note" class="form-control @error('note') is-invalid @enderror" rows="3"
                                                placeholder="Masukkan catatan...">{{ old('note') }}</textarea>
                                            @error('note')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Buat</button>
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
            $('input[name="date_time"]').val(start.format('Y-MM-DD HH:mm'));
            $('#date_time').val(start.format('D MMMM YYYY, HH:mm'));
        @endif

        $('#date_time').daterangepicker({
            autoUpdateInput: false,
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: true,
            showDropdowns: true,
            locale: {
                format: 'D MMMM YYYY, HH:mm',
                cancelLabel: 'Clear'
            },
            minDate: moment("01-01-2020", "DD-MM-YYYY"),
            maxDate: moment().add(5, 'years').endOf('year')
        });

        // Update input ketika daterangepicker diapply
        $('#date_time').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('D MMMM YYYY, HH:mm'));
            $('input[name="date_time"]').val(picker.startDate.format('Y-MM-DD HH:mm'));
        });

        // Hapus nilai input ketika daterangepicker dicancel
        $('#date_time').on('cancel.daterangepicker', function() {
            $(this).val('');
            $('input[name="date_time"]').val('');
        });
    });
</script>
@endpush