@extends('layouts.app')

@section('title', 'Metode Pembayaran')

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Metode Pembayaran</h1>
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
                    <a href="/payment-types/create" class="btn btn-primary">
                        <i class="fa fa-plus mr-2"></i>Tambah Metode Pembayaran
                    </a>
                </div>
            </div>


            <div class="row">
                <div class="col">
                    <div class="card">
                        <form action="/payment-types" method="GET" id="filter-form" spellcheck="false" autocomplete="off">
                            <div class="card-header">
                                <h3 class="card-title">Filter</h3>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-7 col-xl-5">
                                        <div class="form-group">
                                            <label for="name">Cari Metode Pembayaran</label>
                            
                                            <div class="input-group">
                                                <input type="text" id="name" name="name" class="form-control" placeholder="Cari Metode Pembayaran..." value="{{ request('name') }}">
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
                                        <a href="/payment-types" class="btn btn-default btn-sm">
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
                                        <th>Metode Pembayaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($payment_types as $payment_type)
                                        <tr>
                                            <td class="col-1">{{ $payment_types->firstItem() + $loop->index }}</td>
                                            <td>{{ $payment_type->name }}</td>
                                            <td class="text-nowrap col-1">
                                                <form action="/payment-types/{{ $payment_type->id }}" method="POST" class="d-inline">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm delete-button">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                                <a href="/payment-types/{{ $payment_type->id }}/edit" class="btn btn-warning btn-sm">
                                                    <i class="fa fa-pen"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <span>Tidak ada Metode Pembayaran.</span>
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
                    {{ $payment_types->onEachSide(1)->links() }}
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
                title: "Anda yakin ingin menghapus Metode Pembayaran ini?",
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