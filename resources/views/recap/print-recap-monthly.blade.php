@extends('layouts.print')

@section('title', $title ?? 'Laporan untuk puskesmas')

@section('styles')
    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }

        table {
            font-size: x-small;
        }
        
        table.content {
            border-collapse: collapse;
        }

        table.content td, table.content th {
            border: 0.5px solid black;
            padding: 2px;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        hr {
            border: 0.5px solid black;
        }

        .gray {
            background-color: lightgray
        }
    </style>
@endsection

@section('content')

    <table width="100%">
        <tr>
            <td>
                <img src="{{ asset('storage/images/profile-pictures/default-profile-picture.jpg') }}"
                    alt="logo-klinik" width="80" />
            </td>
            <td align="left">
                <div style="font-size: 1.2rem">Ely Dental Clinic</div>
                <div>
                    Jl. Raya Serang Km 24, Talagasari, Kec. Balaraja, Kabupaten Tangerang, Banten 15610
                </div>
            </td>
        </tr>
    </table>

    <hr>

    <div class="text-center">
        <h5>
            REKAPITULASI PENDAPATAN BULANAN<br>
            {{ strtoupper($months[request('month', now()->month)]) }} {{ request('year', now()->year) }}
        </h5>
    </div>

    <table width="100%" class="content">
        <thead class="text-center">
            <tr>
                <th rowspan="2" style="vertical-align: middle;">No</th>
                <th rowspan="2" style="vertical-align: middle;">Tanggal</th>
                <th rowspan="2" style="vertical-align: middle;">Biaya Pasien</th>
                <th rowspan="2" style="vertical-align: middle;">Biaya Operasional</th>
                <th rowspan="2" style="vertical-align: middle;">Biaya Lab</th>
                @foreach ($finalResults->first()['doctors'] as $doctorName => $doctorCost)
                    <th rowspan="2" style="vertical-align: middle;">{{ implode(array_slice(explode(" ", $doctorName), 0, 2)) }}</th>
                @endforeach
                <th rowspan="2" style="vertical-align: middle;">Total untuk klinik</th>
                <th rowspan="2" style="vertical-align: middle;">Zakat 2,5%</th>
                <th rowspan="2" style="vertical-align: middle;">Pengeluaran</th>
                <th rowspan="2" style="vertical-align: middle;">Netto</th>
                <th colspan="{{ $finalResults->first()['payment_types']->count() }}" style="vertical-align: middle;">
                    Total
                </th>
            </tr>
            <tr>
                @foreach ($finalResults->first()['payment_types'] as $paymentTypeName => $paymentTypeAmount)
                    <th>{{ $paymentTypeName }}</th>
                @endforeach
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

@endsection
