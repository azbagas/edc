@extends('layouts.app')

@section('title', 'Pertemuan')

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
                        <form action="/appointments" method="GET" id="filter-form">
                            <div class="card-header">
                                <h3 class="card-title">Filter</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-7 col-xl-5">
                                        <div class="form-group">
                                            <label for="date">Waktu</label>
                                            <select class="form-control" name="date" id="date">
                                                <option value="">Hari ini</option>
                                                <option value="yesterday" {{ request('date') == 'yesterday' ? 'selected' : '' }}>Kemarin</option>
                                                <option value="sevenDaysBefore" {{ request('date') == 'sevenDaysBefore' ? 'selected' : '' }}>7 hari terakhir</option>
                                                <option value="thisMonth" {{ request('date') == 'thisMonth' ? 'selected' : '' }}>Bulan ini</option>
                                                <option value="allTime" {{ request('date') == 'allTime' ? 'selected' : '' }}>Semua waktu</option>
                                            </select>
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
                                        <th>Keluhan</th>
                                        <th>Status</th>
                                        <th class="text-nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($appointments as $appointment)
                                        <tr>
                                            <td>{{ ($appointments->total()-$loop->index)-(($appointments->currentpage()-1) * $appointments->perpage() ) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($appointment->created_at)->format('d-m-Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($appointment->created_at)->format('H:i') }}</td>
                                            <td>{{ $appointment->patient_id }}</td>
                                            <td>{{ $appointment->patient->name }}</td>
                                            <td>{{ $appointment->doctor->user->name }}</td>
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
                                            <td colspan="9" class="text-center">
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

        </div><!-- /.container-fluid -->


    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {

        $('#doctor').on('change', function() {
            let filterForm = $('#filter-form');
            filterForm.submit();
        });

        $('#status').on('change', function() {
            let filterForm = $('#filter-form');
            filterForm.submit();
        });

        $('#date').on('change', function() {
            let filterForm = $('#filter-form');
            filterForm.submit();
        });

    });
</script>
@endpush