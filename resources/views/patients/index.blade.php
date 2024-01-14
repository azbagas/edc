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
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <form action="/patients" method="GET" autocomplete="off" spellcheck="false">
                                            <td>
                                                <input type="text" class="form-control" id="searchId" name="searchId"
                                                    value="{{ request('searchId') }}" placeholder="Cari no pasien...">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" id="searchName" name="searchName"
                                                    value="{{ request('searchName') }}" placeholder="Cari nama pasien...">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" id="searchAddress"
                                                    name="searchAddress" value="{{ request('searchAddress') }}"
                                                    placeholder="Cari alamat pasien...">
                                            </td>
                                            <td colspan="4">
                                                <div class="row">
                                                    <div class="col-6 col-xl-7">
                                                        <button type="submit" class="btn btn-block btn-info">
                                                            <i class="fa fa-search mr-2"></i>Cari Pasien
                                                        </button>
                                                    </div>
                                                    <div class="col-6 col-xl-5">
                                                        <a href="/patients" class="btn btn-block btn-default">
                                                            Reset Pencarian
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </form>
                                    </tr>
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
                                                <a href="appointments/create/{{ $patient->id }}" class="btn btn-success btn-sm">
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
        </div><!-- /.container-fluid -->

    </div>
@endsection