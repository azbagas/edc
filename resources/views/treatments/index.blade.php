@extends('layouts.app')

@section('title', 'Tindakan')

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tindakan</h1>
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
                        <a href="/treatments/create" class="btn btn-primary">
                            <i class="fa fa-plus mr-2"></i>Tambah tindakan
                        </a>
                    </div>
                </div>
                
            @endcan


            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="/treatments" method="GET" id="filter-form" spellcheck="false" autocomplete="off">
                            <div class="card-header">
                                <h3 class="card-title">Filter</h3>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-7 col-xl-5">
                                        <div class="form-group">
                                            <label for="name">Cari Tindakan</label>
                            
                                            <div class="input-group">
                                                <input type="text" id="name" name="name" class="form-control" placeholder="Cari tindakan..." value="{{ request('name') }}">
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
                                            <label for="treatment_type">Jenis Tindakan</label>
                                            <select class="form-control" name="treatment_type" id="treatment_type">
                                                <option value="">Semua jenis tindakan</option>
                                                @foreach ($all_treatment_types as $treatment_type)
                                                    <option value="{{ $treatment_type->id }}" {{ request('treatment_type') == $treatment_type->id ? 'selected' : '' }}>
                                                        {{ $treatment_type->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-7 col-xl-5">
                                        <a href="/treatments" class="btn btn-default btn-sm">
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
                                        <th>Jenis Tindakan</th>
                                        <th>Nama Tindakan</th>
                                        @can('admin')
                                            <th>Aksi</th>
                                            
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($treatment_types as $treatment_type)
                                        @foreach ($treatment_type->treatments as $treatment)
                                            <tr>
                                                
                                                @if ($loop->first)
                                                    <td class="col-1" rowspan="{{ $treatment_type->treatments->count() }}">{{  $loop->parent->iteration }}</td>
                                                    <td rowspan="{{ $treatment_type->treatments->count() }}">{{ $treatment_type->name }}</td>
                                                @endif

                                                <td style="padding: 12px;">{{ $treatment->name }}</td>
                                                
                                                @can('admin')
                                                    <td class="text-nowrap col-1">
                                                        <form action="/treatments/{{ $treatment->id }}" method="POST" class="d-inline">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm delete-button">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                        <a href="/treatments/{{ $treatment->id }}/edit" class="btn btn-warning btn-sm">
                                                            <i class="fa fa-pen"></i>
                                                        </a>
                                                    </td>
                                                    
                                                @endcan
                                            </tr>
                                        @endforeach
                                    @empty
                                        <tr>
                                            <td colspan="16" class="text-center">
                                                <span>Tidak ada treatment.</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                    {{-- @forelse ($treatments as $treatment)
                                        <tr>
                                            <td class="col-1">{{ $treatments->firstItem() + $loop->index }}</td>
                                            <td>{{ $treatment->name }}</td>
                                            <td>{{ $treatment->treatment_type->name }}</td>
                                            <td class="text-nowrap col-1">
                                                <form action="/treatments/{{ $treatment->id }}" method="POST" class="d-inline">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm delete-button">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                                <a href="/treatments/{{ $treatment->id }}/edit" class="btn btn-warning btn-sm">
                                                    <i class="fa fa-pen"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <span>Tidak ada tindakan.</span>
                                            </td>
                                        </tr>
                                    @endforelse --}}

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

        // Filter treatment type
        $('#treatment_type').on('change', function() {
            filterForm.submit();
        });

        // Sweet alert delete
        $('.delete-button').click(function(event) {
            let form = $(this).closest("form");
            event.preventDefault();
            Swal.fire({
                title: "Anda yakin ingin menghapus tindakan ini?",
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