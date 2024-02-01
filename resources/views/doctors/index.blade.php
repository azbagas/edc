@extends('layouts.app')

@section('title', 'Dokter')

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dokter</h1>
                </div><!-- /.col -->

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            @can('owner')
                <div class="row mb-3">
                    <div class="col">
                        <a href="/doctors/create" class="btn btn-primary">
                            <i class="fa fa-plus mr-2"></i>Tambah Dokter
                        </a>
                    </div>
                </div>
                
            @endcan


            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="/doctors" method="GET" id="filter-form" spellcheck="false" autocomplete="off">
                            <div class="card-header">
                                <h3 class="card-title">Filter</h3>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-7 col-xl-5">
                                        <div class="form-group">
                                            <label for="name">Cari Dokter</label>
                            
                                            <div class="input-group">
                                                <input type="text" id="name" name="name" class="form-control" placeholder="Cari dokter..." value="{{ request('name') }}">
                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-info">
                                                      <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="per_page" value="{{ request('per_page') }}">

                                <div class="row">
                                    <div class="col-md-7 col-xl-5">
                                        <a href="/doctors" class="btn btn-default btn-sm">
                                            Reset pencarian
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Dokter</th>
                                        @can('owner')
                                            <th>Username</th>
                                            <th>Email</th>
                                        @endcan
                                        <th>SIP</th>
                                        <th>Alamat</th>
                                        <th>No Telepon</th>
                                        @can('owner')
                                            <th>% Gaji</th>
                                        @endcan
                                        <th>Status</th>
                                        @can('owner')
                                            <th>Aksi</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($doctors as $doctor)
                                        <tr>
                                            <td>{{ $doctors->firstItem() + $loop->index }}</td>
                                            <td>{{ $doctor->user->name }}</td>
                                            @can('owner')
                                                <td>{{ $doctor->user->username }}</td>
                                                <td>{{ $doctor->user->email }}</td>
                                            @endcan
                                            <td>{{ $doctor->sip }}</td>
                                            <td>{{ $doctor->user->address }}</td>
                                            <td>{{ $doctor->user->phone }}</td>
                                            @can('owner')
                                                <td>{{ change_decimal_format_to_percentage($doctor->doctor_percentage) }}</td>
                                            @endcan
                                            <td>
                                                @if ($doctor->user->is_active)
                                                    <span class="badge badge-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-danger">Nonaktif</span>
                                                @endif
                                            </td>
                                            @can('owner')
                                                <td class="text-nowrap col-1">
                                                    <form action="/doctors/{{ $doctor->id }}" method="POST" class="d-inline">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm delete-button">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    <a href="/doctors/{{ $doctor->id }}/edit" class="btn btn-warning btn-sm">
                                                        <i class="fa fa-pen"></i>
                                                    </a>
                                                </td>
                                            @endcan
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <span>Tidak ada Dokter.</span>
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
            
            <div class="row">
                <div class="col">
                    {{ $doctors->onEachSide(1)->links() }}
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="mb-3">
                        <span class="text-muted">
                            Tampilkan baris per halaman
                        </span>
                        
                        <select id="per_page" class="form-control-sm">
                            @foreach ($per_page_options as $per_page_option)
                                <option value="{{ $per_page_option }}" @selected(request('per_page') == $per_page_option )>{{ $per_page_option }}</option>
                            @endforeach
                        </select>
                        
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->


    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {

        let filterForm = $('#filter-form');

        // per page
        $('#per_page').on('change', function() {
            $('input[name="per_page"]').val($(this).val());
            filterForm.submit();
        });

        // Sweet alert delete
        $('.delete-button').click(function(event) {
            let form = $(this).closest("form");
            event.preventDefault();
            Swal.fire({
                title: "Anda yakin ingin menghapus Dokter ini?",
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