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
                        <li class="breadcrumb-item"><a href="/patients">Data Pasien</a></li>
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
            <div class="row">
                <div class="col">

                    <a href="{{ url()->previous() }}" class="btn btn-primary btn-sm mb-3">
                        <i class="fa fa-arrow-left mr-2"></i>Kembali
                    </a>

                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title mt-1">Data Diri Pasien</h3>

                            <div class="card-tools">
                                <form action="/patients/{{ $patient->id }}" method="POST" class="d-inline">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm" id="delete-button">
                                        <i class="fa fa-trash"></i> Hapus Pasien
                                    </button>
                                </form>
                                <a href="/patients/{{ $patient->id }}/edit" class="btn btn-warning btn-sm">
                                    <i class="fa fa-pen"></i> Edit
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3 col-xl-2">
                                    <p class="font-weight-bold mb-0">Nomor Pasien</p>
                                </div>
                                <div class="col-sm-9 col-xl-10">
                                    <p><span class="d-none d-sm-inline mr-2">:</span>{{ $patient->id }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 col-xl-2">
                                    <p class="font-weight-bold mb-0">Nama</p>
                                </div>
                                <div class="col-sm-9 col-xl-10">
                                    <p><span class="d-none d-sm-inline mr-2">:</span>{{ $patient->name }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 col-xl-2">
                                    <p class="font-weight-bold mb-0">Tanggal Lahir</p>
                                </div>
                                <div class="col-sm-9 col-xl-10">
                                    <p><span class="d-none d-sm-inline mr-2">:</span>{{ $patient->date_of_birth }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 col-xl-2">
                                    <p class="font-weight-bold mb-0">Jenis Kelamin</p>
                                </div>
                                <div class="col-sm-9 col-xl-10">
                                    <p><span class="d-none d-sm-inline mr-2">:</span>{{ $patient->gender }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 col-xl-2">
                                    <p class="font-weight-bold mb-0">Nomor Telepon</p>
                                </div>
                                <div class="col-sm-9 col-xl-10">
                                    <p><span class="d-none d-sm-inline mr-2">:</span>{{ $patient->phone }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 col-xl-2">
                                    <p class="font-weight-bold mb-0">Alamat</p>
                                </div>
                                <div class="col-sm-9 col-xl-10">
                                    <p><span class="d-none d-sm-inline mr-2">:</span>{{ $patient->address }}</p>
                                </div>
                            </div>
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
                                    <span class="bg-success font-weight-normal">{{ \Carbon\Carbon::createFromFormat('Y-m-d', $appointment->date)->translatedFormat('l, j F Y') }}</span>
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
                                                    <span class="badge badge-success">{{ $appointment->status }}</span>
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
                                                    <b class="d-block">Komplain</b>
                                                    <span>{{ $appointment->complaint }}</span>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <b class="d-block">Diagnosa</b>
                                                    <ul>
                                                        @forelse ($appointment->diagnoses as $diagnose)
                                                            <li>{{ $diagnose->name }}</li>
                                                        @empty 
                                                            <li>Tidak ada treatment</li>
                                                        @endforelse
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <b class="d-block">Tindakan</b>
                                                    <ul>
                                                        @forelse ($appointment->treatments as $treatment)
                                                            <li>{{ $treatment->name }}</li>
                                                        @empty 
                                                            <li>Tidak ada treatment</li>
                                                        @endforelse
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Placement of additional controls. Optional -->
                                        <div class="timeline-footer">
                                            <a href="#" class="btn btn-primary btn-sm">Lihat Detail</a>
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
            $('#delete-button').click(function(event) {
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
