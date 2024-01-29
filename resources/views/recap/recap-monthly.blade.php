@extends('layouts.app')

@section('title', 'Rekap')

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
                    <h1 class="m-0">Rekap (Bulanan)</h1>
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
                        <form action="{{ route('recap-monthly') }}" method="GET" id="filter-form" spellcheck="false" autocomplete="off">
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
                                            <a href="{{ route('recap-monthly') }}" class="btn btn-default btn-sm">
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
                                            REKAPITULASI PENDAPATAN BULANAN<br>
                                            {{ strtoupper($months[request('month', now()->month)]) }} {{ request('year', now()->year) }}
                                        </h5>

                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive" style="height: 600px">
                                <table class="table table-head-fixed table-sm table-hover table-bordered">
                                    <thead class="text-center">
                                        <tr>
                                            <th style="vertical-align: middle;">No</th>
                                            <th style="vertical-align: middle;">Tanggal</th>
                                            <th style="vertical-align: middle;">Biaya Pasien</th>
                                            <th style="vertical-align: middle;">Biaya Operasional</th>
                                            <th style="vertical-align: middle;">Biaya Lab</th>
                                            @foreach ($finalResults->first()['doctors'] as $doctorName => $doctorCost)
                                                <th style="vertical-align: middle;">{{ implode(array_slice(explode(" ", $doctorName), 0, 2)) }}</th>
                                            @endforeach
                                            <th style="vertical-align: middle;">Total untuk klinik</th>
                                            <th style="vertical-align: middle;">Zakat 2,5%</th>
                                            <th style="vertical-align: middle;">Pengeluaran</th>
                                            <th style="vertical-align: middle;">Netto</th>
                                            <th colspan="{{ $finalResults->first()['payment_types']->count() }}" style="vertical-align: middle;">
                                                <div>
                                                    <div class="row">
                                                        <div class="col">
                                                            Total
                                                        </div>
                                                    </div>
                                                    <hr class="my-2">
                                                    <div class="row">
                                                        @foreach ($finalResults->first()['payment_types'] as $paymentTypeName => $paymentTypeAmount)
                                                            <div class="col-{{ 12/$finalResults->first()['payment_types']->count() }}">
                                                                {{ $paymentTypeName }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($finalResults as $finalResult)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="text-nowrap">{{ \Carbon\Carbon::parse($finalResult['date'])->translatedFormat('j M Y') }}</td>
                                                <td class="text-right">{{ change_decimal_format_to_currency($finalResult['sum_total_amount']) }}</td>
                                                <td class="text-right">{{ change_decimal_format_to_currency($finalResult['sum_total_operational_cost']) }}</td>
                                                <td class="text-right">{{ change_decimal_format_to_currency($finalResult['sum_total_lab_cost']) }}</td>
                                                @foreach ($finalResult['doctors'] as $doctorName => $doctorCost)
                                                    <td class="text-right">{{ change_decimal_format_to_currency($doctorCost) }}</td>
                                                @endforeach
                                                <td class="text-right">{{ change_decimal_format_to_currency($finalResult['sum_total_clinic_total']) }}</td>
                                                <td class="text-right">{{ change_decimal_format_to_currency($finalResult['zakat']) }}</td>
                                                <td class="text-right">{{ change_decimal_format_to_currency($finalResult['expenses']) }}</td>
                                                <td class="text-right">{{ change_decimal_format_to_currency($finalResult['netto']) }}</td>
                                                @foreach ($finalResult['payment_types'] as $paymentTypeName => $paymentTypeAmount)
                                                    <td class="text-right">{{ change_decimal_format_to_currency($paymentTypeAmount) }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        <tr class="font-weight-bold text-right">
                                            <td colspan="2">Total</td>
                                            <td>{{ change_decimal_format_to_currency($finalResults->sum('sum_total_amount')) }}</td>
                                            <td>{{ change_decimal_format_to_currency($finalResults->sum('sum_total_operational_cost')) }}</td>
                                            <td>{{ change_decimal_format_to_currency($finalResults->sum('sum_total_lab_cost')) }}</td>
                                            @foreach ($finalResults->first()['doctors'] as $doctorName => $doctorAmount)
                                                <td class="text-right">{{ change_decimal_format_to_currency($finalResults->sum(function ($finalResult) use($doctorName) {
                                                    return $finalResult['doctors'][$doctorName];
                                                })) }}</td>
                                            @endforeach
                                            <td>{{ change_decimal_format_to_currency($finalResults->sum('sum_total_clinic_total')) }}</td>
                                            <td>{{ change_decimal_format_to_currency($finalResults->sum('zakat')) }}</td>
                                            <td>{{ change_decimal_format_to_currency($finalResults->sum('expenses')) }}</td>
                                            <td>{{ change_decimal_format_to_currency($finalResults->sum('netto')) }}</td>
                                            @foreach ($finalResults->first()['payment_types'] as $paymentTypeName => $paymentTypeAmount)
                                                <td class="text-right">{{ change_decimal_format_to_currency($finalResults->sum(function ($finalResult) use($paymentTypeName) {
                                                    return $finalResult['payment_types'][$paymentTypeName];
                                                })) }}</td>
                                            @endforeach
                                        </tr>


                                        
                                    </tbody>
                                </table>
        
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <a href="{{ route('recap-monthly', ['month' => request('month'), 'year' => request('year'), 'download' => 'pdf']) }}" target="_blank" class="btn btn-default">
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