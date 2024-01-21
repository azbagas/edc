@extends('layouts.app')

@section('title', 'Data Pasien')

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Pasien</h1>
                </div><!-- /.col -->

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col">
                    
                    <a href="/patients/create" class="btn btn-primary">
                        <i class="fa fa-plus mr-2"></i>Buat pasien baru
                    </a>
                    
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="/patients" method="GET" id="filter-form" spellcheck="false" autocomplete="off">
                            <div class="card-header">
                                <h3 class="card-title">Filter</h3>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="per_page" value="{{ request('per_page') }}">

                                <div class="row">
                                    <div class="col-md-7 col-xl-2">
                                        <div class="form-group">
                                            <label for="id">No Pasien</label>
                                            <input type="text" id="id" name="id" class="form-control" placeholder="Cari no pasien..." value="{{ request('id') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-7 col-xl-5">
                                        <div class="form-group">
                                            <label for="name">Nama Pasien</label>
                                            <input type="text" id="name" name="name" class="form-control" placeholder="Cari nama pasien..." value="{{ request('name') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-7 col-xl-7">
                                        <div class="form-group">
                                            <label for="address">Alamat Pasien</label>
                                            <input type="text" id="address" name="address" class="form-control" placeholder="Cari alamat pasien..." value="{{ request('address') }}">
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-7 col-xl-7">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="/patients" class="btn btn-default btn-sm">
                                                Reset pencarian
                                            </a>
                                            <button type="submit" class="btn btn-info">
                                                <i class="fa fa-search mr-2"></i>Cari Pasien
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th class="col-2">No Pasien</th>
                                        <th>Nama Pasien</th>
                                        <th>Alamat</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Jenis Kelamin</th>
                                        <th>No Telepon</th>
                                        <th class="text-nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($patients as $patient)
                                        <tr>
                                            <td>{{ $patient->id }}</td>
                                            <td>{{ $patient->name }}</td>
                                            <td>{{ $patient->address }}</td>
                                            <td>{{ $patient->date_of_birth }}</td>
                                            <td>{{ $patient->gender }}</td>
                                            <td>{{ $patient->phone }}</td>
                                            <td class="text-nowrap">
                                                <a href="/patients/{{ $patient->id }}/edit" class="btn btn-warning btn-sm">
                                                    <i class="fa fa-pen"></i>
                                                </a>
                                                <a href="/patients/{{ $patient->id }}" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="appointments/create?patient={{ $patient->id }}" class="btn btn-success btn-sm">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <span>Tidak ada pasien dengan data tersebut.</span>
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                            <input type="hidden" name="hidden_page" id="hidden_page" value="1">
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col">
                    {{ $patients->onEachSide(1)->links() }}

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

    });
</script>
@endpush