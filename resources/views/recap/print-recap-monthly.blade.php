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

    @include('partials/letter-head')

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
                @foreach ($totalPerMonth->first()['doctor_cost'] as $doctorName => $doctorCost)
                    <th rowspan="2" style="vertical-align: middle;">{{ implode(array_slice(explode(" ", $doctorName), 0, 2)) }}</th>
                @endforeach
                <th rowspan="2" style="vertical-align: middle;">Total untuk klinik</th>
                <th rowspan="2" style="vertical-align: middle;">Zakat 2,5%</th>
                <th rowspan="2" style="vertical-align: middle;">Pengeluaran</th>
                <th rowspan="2" style="vertical-align: middle;">Netto</th>
                <th colspan="{{ $totalPerMonth->first()['payment_types']->count() }}" style="vertical-align: middle;">
                    Total
                </th>
            </tr>
            <tr>
                @foreach ($totalPerMonth->first()['payment_types'] as $paymentTypeName => $paymentTypeAmount)
                    <th>{{ $paymentTypeName }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($totalPerMonth as $totalPerDay)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-nowrap">{{ \Carbon\Carbon::parse($totalPerDay['date'])->translatedFormat('j M Y') }}</td>
                    <td class="text-right">{{ change_decimal_format_to_currency($totalPerDay['amount']) }}</td>
                    <td class="text-right">{{ change_decimal_format_to_currency($totalPerDay['operational_cost']) }}</td>
                    <td class="text-right">{{ change_decimal_format_to_currency($totalPerDay['lab_cost']) }}</td>
                    @foreach ($totalPerDay['doctor_cost'] as $doctorName => $doctorCost)
                        <td class="text-right">{{ change_decimal_format_to_currency($doctorCost) }}</td>
                    @endforeach
                    <td class="text-right">{{ change_decimal_format_to_currency($totalPerDay['clinic_cost']) }}</td>
                    <td class="text-right">{{ change_decimal_format_to_currency($totalPerDay['zakat']) }}</td>
                    <td class="text-right">{{ change_decimal_format_to_currency($totalPerDay['expenses']) }}</td>
                    <td class="text-right">{{ change_decimal_format_to_currency($totalPerDay['netto']) }}</td>
                    @foreach ($totalPerDay['payment_types'] as $paymentTypeName => $paymentTypeAmount)
                        <td class="text-right">{{ change_decimal_format_to_currency($paymentTypeAmount) }}</td>
                    @endforeach
                </tr>
            @endforeach
            <tr class="font-weight-bold text-right">
                <td colspan="2">Total</td>
                <td>{{ change_decimal_format_to_currency($totalPerMonth->sum('amount')) }}</td>
                <td>{{ change_decimal_format_to_currency($totalPerMonth->sum('operational_cost')) }}</td>
                <td>{{ change_decimal_format_to_currency($totalPerMonth->sum('lab_cost')) }}</td>
                @foreach ($totalPerMonth->first()['doctor_cost'] as $doctorName => $doctorAmount)
                    <td class="text-right">{{ change_decimal_format_to_currency($totalPerMonth->sum(function ($totalPerDay) use($doctorName) {
                        return $totalPerDay['doctor_cost'][$doctorName];
                    })) }}</td>
                @endforeach
                <td>{{ change_decimal_format_to_currency($totalPerMonth->sum('clinic_cost')) }}</td>
                <td>{{ change_decimal_format_to_currency($totalPerMonth->sum('zakat')) }}</td>
                <td>{{ change_decimal_format_to_currency($totalPerMonth->sum('expenses')) }}</td>
                <td>{{ change_decimal_format_to_currency($totalPerMonth->sum('netto')) }}</td>
                @foreach ($totalPerMonth->first()['payment_types'] as $paymentTypeName => $paymentTypeAmount)
                    <td class="text-right">{{ change_decimal_format_to_currency($totalPerMonth->sum(function ($totalPerDay) use($paymentTypeName) {
                        return $totalPerDay['payment_types'][$paymentTypeName];
                    })) }}</td>
                @endforeach
            </tr>


            
        </tbody>
    </table>

@endsection
