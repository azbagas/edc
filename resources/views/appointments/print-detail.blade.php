@extends('layouts.print')

@section('title', $title ?? 'Detail Pertemuan')

@section('styles')
    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }

        table {
            font-size: x-small;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
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

    <table width="100%">
        <tr>
            <td>
                <table>
                    <tr>
                        <td>
                            <strong>Nomor Pasien</strong>
                        </td>
                        <td>
                            : {{ $appointment->patient_id }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Nama</strong>
                        </td>
                        <td>
                            : {{ $appointment->patient->name }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Tanggal Lahir</strong>
                        </td>
                        <td>
                            : {{ $appointment->patient->date_of_birth }} ({{ $appointment->patient->age }} tahun)
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Jenis Kelamin</strong>
                        </td>
                        <td>
                            : {{ $appointment->patient->gender }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>No Telp</strong>
                        </td>
                        <td>
                            : {{ $appointment->patient->phone }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Alamat</strong>
                        </td>
                        <td>
                            : {{ $appointment->patient->address }}
                        </td>
                    </tr>
                </table>
            </td>
            <td valign='top'>
                <table>
                    <tr>
                        <td>
                            <strong>No. Pertemuan</strong>
                        </td>
                        <td>
                            : {{ format_appointment_id($appointment->id) }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Tanggal</strong>
                        </td>
                        <td>
                            : {{ \Carbon\Carbon::parse($appointment->date_time)->translatedFormat('j F Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Dokter</strong>
                        </td>
                        <td>
                            : {{ $appointment->doctor->user->name }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <br />

    <div style="font-size:small">Diagnosis</div>
    <table width="100%">
        <thead style="background-color: lightgray;">
            <tr>
                <th width="30px">No</th>
                <th>Diagnosis</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($appointment->diagnoses as $diagnosis)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $diagnosis->diagnosis_code }} - {{ $diagnosis->name }}</td>
                    <td>{{ $diagnosis->pivot->note }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        Tidak ada diagnosis
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <br>

    <div style="font-size:small">Tindakan</div>
    <table width="100%">
        <thead style="background-color: lightgray;">
            <tr>
                <th width="30px">No</th>
                <th>Tindakan</th>
                <th>Keterangan</th>
                <th width="100px">Harga</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($appointment->treatments as $treatment)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $treatment->treatment_type->name }} - {{ $treatment->name }}</td>
                    <td>{{ $treatment->pivot->note }}</td>
                    <td align="right">Rp{{ change_decimal_format_to_currency($treatment->pivot->price) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">
                        Tidak ada tindakan
                    </td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td align="right" colspan="3">Total</td>
                <td align="right" class="gray">Rp{{ change_decimal_format_to_currency($subTotalTreatments) }}</td>
            </tr>
        </tfoot>
    </table>

    <br>

    <div style="font-size:small">Obat</div>
    <table width="100%">
        <thead style="background-color: lightgray;">
            <tr>
                <th width="30px">No</th>
                <th>Obat</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th width="100px">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($appointment->medicines as $medicine)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $medicine->name }} @if ($medicine->dose) {{ $medicine->dose }} @endif</td>
                    <td align="right">Rp{{ change_decimal_format_to_currency($medicine->pivot->price) }}</td>
                    <td align="right">{{ $medicine->pivot->quantity }} {{ $medicine->unit }}</td>
                    <td align="right">
                        Rp{{ change_decimal_format_to_currency($medicine->pivot->quantity * $medicine->pivot->price) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        Tidak ada obat
                    </td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td align="right" colspan="4">Total</td>
                <td align="right" class="gray">Rp{{ change_decimal_format_to_currency($subTotalMedicines) }}</td>
            </tr>
        </tfoot>
    </table>

    <br>

    <table width="100%">
        <tfoot>
            <tr>
                <td align="right">Grand Total</td>
                <td align="right" width="100px" class="gray">Rp{{ change_decimal_format_to_currency($appointment->payment->amount) }}</td>
            </tr>
        </tfoot>
    </table>

    <br>
    <br>

    <table width="40%" style="border: 0.5px solid black;">
        <tr>
            <td>Uang</td>
            <td>: Rp{{ change_decimal_format_to_currency($appointment->payment->patient_money) }}</td>
        </tr>
        <tr>
            <td>Sisa</td>
            <td>: Rp{{ change_decimal_format_to_currency($appointment->payment->amount - $appointment->payment->patient_money) }}</td>
        </tr>
        <tr>
            <td>Metode Pembayaran</td>
            <td>: {{ $appointment->payment->payment_type->name }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>: {{ $appointment->payment->status }}</td>
        </tr>
        <tr>
            <td>Datang kembali tanggal</td>
            <td>:
                @if ($appointment->next_appointment_date_time)
                    {{ \Carbon\Carbon::parse($appointment->next_appointment_date_time)->translatedFormat('j F Y, H:i') }}        
                @else
                    -
                @endif
            </td>
        </tr>
    </table>

@endsection
