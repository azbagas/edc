@extends('layouts.app')

@section('title', 'Tambah Pengeluaran')

@section('styles')
    <style>
        #date:hover {
            cursor: pointer;
        }

        #date {
            caret-color: transparent;
        }
    </style>
@endsection

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tambah Pengeluaran</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/expenses">Pengeluaran</a></li>
                        <li class="breadcrumb-item active">Tambah pengeluaran</li>
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

                    <div class="card">
                        <form action="/expenses" method="POST" autocomplete="off" spellcheck="false">
                            @csrf
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="date">Tanggal<span class="text-danger">*</span></label>
                            
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <input type="hidden" name="date">
                                                <input type="text" class="form-control float-right" id="date">
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="name">Nama Pengeluaran<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" required autofocus
                                                id="name" name="name" placeholder="Masukkan nama pengeluaran..." value="{{ old('name') }}">
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="amount">Jumlah<span class="text-danger">*</span></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input id="amount" type="text" name="amount" class="form-control rupiah @error('amount') is-invalid @enderror" placeholder="0,00" value="{{ old('amount') ? change_decimal_format_to_currency(old('amount')) : '' }}" required>
                                                @error('amount')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Tambah</button>
                            </div>
                        </form>
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
            $(`.rupiah`).inputmask("currency", {
                radixPoint: ',',
                allowMinus: false,
            });

            // Initial date
            let start = moment();
            @if(old('date'))
                let startStr = "{{ old('date') }}";
                start = moment(startStr, "DD-MM-YYYY");
            @endif
            $('input[name="date"]').val(start.format('DD-MM-YYYY'));

            $('#date').daterangepicker({
                singleDatePicker: true,
                autoApply: true,
                showDropdowns: true,
                startDate: start,
                locale: {
                    format: 'D MMMM YYYY'
                },
                minDate: moment("01-01-2020", "DD-MM-YYYY"),
                maxDate: moment().add(5, 'years').endOf('year')
            }, function(start, end, label) {
                $('input[name="date"]').val(start.format('DD-MM-YYYY'));
            });
        });
    </script>
@endpush
