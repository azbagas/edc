@extends('layouts.app')

@section('title', 'Detail Pertemuan')

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail Pertemuan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ session('appointments_url') ?? '/appointments' }}">Pertemuan</a></li>
                        <li class="breadcrumb-item active">Detail Pertemuan</li>
                    </ol>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            {{-- <div class="row">
                <div class="col text-right">
                    <a href="/appointments/{{ $appointment->id }}/examination" class="btn btn-warning btn-sm mb-3">
                        <i class="fa fa-pen mr-2"></i>Edit
                    </a>
                </div>
            </div> --}}

            <div class="row">
                <div class="col">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                Invoice
                            </h3>

                            <div class="card-tools">
                                <form action="/appointments/{{ $appointment->id }}" method="POST" class="d-inline">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm delete-button">
                                        <i class="fa fa-trash"></i> Hapus pertemuan
                                    </button>
                                </form>
                                <a href="/patients/{{ $appointment->patient_id }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-eye"></i> Lihat pasien
                                </a>
                                <a href="/appointments/{{ $appointment->id }}/examination" class="btn btn-warning btn-sm">
                                    <i class="fa fa-pen"></i> Edit
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="invoice border-0">

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <h4>
                                            <i class="fas fa-tooth"></i> Ely Dental Clinic
                                            <small
                                                class="float-right">{{ \Carbon\Carbon::parse($appointment->date_time)->translatedFormat('l, j F Y') }}</small>
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
                                            
                                            <dt class="col-sm-3 col-xl-2">Asisten</dt>
                                            <dd class="col-sm-9 col-xl-10"><span class="d-none d-sm-inline">:</span> {{ $appointment->assistant->name }}</dd>
                                            
                                            <dt class="col-sm-3 col-xl-2">Admin</dt>
                                            <dd class="col-sm-9 col-xl-10"><span class="d-none d-sm-inline">:</span> {{ $appointment->admin->user->name }}</dd>
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
                                                        <td>{{ $diagnosis->disease->disease_code }} - {{ $diagnosis->disease->name }}</td>
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
                                                        <td class="col-2 text-right">Rp{{ change_decimal_format_to_currency($treatment->pivot->price) }}</td>
                                                    </tr>
                                                    @if ($loop->last)
                                                        <tr class="font-weight-bold">
                                                            <td colspan="4" class="text-right">Total</td>
                                                            <td class="text-right">Rp{{ change_decimal_format_to_currency($subTotalTreatments) }}</td>
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
                                                        <td>Rp{{ change_decimal_format_to_currency($medicine->pivot->price) }}</td>
                                                        <td>{{ $medicine->pivot->quantity }} {{ $medicine->unit }}</td>
                                                        <td class="col-2 text-right">Rp{{ change_decimal_format_to_currency($medicine->pivot->quantity * $medicine->pivot->price) }}</td>
                                                    </tr>
                                                    @if ($loop->last)
                                                        <tr class="font-weight-bold">
                                                            <td colspan="4" class="text-right">Total</td>
                                                            <td class="text-right">Rp{{ change_decimal_format_to_currency($subTotalMedicines) }}</td>
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
        
                                @if ($appointment->payment)
                                    <div class="row mb-3">
            
                                        <div class="col-lg-8 mb-3 mb-lg-0">
                                            {{-- <h5>Metode Pembayaran</h5>
                                            <p>
                                                {{ $appointment->payment->payment_type->name }}
                                            </p> --}}
                                        </div>
            
                                        <div class="col-lg-4">
                                            <h5>Total</h5>
                                            <dl class="row">
                                                <dt class="col-sm-4 col-md-3 col-lg-4">Total tindakan</dt>
                                                <dd class="col-sm-8 col-md-9 col-lg-8"><span class="d-none d-sm-inline">:</span> Rp{{ change_decimal_format_to_currency($subTotalTreatments) }}</dd>
            
                                                <dt class="col-sm-4 col-md-3 col-lg-4">Total obat</dt>
                                                <dd class="col-sm-8 col-md-9 col-lg-8"><span class="d-none d-sm-inline">:</span> Rp{{ change_decimal_format_to_currency($subTotalMedicines) }}</dd>
                                                
                                                <div class="col-12">
                                                    <hr class="p-1 m-0">
                                                </div>
                                                
                                                <dt class="col-sm-4 col-md-3 col-lg-4">Grand total</dt>
                                                <dd class="col-sm-8 col-md-9 col-lg-8"><span class="d-none d-sm-inline">:</span> Rp{{ change_decimal_format_to_currency($appointment->payment->amount) }}</dd>
                                            </dl>
                                        </div>
            
                                    </div>
                                @endif
        
        
                                <div class="row no-print">
                                    <div class="col-12">
                                        <a href="/appointments/{{ $appointment->id }}?download=pdf" target="_blank" class="btn btn-default">
                                            <i class="fas fa-download"></i> Download PDF
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                Pembayaran & Catatan Klinik
                            </h3>

                            <div class="card-tools">
                                <a href="/appointments/{{ $appointment->id }}/payment" class="btn btn-warning btn-sm">
                                    <i class="fa fa-pen"></i> Edit
                                </a>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-sm-6">
                                    <dl class="row">
                                        <dt class="col-sm-5 col-xl-4">Uang Pasien</dt>
                                        <dd class="col-sm-7 col-xl-8"><span class="d-none d-sm-inline">:</span> Rp{{ change_decimal_format_to_currency($appointment->payment->patient_money) }}</dd>
                                        
                                        <dt class="col-sm-5 col-xl-4">Sisa</dt>
                                        <dd class="col-sm-7 col-xl-8"><span class="d-none d-sm-inline">:</span> Rp{{ change_decimal_format_to_currency($appointment->payment->amount - $appointment->payment->patient_money) }}</dd>
                                        
                                        <dt class="col-sm-5 col-xl-4">Metode Pembayaran</dt>
                                        <dd class="col-sm-7 col-xl-8"><span class="d-none d-sm-inline">:</span> {{ $appointment->payment->payment_type->name }}</dd>
                                        
                                        <dt class="col-sm-5 col-xl-4">Status Pembayaran</dt>
                                        <dd class="col-sm-7 col-xl-8"><span class="d-none d-sm-inline">:</span> <span class="badge badge-{{ $appointment->payment->status == 'Lunas' ? 'success' : 'danger' }}">{{ $appointment->payment->status }}</span></dd>
                                    </dl>
                                </div>
                                <div class="col-sm-6">
                                    <dl class="row">
                                        <dt class="col-sm-5 col-xl-4">Biaya Operasional</dt>
                                        <dd class="col-sm-7 col-xl-8"><span class="d-none d-sm-inline">:</span> Rp{{ change_decimal_format_to_currency($appointment->payment->operational_cost) }}</dd>
                                        
                                        <dt class="col-sm-5 col-xl-4">Biaya Lab</dt>
                                        <dd class="col-sm-7 col-xl-8"><span class="d-none d-sm-inline">:</span> Rp{{ change_decimal_format_to_currency($appointment->payment->lab_cost) }}</dd>
                                        
                                        <dt class="col-sm-5 col-xl-4">Datang kembali tanggal</dt>
                                        <dd class="col-sm-7 col-xl-8"><span class="d-none d-sm-inline">:</span>
                                            @if ($appointment->next_appointment_date_time)
                                                {{ \Carbon\Carbon::parse($appointment->next_appointment_date_time)->translatedFormat('j F Y, H:i') }}        
                                            @else
                                                -
                                            @endif
                                        </dd>
        
                                        <dt class="col-sm-5 col-xl-4">Catatan</dt>
                                        <dd class="col-sm-7 col-xl-8"><span class="d-none d-sm-inline">:</span>
                                            @if ($appointment->payment->note)
                                                {{ $appointment->payment->note }}        
                                            @else
                                                -
                                            @endif
                                        </dd>
                                    </dl>
    
                                </div>
                            </div>
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
            // Sweet alert delete appointment
            $('.delete-button').click(function(event) {
                let form = $(this).closest("form");
                event.preventDefault();
                Swal.fire({
                    title: "Anda yakin ingin menghapus pertemuan ini?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Tidak"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            })
        });
    </script>
@endpush
