@extends('layouts.app')

@section('title', 'Laporan untuk Puskesmas')

@section('styles')
    <style>
        #date_range:hover {
            cursor: pointer;
        }

        #date_range {
            caret-color: transparent;
        }

        #date_header {
            text-transform: uppercase
        }
    </style>
@endsection

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan untuk Puskesmas (Harian)</h1>
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
                        <form action="{{ route('community-health-center-daily') }}" method="GET" id="filter-form" spellcheck="false" autocomplete="off">
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
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="text-center mb-4">
                                        <h5>
                                            LAPORAN HARIAN ELY DENTAL CLINIC<br>
                                            KECAMATAN BALARAJA KABUPATEN TANGERANG<br>
                                            <span id="date_header"></span>
                                        </h5>

                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-bordered">
                                    <thead class="text-center">
                                        <tr>
                                            <th rowspan="2" style="vertical-align: middle;">No</th>
                                            <th colspan="2" rowspan="2" style="vertical-align: middle;">Kegiatan / Keadaan Kesehatan Gigi</th>
                                            <th colspan="2">Pasien Baru</th>
                                            <th colspan="2">Pasien Lama</th>
                                            <th colspan="2">Total</th>
                                        </tr>
                                        <tr>
                                            {{-- Baru --}}
                                            <th style="width: 4rem">L</th>
                                            <th style="width: 4rem">P</th>
                                            {{-- Lama --}}
                                            <th style="width: 4rem">L</th>
                                            <th style="width: 4rem">P</th>
                                            {{-- Total --}}
                                            <th style="width: 4rem">L</th>
                                            <th style="width: 4rem">P</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="font-weight-bold">
                                            <td>I</td>
                                            <td colspan="8">Kunjungan Pasien</td>
                                        </tr>
                                        @foreach ($patientTypesCount as $item)
                                            <tr>
                                                <td></td>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item['patient_type'] }}</td>
                                                <td class="text-center">{{ $item['countMaleNew'] }}</td>
                                                <td class="text-center">{{ $item['countFemaleNew'] }}</td>
                                                <td class="text-center">{{ $item['countMaleOld'] }}</td>
                                                <td class="text-center">{{ $item['countFemaleOld'] }}</td>
                                                <td class="text-center">{{ $item['countMaleNew'] + $item['countMaleOld'] }}</td>
                                                <td class="text-center">{{ $item['countFemaleNew'] + $item['countFemaleOld'] }}</td>
                                            </tr>
                                        @endforeach

                                        <tr class="font-weight-bold">
                                            <td>II</td>
                                            <td colspan="8">Pelayanan Medik Dasar Gigi</td>
                                        </tr>
                                        @foreach ($treatmentTypesCount as $item)
                                            <tr>
                                                <td></td>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item['treatment_type']->name }}</td>
                                                <td class="text-center">{{ $item['countMaleNew'] }}</td>
                                                <td class="text-center">{{ $item['countFemaleNew'] }}</td>
                                                <td class="text-center">{{ $item['countMaleOld'] }}</td>
                                                <td class="text-center">{{ $item['countFemaleOld'] }}</td>
                                                <td class="text-center">{{ $item['countMaleNew'] + $item['countMaleOld'] }}</td>
                                                <td class="text-center">{{ $item['countFemaleNew'] + $item['countFemaleOld'] }}</td>
                                            </tr>
                                        @endforeach

                                        <tr class="font-weight-bold">
                                            <td>III</td>
                                            <td colspan="8">Penyakit dan Kelainan Gigi / Mulut</td>
                                        </tr>
                                        @foreach ($diseasesCount as $item)
                                            <tr>
                                                <td></td>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item['disease']->name }} ({{ $item['disease']->disease_code }})</td>
                                                <td class="text-center">{{ $item['countMaleNew'] }}</td>
                                                <td class="text-center">{{ $item['countFemaleNew'] }}</td>
                                                <td class="text-center">{{ $item['countMaleOld'] }}</td>
                                                <td class="text-center">{{ $item['countFemaleOld'] }}</td>
                                                <td class="text-center">{{ $item['countMaleNew'] + $item['countMaleOld'] }}</td>
                                                <td class="text-center">{{ $item['countFemaleNew'] + $item['countFemaleOld'] }}</td>
                                            </tr>
                                        @endforeach


                                        
                                    </tbody>
                                </table>
        
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <a href="{{ route('community-health-center-daily', ['start_date' => request('start_date'), 'end_date' => request('end_date'), 'download' => 'pdf']) }}" target="_blank" class="btn btn-default">
                                <i class="fas fa-download"></i> Download PDF
                            </a>
                        </div>
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

        if (start.format('D MMMM YYYY') == end.format('D MMMM YYYY')) {
            $('#date_header').html(start.format('D MMMM YYYY'));
        } else {
            $('#date_header').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
        }

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
            // $('#date_header').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
            if (start.format('D MMMM YYYY') == end.format('D MMMM YYYY')) {
                $('#date_header').html(start.format('D MMMM YYYY'));
            } else {
                $('#date_header').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
            }
            filterForm.submit();
        });

    });
</script>
@endpush