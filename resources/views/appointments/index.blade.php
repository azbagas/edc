@extends('layouts.app')

@section('title', 'Pertemuan')

@section('styles')
    <style>
        #date_range:hover {
            cursor: pointer;
        }

        #date_range {
            caret-color: transparent;
        }
    </style>
@endsection

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pertemuan</h1>
                </div><!-- /.col -->

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
                        <form action="/appointments" method="GET" id="filter-form" spellcheck="false" autocomplete="off">
                            <div class="card-header">
                                <h3 class="card-title">Filter</h3>
                            </div>
                            <div class="card-body">
                                
                                <div class="row">
                                    <div class="col-md-7 col-xl-5">
                                        <div class="form-group">
                                            <label for="date_range">Waktu</label>
                            
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control float-right" id="date_range">
                                                <input type="hidden" name="start_date">
                                                <input type="hidden" name="end_date">
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-xl-3">
                                        <div class="form-group">
                                            <label for="doctor">Dokter</label>
                                            <select class="form-control" name="doctor" id="doctor">
                                                <option value="">Semua dokter</option>
                                                @foreach ($doctors as $doctor)
                                                    <option value="{{ $doctor->id }}" {{ request('doctor') == $doctor->id ? 'selected' : '' }}>
                                                        {{ $doctor->user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-xl-2">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" name="status" id="status">
                                                <option value="">Semua status</option>
                                                @foreach ($statuses as $status)
                                                    <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                                
                                <div class="row">
                                    <div class="col">
                                        <a href="/appointments" class="btn btn-default btn-sm">
                                            Reset Filter
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card">
                        {{-- <div class="card-header">
                            <h3 class="card-title">Daftar Pertemuan</h3>
                        </div> --}}
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>No Pasien</th>
                                        <th>Nama Pasien</th>
                                        <th>Dokter</th>
                                        <th>Asisten</th>
                                        <th>Admin</th>
                                        <th>Keluhan</th>
                                        <th>Status</th>
                                        <th class="text-nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($appointments as $appointment)
                                        <tr>
                                            <td>{{ ($appointments->total()-$loop->index)-(($appointments->currentpage()-1) * $appointments->perpage() ) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($appointment->date_time)->translatedFormat('j M Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($appointment->date_time)->format('H:i') }}</td>
                                            <td>{{ $appointment->patient_id }}</td>
                                            <td>{{ $appointment->patient->name }}</td>
                                            <td>{{ $appointment->doctor->user->name }}</td>
                                            <td>{{ $appointment->assistant->name }}</td>
                                            <td>{{ $appointment->admin->user->name }}</td>
                                            <td>{{ $appointment->complaint }}</td>
                                            <td><span class="badge badge-{{ $appointment->status->type }}">{{ $appointment->status->name }}</span></td>
                                            <td class="text-nowrap">
                                                <a href="/appointments/{{ $appointment->id }}/edit" class="btn btn-warning btn-sm">
                                                    <i class="fa fa-pen"></i>
                                                </a>
                                                @if ($appointment->status->name == 'Selesai')
                                                    <a href="/appointments/{{ $appointment->id }}" class="btn btn-primary btn-sm">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                @endif
                                                @if ($appointment->status->name != 'Selesai')
                                                    <a href="/appointments/{{ $appointment->id }}/examination" class="btn btn-success btn-sm">
                                                        <i class="fa fa-search"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="16" class="text-center">
                                                <span>Belum ada pertemuan.</span>
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col">
                    {{ $appointments->onEachSide(1)->links() }}
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="mb-3">
                        <span class="text-muted">
                            Tampilkan baris per halaman
                        </span>
                        
                        <select id="per_page" class="form-control-sm">
                            @foreach ($per_page_options as $per_page_option)
                                <option value="{{ $per_page_option }}" @selected(request('per_page') == $per_page_option )>{{ $per_page_option }}</option>
                            @endforeach
                        </select>
                        
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->


    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {

        let filterForm = $('#filter-form');

        $('#doctor').on('change', function() {
            filterForm.submit();
        });

        $('#status').on('change', function() {
            filterForm.submit();
        });

        $('#date').on('change', function() {
            filterForm.submit();
        });

        // per page
        $('#per_page').on('change', function() {
            $('input[name="per_page"]').val($(this).val());
            filterForm.submit();
        });

        //Date range picker
        let start = moment();
        @if(request('start_date'))
            startStr = "{{ request('start_date') }}";
            start = moment(startStr, "DD-MM-YYYY");
        @endif
        $('input[name="start_date"]').val(start.format('DD-MM-YYYY'));

        let end = moment();
        @if(request('end_date'))
            endStr = "{{ request('end_date') }}";
            end = moment(endStr, "DD-MM-YYYY");
        @endif
        $('input[name="end_date"]').val(end.format('DD-MM-YYYY'));

        $('#date_range').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Hari ini': [moment(), moment()],
                'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 hari terakhir': [moment().subtract(6, 'days'), moment()],
                'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
                'Bulan lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Semua waktu': [moment(0), moment().add(5, 'years').endOf('year')]
            },
            locale: {
                format: 'D MMMM YYYY'
            }
        }, function(start, end, label) {
            $('input[name="start_date"]').val(start.format('DD-MM-YYYY'));
            $('input[name="end_date"]').val(end.format('DD-MM-YYYY'));
            filterForm.submit();
        });

    });
</script>
@endpush