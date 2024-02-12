@extends('layouts.app')

@section('title', 'Detail Pasien')

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail Pasien</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ session('patients_url') ?? '/patients' }}">Data Pasien</a></li>
                        <li class="breadcrumb-item active">Detail Pasien</li>
                    </ol>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col">
                    <a href="{{ session("patients_url", "/patients") }}" class="btn btn-info btn-sm">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col">

                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title mt-1">Data Diri Pasien</h3>

                            @can('admin')
                                <div class="card-tools">
                                    <form action="/patients/{{ $patient->id }}" method="POST" class="d-inline">
                                        @method('delete')
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm delete-button">
                                            <i class="fa fa-trash"></i> Hapus Pasien
                                        </button>
                                    </form>
                                    <a href="/patients/{{ $patient->id }}/edit" class="btn btn-warning btn-sm">
                                        <i class="fa fa-pen"></i> Edit
                                    </a>
                                    <a href="/../appointments/create?patient={{ $patient->id }}" class="btn btn-success btn-sm">
                                        <i class="fa fa-plus"></i> Tambah ke pertemuan
                                    </a>
                                </div>
                                
                            @endcan
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-4 col-md-3 col-xl-2">Nomor Pasien</dt>
                                <dd class="col-sm-8 col-md-9 col-xl-10"><span class="d-none d-sm-inline">:</span> {{ $patient->id }}</dd>

                                <dt class="col-sm-4 col-md-3 col-xl-2">Nama</dt>
                                <dd class="col-sm-8 col-md-9 col-xl-10"><span class="d-none d-sm-inline">:</span> {{ $patient->name }}</dd>

                                <dt class="col-sm-4 col-md-3 col-xl-2">Tanggal Lahir</dt>
                                <dd class="col-sm-8 col-md-9 col-xl-10"><span class="d-none d-sm-inline">:</span> {{ $patient->date_of_birth }} ({{ $patient->age }} tahun)</dd>

                                <dt class="col-sm-4 col-md-3 col-xl-2">Jenis Kelamin</dt>
                                <dd class="col-sm-8 col-md-9 col-xl-10"><span class="d-none d-sm-inline">:</span> {{ $patient->gender }}</dd>
                                
                                <dt class="col-sm-4 col-md-3 col-xl-2">Nomor Telepon</dt>
                                <dd class="col-sm-8 col-md-9 col-xl-10"><span class="d-none d-sm-inline">:</span> {{ $patient->phone }}</dd>
                                
                                <dt class="col-sm-4 col-md-3 col-xl-2">Alamat</dt>
                                <dd class="col-sm-8 col-md-9 col-xl-10"><span class="d-none d-sm-inline">:</span> {{ $patient->address }}</dd>
                            </dl>
                        </div>
                    </div>


                </div>
            </div>
            <!-- /.row -->
            <h4 class="my-3">Riwayat Pertemuan</h4>
            <div class="row">
                <div class="col">

                    @if ($appointments->count())
                        <!-- Main node for this component -->
                        <div class="timeline">
                            @foreach ($appointments as $appointment)
                                <!-- Timeline time label -->
                                <div class="time-label">
                                    <span class="bg-success font-weight-normal">{{ \Carbon\Carbon::parse($appointment->date_time)->translatedFormat('l, j F Y') }}</span>
                                </div>
                                <div>
                                    <!-- Before each timeline item corresponds to one icon on the left scale -->
                                    <i class="fas fa-notes-medical bg-blue"></i>
                                    <!-- Timeline item -->
                                    <div class="timeline-item mb-4">
                                        <!-- Time -->
                                        {{-- <span class="time"><i class="fas fa-clock"></i> 12:05</span> --}}
                                        <!-- Header. Optional -->
                                        <h3 class="timeline-header">
                                            <div class="row">
                                                <div class="col-md-auto order-md-last mb-3">
                                                    <span class="badge badge-{{ $appointment->status->type }}">{{ $appointment->status->name }}</span>
                                                    <span class="badge badge-{{ ($appointment->payment->status ?? '') == 'Lunas' ? 'success' : 'danger' }}">{{ $appointment->payment->status ?? '' }}</span>
                                                </div>
                                                <div class="col">
                                                    <div class="row mb-3 mb-md-2">
                                                        <div class="col-md-1 mb-1 mb-md-0">
                                                            <b class="mb-0">Dokter</b>
                                                        </div>
                                                        <div class="col-md-11">
                                                            <span><span class="d-none d-md-inline mr-2">:</span>{{ $appointment->doctor->user->name }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3 mb-md-2">
                                                        <div class="col-md-1 mb-1 mb-md-0">
                                                            <b class="mb-0">Asisten</b>
                                                        </div>
                                                        <div class="col-md-11">
                                                            <span><span class="d-none d-md-inline mr-2">:</span>{{ $appointment->assistant->name }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-1 mb-1 mb-md-0">
                                                            <b class="mb-0">Admin</b>
                                                        </div>
                                                        <div class="col-md-11">
                                                            <span><span class="d-none d-md-inline mr-2">:</span>{{ $appointment->admin->user->name }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </h3>
                                        <!-- Body -->
                                        <div class="timeline-body">
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <b class="d-block">Keluhan</b>
                                                    <span>{{ $appointment->complaint }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <b class="d-block">Diagnosa</b>
                                                    <ul>
                                                        @forelse ($appointment->diagnoses as $diagnosis)
                                                            <li class="@if(!$loop->last) mb-2 @endif">
                                                                {{ $diagnosis->name }} ({{ $diagnosis->diagnosis_code }})<br>
                                                                <i>Catatan: </i>
                                                                @if ($diagnosis->pivot->note)
                                                                    {{ $diagnosis->pivot->note }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </li>
                                                        @empty 
                                                            <li>Tidak ada diagnosa</li>
                                                        @endforelse
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <b class="d-block">Tindakan</b>
                                                    <ul>
                                                        @forelse ($appointment->treatments as $treatment)
                                                            <li class="@if(!$loop->last) mb-2 @endif">
                                                                {{ $treatment->treatment_type->name }}: {{ $treatment->name }}<br>
                                                                <i>Catatan: </i>
                                                                @if ($treatment->pivot->note)
                                                                    {{ $treatment->pivot->note }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </li>
                                                        @empty 
                                                            <li>Tidak ada tindakan</li>
                                                        @endforelse
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <b class="d-block">Obat</b>
                                                    <ul>
                                                        @forelse ($appointment->medicines as $medicine)
                                                            <li class="@if(!$loop->last) mb-2 @endif">
                                                                {{ $medicine->name }} {{ $medicine->dose }} x{{ $medicine->pivot->quantity }} {{ $medicine->unit }}
                                                            </li>
                                                        @empty 
                                                            <li>Tidak ada obat</li>
                                                        @endforelse
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Placement of additional controls. Optional -->
                                        <div class="timeline-footer">
                                            <a href="/appointments/{{ $appointment->id }}" class="btn btn-primary btn-sm">Lihat Detail</a>
                                            {{-- <a class="btn btn-danger btn-sm">Delete</a> --}}
                                        </div>
                                    </div>
                                </div>

                                @if ($loop->last)
                                    <!-- The last icon means the story is complete -->
                                    <div>
                                        <i class="fas fa-clock bg-gray"></i>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="mb-3">Belum ada pertemuan.</div>
                    @endif

                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.delete-button').click(function(event) {
                let form = $(this).closest("form");
                event.preventDefault();
                Swal.fire({
                    title: "Anda yakin ingin menghapus pasien ini?",
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
