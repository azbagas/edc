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
            LAPORAN HARIAN ELY DENTAL CLINIC<br>
            KECAMATAN BALARAJA KABUPATEN TANGERANG<br>
            @if ($startDate->format('d-m-Y') == $endDate->format('d-m-Y'))
                {{ strtoupper($startDate->translatedFormat('j F Y')) }}
            @else
                {{ strtoupper($startDate->translatedFormat('j F Y')) }} - {{ strtoupper($endDate->translatedFormat('j F Y')) }}
            @endif
        </h5>
    </div>

    <table width="100%" class="content">
        <thead style="background-color: lightgray;">
            <tr>
                <th rowspan="2" style="vertical-align: middle;">No</th>
                <th colspan="2" rowspan="2" style="vertical-align: middle;">Kegiatan / Keadaan Kesehatan Gigi</th>
                <th colspan="2">Pasien Baru</th>
                <th colspan="2">Pasien Lama</th>
                <th colspan="2">Total</th>
            </tr>
            <tr>
                {{-- Baru --}}
                <th width="40px">L</th>
                <th width="40px">P</th>
                {{-- Lama --}}
                <th width="40px">L</th>
                <th width="40px">P</th>
                {{-- Total --}}
                <th width="40px">L</th>
                <th width="40px">P</th>
            </tr>
        </thead>
        <tbody>
            <tr class="font-weight-bold">
                <td class="text-center">I</td>
                <td colspan="8">Kunjungan Pasien</td>
            </tr>
            @foreach ($patientTypesCount as $item)
                <tr>
                    <td></td>
                    <td width="25px" class="text-center">{{ $loop->iteration }}</td>
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
                <td class="text-center">II</td>
                <td colspan="8">Pelayanan Medik Dasar Gigi</td>
            </tr>
            @foreach ($treatmentTypesCount as $item)
                <tr>
                    <td></td>
                    <td class="text-center">{{ $loop->iteration }}</td>
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
                <td class="text-center">III</td>
                <td colspan="8">Penyakit dan Kelainan Gigi / Mulut</td>
            </tr>
            @foreach ($diseasesCount as $item)
                <tr>
                    <td></td>
                    <td class="text-center">{{ $loop->iteration }}</td>
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

    <br>
    <br>

    <table width="100%">
        <tr>
            <td class="text-center" width="50%">
                Mengetahui
            </td>
            <td class="text-center" width="50%">
                Balaraja, ...............................
            </td>
        </tr>
        <tr>
            <td colspan="2" height="60px">
                
            </td>
        </tr>
        <tr>
            <td class="text-center" width="50%">
                
                <span style="border-bottom: 0.5px solid black; width: 35%; color:transparent">
                    --------------------------
                </span>
                <br>
                <span style="color: transparent">-</span>
            </td>
            <td class="text-center" width="50%">
                <span style="border-bottom: 0.5px solid black; width: 35%">
                    {{ $doctor->user->name }}
                </span>
                <br>
                {{ $doctor->sip }}
            </td>
        </tr>
    </table>

@endsection
