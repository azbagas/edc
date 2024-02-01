@extends('layouts.app')

@section('title', 'Obat')

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Obat</h1>
                </div><!-- /.col -->

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            @can('admin')
                <div class="row mb-3">
                    <div class="col">
                        <a href="/medicines/create" class="btn btn-primary">
                            <i class="fa fa-plus mr-2"></i>Tambah obat
                        </a>
                    </div>
                </div>
            @endcan


            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="/medicines" method="GET" id="filter-form" spellcheck="false" autocomplete="off">
                            <div class="card-header">
                                <h3 class="card-title">Filter</h3>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-7 col-xl-5">
                                        <div class="form-group">
                                            <label for="name">Cari Obat</label>
                            
                                            <div class="input-group">
                                                <input type="text" id="name" name="name" class="form-control" placeholder="Cari obat..." value="{{ request('name') }}">
                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-info">
                                                      <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-7 col-xl-5">
                                        <div class="form-group">
                                            <label for="medicine_type">Jenis Obat</label>
                                            <select class="form-control" name="medicine_type" id="medicine_type">
                                                <option value="">Semua jenis obat</option>
                                                @foreach ($all_medicine_types as $medicine_type)
                                                    <option value="{{ $medicine_type->id }}" {{ request('medicine_type') == $medicine_type->id ? 'selected' : '' }}>
                                                        {{ $medicine_type->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-7 col-xl-5">
                                        <a href="/medicines" class="btn btn-default btn-sm">
                                            Reset pencarian
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Obat</th>
                                        <th>Nama Obat</th>
                                        <th>Dosis</th>
                                        <th>Stok</th>
                                        <th>Satuan</th>
                                        <th>Harga (Rp)</th>
                                        @can('admin')
                                            <th>Aksi</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($medicine_types as $medicine_type)
                                        @foreach ($medicine_type->medicines as $medicine)
                                            <tr>
                                                
                                                @if ($loop->first)
                                                    <td class="col-1" rowspan="{{ $medicine_type->medicines->count() }}">{{  $loop->parent->iteration }}</td>
                                                    <td rowspan="{{ $medicine_type->medicines->count() }}">{{ $medicine_type->name }}</td>
                                                @endif

                                                <td style="padding: 12px;">{{ $medicine->name }}</td>
                                                <td>
                                                    @if ($medicine->dose)
                                                        {{ $medicine->dose }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $medicine->stock }}</td>
                                                <td>{{ $medicine->unit }}</td>
                                                <td class="text-right">{{ change_decimal_format_to_currency($medicine->price) }}</td>
                                                
                                                @can('admin')
                                                    <td class="text-nowrap col-1">
                                                        <form action="/medicines/{{ $medicine->id }}" method="POST" class="d-inline">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm delete-button">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                        <a href="/medicines/{{ $medicine->id }}/edit" class="btn btn-warning btn-sm">
                                                            <i class="fa fa-pen"></i>
                                                        </a>
                                                    </td>
                                                    
                                                @endcan
                                            </tr>
                                        @endforeach
                                    @empty
                                        <tr>
                                            <td colspan="16" class="text-center">
                                                <span>Tidak ada obat.</span>
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
            <!-- /.row -->


        </div><!-- /.container-fluid -->


    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {

        let filterForm = $('#filter-form');

        // Filter medicine type
        $('#medicine_type').on('change', function() {
            filterForm.submit();
        });

        // Sweet alert delete
        $('.delete-button').click(function(event) {
            let form = $(this).closest("form");
            event.preventDefault();
            Swal.fire({
                title: "Anda yakin ingin menghapus obat ini?",
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