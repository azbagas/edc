@extends('layouts.app')

@section('title', 'Diagnosis')

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Diagnosis</h1>
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
                    <a href="/diagnoses/create" class="btn btn-primary">
                        <i class="fa fa-plus mr-2"></i>Tambah diagnosis
                    </a>
                </div>
            </div>


            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="/diagnoses" method="GET" id="filter-form" spellcheck="false" autocomplete="off">
                            <div class="card-header">
                                <h3 class="card-title">Filter</h3>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-7 col-xl-5">
                                        <div class="form-group">
                                            <label for="name">Cari Diagnosis</label>
                            
                                            <div class="input-group">
                                                <input type="text" id="name" name="name" class="form-control" placeholder="Cari diagnosis atau kode diagnosis..." value="{{ request('name') }}">
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
                                            <label for="disease">Penyakit</label>
                                            <select class="form-control" name="disease_id" id="disease">
                                                <option value="">Semua penyakit</option>
                                                @foreach ($all_diseases as $disease)
                                                    <option value="{{ $disease->id }}" {{ request('disease_id') == $disease->id ? 'selected' : '' }}>
                                                       {{ $disease->disease_code }} - {{ $disease->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-7 col-xl-5">
                                        <a href="/diagnoses" class="btn btn-default btn-sm">
                                            Reset filter
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
                                        <th>Kode Penyakit</th>
                                        <th>Penyakit</th>
                                        <th>Kode Diagnosis</th>
                                        <th>Diagnosis</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($diseases as $disease)
                                        @foreach ($disease->diagnoses as $diagnosis)
                                            <tr>
                                                @if ($loop->first)
                                                    <td rowspan="{{ $disease->diagnoses->count() }}">{{ $disease->disease_code }}</td>
                                                    <td rowspan="{{ $disease->diagnoses->count() }}">{{ $disease->name }}</td>
                                                @endif

                                                <td style="padding: 12px;">{{ $diagnosis->diagnosis_code }}</td>
                                                <td>{{ $diagnosis->name }}</td>
                                                <td class="text-nowrap col-1">
                                                    <form action="/diagnoses/{{ $diagnosis->id }}" method="POST" class="d-inline">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm delete-button">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    <a href="/diagnoses/{{ $diagnosis->id }}/edit" class="btn btn-warning btn-sm">
                                                        <i class="fa fa-pen"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @empty
                                        <tr>
                                            <td colspan="16" class="text-center">
                                                <span>Tidak ada diagnosis.</span>
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

        // Filter disease
        $('#disease').on('change', function() {
            filterForm.submit();
        });

        // Sweet alert delete
        $('.delete-button').click(function(event) {
            let form = $(this).closest("form");
            event.preventDefault();
            Swal.fire({
                title: "Anda yakin ingin menghapus diagnosis ini?",
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