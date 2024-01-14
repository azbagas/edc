@extends('layouts.app')

@section('title', 'Pembayaran')

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pembayaran</h1>
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

                    <a href="{{ url()->previous() }}" class="btn btn-primary btn-sm mb-3">
                        <i class="fa fa-arrow-left mr-2"></i>Kembali
                    </a>

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
                                    <dd class="col-sm-7 col-xl-8"><span class="d-none d-sm-inline">:</span> {{ $appointment->patient->id }}</dd>

                                    <dt class="col-sm-5 col-xl-4">Nama</dt>
                                    <dd class="col-sm-7 col-xl-8"><span class="d-none d-sm-inline">:</span> {{ $appointment->patient->name }}</dd>

                                    <dt class="col-sm-5 col-xl-4">Tanggal Lahir</dt>
                                    <dd class="col-sm-7 col-xl-8"><span class="d-none d-sm-inline">:</span> {{ $appointment->patient->date_of_birth }} ({{ $appointment->patient->age }} tahun)</dd>

                                    <dt class="col-sm-5 col-xl-4">Jenis Kelamin</dt>
                                    <dd class="col-sm-7 col-xl-8"><span class="d-none d-sm-inline">:</span> {{ $appointment->patient->gender }}</dd>
                                    
                                    <dt class="col-sm-5 col-xl-4">Nomor Telepon</dt>
                                    <dd class="col-sm-7 col-xl-8"><span class="d-none d-sm-inline">:</span> {{ $appointment->patient->phone }}</dd>
                                    
                                    <dt class="col-sm-5 col-xl-4">Alamat</dt>
                                    <dd class="col-sm-7 col-xl-8"><span class="d-none d-sm-inline">:</span> {{ $appointment->patient->address }}</dd>
                                </dl>
                            </div>
                            <div class="col-6 invoice-col">
                                <dl class="row">
                                    <dt class="col-sm-3 col-xl-2">Dokter</dt>
                                    <dd class="col-sm-9 col-xl-10"><span class="d-none d-sm-inline">:</span> {{ $appointment->doctor->user->name }}</dd>
                                </dl>
                            </div>
                        </div>

                        <h5>Diagnosa</h5>
                        <div class="row mb-3">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="col-1">No</th>
                                            <th>Penyakit</th>
                                            <th>Diagnosa</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($appointment->diagnoses as $diagnose)
                                            <tr>
                                                <td class="col-1">{{ $loop->iteration }}</td>
                                                <td>{{ $diagnose->disease->disease_code }} - {{ $diagnose->disease->name }}</td>
                                                <td>{{ $diagnose->diagnose_code }} - {{ $diagnose->name }}</td>
                                                <td>{{ $diagnose->pivot->note }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">
                                                    Tidak ada diagnosa
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
                                                <td class="col-2 text-right">Rp{{ number_format($treatment->pivot->price, 2, ',', '.') }}</td>
                                            </tr>
                                            @if ($loop->last)
                                                <tr class="font-weight-bold">
                                                    <td colspan="4" class="text-right">Total</td>
                                                    <td class="text-right">Rp{{ number_format($subTotalTreatments, 2, ',', '.') }}</td>
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
                                                <td>{{ $medicine->name }} @if ($medicine->dose) {{ $medicine->dose }} @endif</td>
                                                <td>Rp{{ number_format($medicine->pivot->price, 2, ',', '.') }}</td>
                                                <td>{{ $medicine->pivot->quantity }} {{ $medicine->unit }}</td>
                                                <td class="col-2 text-right">Rp{{ number_format($medicine->pivot->quantity * $medicine->pivot->price, 2, ',', '.') }}</td>
                                            </tr>
                                            @if ($loop->last)
                                                <tr class="font-weight-bold">
                                                    <td colspan="4" class="text-right">Total</td>
                                                    <td class="text-right">Rp{{ number_format($subTotalMedicines, 2, ',', '.') }}</td>
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
                                    <dd class="col-sm-8 col-md-9 col-lg-8"><span class="d-none d-sm-inline">:</span> Rp{{ number_format($subTotalTreatments, 2, ',', '.') }}</dd>

                                    <dt class="col-sm-4 col-md-3 col-lg-4">Total obat</dt>
                                    <dd class="col-sm-8 col-md-9 col-lg-8"><span class="d-none d-sm-inline">:</span> Rp{{ number_format($subTotalMedicines, 2, ',', '.') }}</dd>
                                    
                                    <div class="col-12">
                                        <hr class="p-1 m-0">
                                    </div>
                                    
                                    <dt class="col-sm-4 col-md-3 col-lg-4">Grand total</dt>
                                    <dd class="col-sm-8 col-md-9 col-lg-8"><span class="d-none d-sm-inline">:</span> Rp{{ number_format($grandTotal, 2, ',', '.') }}</dd>
                                </dl>
                            </div>

                        </div>
                    </div>


                </div>
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                Pembayaran
                            </h3>
                        </div>
                        <form action="/appointments/{{ $appointment->id }}/payment" method="POST" autocomplete="off" spellcheck="false">
                            @method('put')
                            @csrf

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <label for="payment_type_id">Metode pembayaran<span class="text-danger">*</span></label>
                                            <select class="form-control @error('payment_type_id') is-invalid @enderror" name="payment_type_id" id="payment_type_id" required>
                                                @foreach ($paymentTypes as $paymentType)
                                                    <option value="{{ $paymentType->id }}" @selected($paymentType->id == old('payment_type_id', $appointment->payment->payment_type_id))>
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
                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <label for="operational_cost">Biaya operasional</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input id="operational_cost" type="text" name="operational_cost" class="form-control rupiah" placeholder="0,00" value="{{ $appointment->payment->operational_cost/1 ?? 0 }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-success">Selesai</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


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
        });
    </script>
@endpush
