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
    </style>
@endsection

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan untuk Puskesmas (Bulanan)</h1>
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
                        <form action="{{ route('community-health-center-monthly') }}" method="GET" id="filter-form" spellcheck="false" autocomplete="off">
                            <div class="card-header">
                                <h3 class="card-title">Filter</h3>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-5 col-xl-3">
                                        <div class="form-group">
                                            <label for="month">Pilih bulan</label>
                                            <select class="form-control" name="month" id="month">
                                                @foreach ($months as $monthNumber => $month)
                                                    <option value="{{ $monthNumber }}" {{ request('month', now()->month) == $monthNumber ? 'selected' : '' }}>{{ $month }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xl-2">
                                        <div class="form-group">
                                            <label for="year">Pilih tahun</label>
                                            <select class="form-control" name="year" id="year">
                                                @foreach ($years as $yearNumber => $year)
                                                    <option value="{{ $yearNumber }}" {{ request('year', now()->year) == $yearNumber ? 'selected' : '' }}>{{ $year }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-7 col-xl-5">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('community-health-center-monthly') }}" class="btn btn-default btn-sm">
                                                Reset filter
                                            </a>
                                            <button type="submit" class="btn btn-info">
                                                <i class="fa fa-search mr-2"></i>Filter
                                            </button>
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
                                            LAPORAN BULANAN ELY DENTAL CLINIC<br>
                                            KECAMATAN BALARAJA KABUPATEN TANGERANG<br>
                                            {{ strtoupper($months[request('month', now()->month)]) }} {{ request('year', now()->year) }}
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
                            <a href="{{ route('community-health-center-monthly', ['month' => request('month'), 'year' => request('year'), 'download' => 'pdf']) }}" target="_blank" class="btn btn-default">
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


    });
</script>
@endpush