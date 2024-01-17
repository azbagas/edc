@extends('layouts.app')

@section('title', 'Pendapatan')

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
                    <h1 class="m-0">Pendapatan</h1>
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
                        <form action="/income" method="GET" id="filter-form" spellcheck="false" autocomplete="off">
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

                                <input type="hidden" name="per_page" value="{{ request('per_page') }}">

                            </div>
                        </form>
                    </div>
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>No Pasien</th>
                                        <th>Nama Pasien</th>
                                        <th>Biaya Pasien (Rp)</th>
                                        <th>Biaya Operasional (Rp)</th>
                                        <th>Metode Pembayaran</th>
                                        <th class="text-nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($appointments as $appointment)
                                        <tr>
                                            <td>{{ $appointments->firstItem() + $loop->index }}</td>
                                            <td>{{ \Carbon\Carbon::parse($appointment->created_at)->translatedFormat('d M Y') }}</td>
                                            <td>{{ $appointment->patient->id }}</td>
                                            <td>{{ $appointment->patient->name }}</td>
                                            <td class="text-right">{{ change_decimal_format_to_currency($appointment->payment->amount) }}</td>
                                            <td class="text-right">{{ change_decimal_format_to_currency($appointment->payment->operational_cost) }}</td>
                                            <td>{{ $appointment->payment->payment_type->name }}</td>
                                            <td>
                                                <a href="/appointments/{{ $appointment->id }}" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                <span>Tidak ada pendapatan.</span>
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


        // per page
        $('#per_page').on('change', function() {
            $('input[name="per_page"]').val($(this).val());
            filterForm.submit();
        });


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