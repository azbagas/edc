@extends('layouts.app')

@section('title', 'Janji Pasien')

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
                    <h1 class="m-0">Janji Pasien</h1>
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
                        <form action="/patient-promises" method="GET" id="filter-form" spellcheck="false" autocomplete="off">
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
                                                <input type="hidden" name="start_date">
                                                <input type="hidden" name="end_date">
                                                <input type="text" class="form-control float-right" id="date_range">
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 col-xl-2">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" name="status" id="status">
                                                <option value="">Semua status</option>
                                                @foreach ($statuses as $status)
                                                    <option value="{{ $status }}" @selected(request('status') == $status)>{{ $status }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="per_page" value="{{ request('per_page') }}">

                                <div class="row">
                                    <div class="col-md-7 col-xl-5">
                                        <a href="/patient-promises" class="btn btn-default btn-sm">
                                            Reset Filter
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover table-bordered">
                                <thead class="text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>No Pasien</th>
                                        <th>Nama Pasien</th>
                                        <th>Catatan</th>
                                        <th>Status</th>
                                        <th class="text-nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($patientPromises as $patientPromise)
                                        <tr>
                                            <td>{{ $patientPromises->firstItem() + $loop->index }}</td>
                                            <td>{{ \Carbon\Carbon::parse($patientPromise->date_time)->translatedFormat('j M Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($patientPromise->date_time)->format('H:i') }}</td>
                                            <td>{{ $patientPromise->patient_id }}</td>
                                            <td>{{ $patientPromise->patient->name }}</td>
                                            <td>{{ $patientPromise->note }}</td>
                                            @php
                                                $statusBadge = '';
                                                if ($patientPromise->status == 'Pending') {
                                                    $statusBadge = 'info';
                                                } else if ($patientPromise->status == 'Selesai') {
                                                    $statusBadge = 'success';
                                                } else if ($patientPromise->status == 'Batal') {
                                                    $statusBadge = 'danger';
                                                }
                                            @endphp
                                            <td><span class="badge badge-{{ $statusBadge }}">{{ $patientPromise->status }}</span></td>
                                            <td class="text-nowrap col-1">
                                                @if ($patientPromise->status == 'Batal')
                                                    <form action="/patient-promises/{{ $patientPromise->id }}" method="POST" class="d-inline">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm delete-button">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="/patient-promises/{{ $patientPromise->id }}/edit" class="btn btn-warning btn-sm">
                                                    <i class="fa fa-pen"></i>
                                                </a>
                                                @if ($patientPromise->status == 'Pending')
                                                    <form action="/patient-promises/{{ $patientPromise->id }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('put')
                                                        <input type="hidden" name="batal" value="1">
                                                        <button type="submit" class="btn btn-danger btn-sm batal-button">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </form>
                                                    <form action="/patient-promises/{{ $patientPromise->id }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('put')
                                                        <input type="hidden" name="selesai" value="1">
                                                        <button type="submit" class="btn btn-success btn-sm selesai-button">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="16" class="text-center">
                                                <span>Tidak ada janji pasien.</span>
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
                    {{ $patientPromises->onEachSide(1)->links() }}
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

        $('#status').on('change', function() {
            filterForm.submit();
        });


        // per page
        $('#per_page').on('change', function() {
            $('input[name="per_page"]').val($(this).val());
            filterForm.submit();
        });

        // Sweet alert delete
        $('.delete-button').click(function(event) {
            let form = $(this).closest("form");
            event.preventDefault();
            Swal.fire({
                title: "Anda yakin ingin menghapus janji ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        })

        // Sweet alert delete
        $('.batal-button').click(function(event) {
            let form = $(this).closest("form");
            event.preventDefault();
            Swal.fire({
                title: "Anda yakin ingin membatalkan janji ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, batalkan!",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        })

        // Sweet alert delete
        $('.selesai-button').click(function(event) {
            let form = $(this).closest("form");
            event.preventDefault();
            Swal.fire({
                title: "Anda yakin ingin menyelesaikan janji ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya!",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        })


        // Daterange
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
                '7 hari kedepan': [moment(), moment().add(6, 'days')],
                'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
                'Bulan depan': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
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