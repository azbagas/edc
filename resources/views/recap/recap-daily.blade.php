@extends('layouts.app')

@section('title', 'Rekap')

@section('styles')
    <style>
        #date:hover {
            cursor: pointer;
        }

        #date {
            caret-color: transparent;
        }
    </style>
@endsection

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Rekap (Harian)</h1>
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
                        <form action="{{ route('recap-daily') }}" method="GET" id="filter-form" spellcheck="false" autocomplete="off">
                            <div class="card-header">
                                <h3 class="card-title">Filter</h3>
                            </div>
                            <div class="card-body">
                                
                                <div class="row">
                                    <div class="col-md-7 col-xl-5">
                                        <div class="form-group">
                                            <label for="date">Tanggal<span class="text-danger">*</span></label>
                            
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <input type="hidden" name="date">
                                                <input type="text" class="form-control float-right" id="date">
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
                                            REKAPITULASI PEMASUKAN HARIAN KLINIK <br>
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
                                            <th rowspan="2" style="vertical-align: middle;">Dokter</th>
                                            <th rowspan="2" style="vertical-align: middle;">Biaya Pasien</th>
                                            <th rowspan="2" style="vertical-align: middle;">Biaya Operasional</th>
                                            <th rowspan="2" style="vertical-align: middle;">Biaya Lab</th>
                                            <th rowspan="2" style="vertical-align: middle;">Jasa Dokter</th>
                                            <th rowspan="2" style="vertical-align: middle;">Grand Total Klinik</th>
                                            <th rowspan="2" style="vertical-align: middle;">Zakat 2,5%</th>
                                            <th colspan="{{ $doctorCosts->first()['payment_types']->count() }}">Total</th>
                                        </tr>
                                        <tr>
                                            @foreach ($doctorCosts->first()['payment_types'] as $paymentTypeName => $paymentTypeAmount)
                                                <th>{{ $paymentTypeName }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($doctorCosts as $doctorCost)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $doctorCost['doctor_name'] }}</td>
                                                <td class="text-right">{{ change_decimal_format_to_currency($doctorCost['sum_total_amount']) }}</td>
                                                <td class="text-right">{{ change_decimal_format_to_currency($doctorCost['sum_total_operational_cost']) }}</td>
                                                <td class="text-right">{{ change_decimal_format_to_currency($doctorCost['sum_total_lab_cost']) }}</td>
                                                <td class="text-right">{{ change_decimal_format_to_currency($doctorCost['sum_total_doctor_cost']) }}</td>
                                                <td class="text-right">{{ change_decimal_format_to_currency($doctorCost['sum_total_clinic_total']) }}</td>
                                                <td class="text-right">{{ change_decimal_format_to_currency($doctorCost['sum_total_clinic_total'] * 0.025) }}</td>
                                                @foreach ($doctorCost['payment_types'] as $paymentTypeName => $paymentTypeAmount)
                                                    <td class="text-right">{{ change_decimal_format_to_currency($paymentTypeAmount) }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        <tr class="font-weight-bold text-right">
                                            <td colspan="2" class="text-right">Total</td>
                                            <td>{{ change_decimal_format_to_currency($doctorCosts->sum('sum_total_amount')) }}</td>
                                            <td>{{ change_decimal_format_to_currency($doctorCosts->sum('sum_total_operational_cost')) }}</td>
                                            <td>{{ change_decimal_format_to_currency($doctorCosts->sum('sum_total_lab_cost')) }}</td>
                                            <td>{{ change_decimal_format_to_currency($doctorCosts->sum('sum_total_doctor_cost')) }}</td>
                                            <td>{{ change_decimal_format_to_currency($doctorCosts->sum('sum_total_clinic_total')) }}</td>
                                            <td>{{ change_decimal_format_to_currency($doctorCosts->sum(function ($doctorCost) {
                                                                                                            return $doctorCost['sum_total_clinic_total'] * 0.025;
                                                                                                        })) 
                                                }}
                                            </td>
                                            @foreach ($doctorCosts->first()['payment_types'] as $paymentTypeName => $paymentTypeAmount)
                                                <td class="text-right">{{ change_decimal_format_to_currency($doctorCosts->sum(function ($doctorCost) use ($paymentTypeName) {
                                                    return $doctorCost['payment_types'][$paymentTypeName];
                                                })) }}</td>
                                            @endforeach
                                        </tr>
                                        
                                    </tbody>
                                </table>
        
                            </div>
                        </div>
                        <!-- /.card-body -->
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

        // Initial date
        let start = moment();
        @if(request('date'))
            let startStr = "{{ request('date') }}";
            start = moment(startStr, "DD-MM-YYYY");
        @endif
        $('input[name="date"]').val(start.format('DD-MM-YYYY'));
        $('#date_header').text(start.format('D MMMM YYYY'));

        $('#date').daterangepicker({
            singleDatePicker: true,
            autoApply: true,
            showDropdowns: true,
            startDate: start,
            locale: {
                format: 'D MMMM YYYY'
            },
            minDate: moment("01-01-2020", "DD-MM-YYYY"),
            maxDate: moment().add(5, 'years').endOf('year')
        }, function(start, end, label) {
            $('input[name="date"]').val(start.format('DD-MM-YYYY'));
            $('#date_header').text(start.format('D MMMM YYYY'));
            filterForm.submit();
        });

    });
</script>
@endpush