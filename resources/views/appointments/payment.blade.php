@extends('layouts.app')

@section('title', 'Pembayaran')

@section('styles')
    <style>
        #date_time:hover {
            cursor: pointer;
        }

        #date_time {
            caret-color: transparent;
        }
    </style>
@endsection

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pembayaran</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/appointments">Pertemuan</a></li>
                        <li class="breadcrumb-item active">Pembayaran</li>
                    </ol>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">


                    <div class="invoice p-3 mb-3">

                        <div class="row mb-2">
                            <div class="col-12">
                                <h4>
                                    <i class="fas fa-tooth"></i> Ely Dental Clinic
                                    <small
                                        class="float-right">{{ \Carbon\Carbon::parse($appointment->created_at)->translatedFormat('l, j F Y') }}</small>
                                </h4>
                            </div>
                        </div>

                        <div class="row invoice-info">
                            <div class="col-6 invoice-col">
                                <dl class="row">
                                    <dt class="col-sm-5 col-xl-4">Nomor Pasien</dt>
                                    <dd class="col-sm-7 col-xl-8"><span class="d-none d-sm-inline">:</span>
                                        {{ $appointment->patient->id }}</dd>

                                    <dt class="col-sm-5 col-xl-4">Nama</dt>
                                    <dd class="col-sm-7 col-xl-8"><span class="d-none d-sm-inline">:</span>
                                        {{ $appointment->patient->name }}</dd>

                                    <dt class="col-sm-5 col-xl-4">Tanggal Lahir</dt>
                                    <dd class="col-sm-7 col-xl-8"><span class="d-none d-sm-inline">:</span>
                                        {{ $appointment->patient->date_of_birth }} ({{ $appointment->patient->age }} tahun)
                                    </dd>

                                    <dt class="col-sm-5 col-xl-4">Jenis Kelamin</dt>
                                    <dd class="col-sm-7 col-xl-8"><span class="d-none d-sm-inline">:</span>
                                        {{ $appointment->patient->gender }}</dd>

                                    <dt class="col-sm-5 col-xl-4">Nomor Telepon</dt>
                                    <dd class="col-sm-7 col-xl-8"><span class="d-none d-sm-inline">:</span>
                                        {{ $appointment->patient->phone }}</dd>

                                    <dt class="col-sm-5 col-xl-4">Alamat</dt>
                                    <dd class="col-sm-7 col-xl-8"><span class="d-none d-sm-inline">:</span>
                                        {{ $appointment->patient->address }}</dd>
                                </dl>
                            </div>
                            <div class="col-6 invoice-col">
                                <dl class="row">
                                    <dt class="col-sm-3 col-xl-2">Dokter</dt>
                                    <dd class="col-sm-9 col-xl-10"><span class="d-none d-sm-inline">:</span>
                                        {{ $appointment->doctor->user->name }}</dd>
                                </dl>
                            </div>
                        </div>

                        <h5>Diagnosis</h5>
                        <div class="row mb-3">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="col-1">No</th>
                                            <th>Penyakit</th>
                                            <th>Diagnosis</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($appointment->diagnoses as $diagnosis)
                                            <tr>
                                                <td class="col-1">{{ $loop->iteration }}</td>
                                                <td>{{ $diagnosis->disease->disease_code }} -
                                                    {{ $diagnosis->disease->name }}</td>
                                                <td>{{ $diagnosis->diagnosis_code }} - {{ $diagnosis->name }}</td>
                                                <td>{{ $diagnosis->pivot->note }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">
                                                    Tidak ada diagnosis
                                                </td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <h5>Tindakan</h5>
                        <div class="row mb-3">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="col-1">No</th>
                                            <th>Jenis Tindakan</th>
                                            <th>Tindakan</th>
                                            <th>Keterangan</th>
                                            <th class="col-2">Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($appointment->treatments as $treatment)
                                            <tr>
                                                <td class="col-1">{{ $loop->iteration }}</td>
                                                <td>{{ $treatment->treatment_type->name }}</td>
                                                <td>{{ $treatment->name }}</td>
                                                <td>{{ $treatment->pivot->note }}</td>
                                                <td class="col-2 text-right">
                                                    Rp{{ change_decimal_format_to_currency($treatment->pivot->price) }}
                                                </td>
                                            </tr>
                                            @if ($loop->last)
                                                <tr class="font-weight-bold">
                                                    <td colspan="4" class="text-right">Total</td>
                                                    <td class="text-right">
                                                        Rp{{ change_decimal_format_to_currency($subTotalTreatments) }}</td>
                                                </tr>
                                            @endif
                                        @empty
                                            <tr>
                                                <td colspan="5">
                                                    Tidak ada tindakan
                                                </td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <h5>Obat</h5>
                        <div class="row mb-3">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="col-1">No</th>
                                            <th>Obat</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                            <th class="col-2">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($appointment->medicines as $medicine)
                                            <tr>
                                                <td class="col-1">{{ $loop->iteration }}</td>
                                                <td>{{ $medicine->name }} @if ($medicine->dose)
                                                        {{ $medicine->dose }}
                                                    @endif
                                                </td>
                                                <td>Rp{{ change_decimal_format_to_currency($medicine->pivot->price) }}</td>
                                                <td>{{ $medicine->pivot->quantity }} {{ $medicine->unit }}</td>
                                                <td class="col-2 text-right">
                                                    Rp{{ change_decimal_format_to_currency($medicine->pivot->quantity * $medicine->pivot->price) }}
                                                </td>
                                            </tr>
                                            @if ($loop->last)
                                                <tr class="font-weight-bold">
                                                    <td colspan="4" class="text-right">Total</td>
                                                    <td class="text-right">
                                                        Rp{{ change_decimal_format_to_currency($subTotalMedicines) }}</td>
                                                </tr>
                                            @endif
                                        @empty
                                            <tr>
                                                <td colspan="5">
                                                    Tidak ada obat
                                                </td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row mb-3">

                            <div class="col-lg-8 mb-3 mb-lg-0">

                            </div>

                            <div class="col-lg-4">
                                <h5>Total</h5>
                                <dl class="row">
                                    <dt class="col-sm-4 col-md-3 col-lg-4">Total tindakan</dt>
                                    <dd class="col-sm-8 col-md-9 col-lg-8"><span class="d-none d-sm-inline">:</span>
                                        Rp{{ change_decimal_format_to_currency($subTotalTreatments) }}</dd>

                                    <dt class="col-sm-4 col-md-3 col-lg-4">Total obat</dt>
                                    <dd class="col-sm-8 col-md-9 col-lg-8"><span class="d-none d-sm-inline">:</span>
                                        Rp{{ change_decimal_format_to_currency($subTotalMedicines) }}</dd>

                                    <div class="col-12">
                                        <hr class="p-1 m-0">
                                    </div>

                                    <dt class="col-sm-4 col-md-3 col-lg-4">Grand total</dt>
                                    <dd class="col-sm-8 col-md-9 col-lg-8"><span class="d-none d-sm-inline">:</span>
                                        Rp{{ change_decimal_format_to_currency($grandTotal) }}</dd>
                                </dl>
                            </div>

                        </div>
                    </div>


                </div>
            </div>
            <!-- /.row -->

            <form action="/appointments/{{ $appointment->id }}/payment" method="POST" autocomplete="off"
                spellcheck="false">
                @method('put')
                @csrf
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Pembayaran
                                </h3>
                            </div>
        
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <label for="payment_type_id">Metode pembayaran<span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control @error('payment_type_id') is-invalid @enderror"
                                                name="payment_type_id" id="payment_type_id" required>
                                                @foreach ($paymentTypes as $paymentType)
                                                    <option value="{{ $paymentType->id }}" @selected($paymentType->id == old('payment_type_id', $appointment->payment->payment_type_id ?? false))>
                                                        {{ $paymentType->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('payment_type_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col">

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Catatan Klinik
                                </h3>
                            </div>

                            <div class="card-body">
                                
                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <label for="operational_cost">Biaya operasional</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input id="operational_cost" type="text" name="operational_cost"
                                                    class="form-control rupiah" placeholder="0,00"
                                                    value="{{ optional($appointment->payment)->operational_cost ? change_decimal_format_to_currency($appointment->payment->operational_cost) : 0 }}"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <label for="date_time">Datang kembali tanggal</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <input type="hidden" name="date_time">
                                                <input type="text" class="form-control float-right" id="date_time">
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <label for="note">Catatan</label>
                                            <textarea id="note" name="note" class="form-control @error('note') is-invalid @enderror" rows="3" 
                                                placeholder="Masukkan catatan (opsional)...">{{ old('note', $appointment->payment->note) }}</textarea>
                                            @error('note')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col text-right">
                        <button type="submit" class="btn btn-success mb-3 mt-5">Selesai</button>
                    </div>
                </div>
            </form>


        </div><!-- /.container-fluid -->


    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(`.rupiah`).inputmask("currency", {
                radixPoint: ',',
                allowMinus: false,
            });

            // Initial date_time
            let start = moment();
            @if(old('date_time', $appointment->next_appointment_date_time))
                startStr = "{{ old('date_time', $appointment->next_appointment_date_time) }}";
                start = moment(startStr, "Y-MM-DD HH:mm");
                $('input[name="date_time"]').val(start.format('Y-MM-DD HH:mm'));
                $('#date_time').val(start.format('D MMMM YYYY, HH:mm'));
            @endif

            $('#date_time').daterangepicker({
                autoUpdateInput: false,
                singleDatePicker: true,
                timePicker: true,
                timePicker24Hour: true,
                showDropdowns: true,
                locale: {
                    format: 'D MMMM YYYY, HH:mm',
                    cancelLabel: 'Clear'
                },
                minDate: moment("01-01-2020", "DD-MM-YYYY"),
                maxDate: moment().add(5, 'years').endOf('year')
            });

            // Update input ketika daterangepicker diapply
            $('#date_time').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('D MMMM YYYY, HH:mm'));
                $('input[name="date_time"]').val(picker.startDate.format('Y-MM-DD HH:mm'));
            });

            // Hapus nilai input ketika daterangepicker dicancel
            $('#date_time').on('cancel.daterangepicker', function() {
                $(this).val('');
                $('input[name="date_time"]').val('');
            });
        });
    </script>
@endpush
