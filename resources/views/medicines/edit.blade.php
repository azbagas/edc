@extends('layouts.app')

@section('title', 'Edit Obat')

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Obat</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/medicines">Obat</a></li>
                        <li class="breadcrumb-item active">Edit Obat</li>
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
                    <a href="javascript:history.back()" class="btn btn-info btn-sm">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            
            <div class="row">
                <div class="col">

                    <div class="card">
                        <form action="/medicines/{{ $medicine->id }}" method="POST" autocomplete="off" spellcheck="false">
                            @csrf
                            @method('put')
                            <div class="card-body">
                                
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="medicine_type_id">Jenis Obat<span class="text-danger">*</span></label>
                                            <select id="medicine_type_id" name="medicine_type_id" class="form-control select2 @error('medicine_type_id') is-invalid @enderror" style="width: 100%;" data-placeholder="-- Pilih jenis obat --" required>
                                                <option></option>
                                                @foreach ($medicine_types as $medicine_type)
                                                    <option value="{{ $medicine_type->id }}" @selected(old('medicine_type_id', $medicine->medicine_type_id) == $medicine_type->id)>{{ $medicine_type->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('medicine_type_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="name">Nama Obat<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" required
                                                id="name" name="name" placeholder="Masukkan nama obat..." value="{{ old('name', $medicine->name) }}">
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="dose">Dosis</label>
                                            <input type="text" class="form-control @error('dose') is-invalid @enderror"
                                                id="dose" name="dose" placeholder="Masukkan Dosis..." value="{{ old('dose', $medicine->dose) }}">
                                            @error('dose')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="stock">Stok<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('stock') is-invalid @enderror" required
                                                id="stock" name="stock" placeholder="Masukkan stok..." value="{{ old('stock', $medicine->stock) }}">
                                            @error('stock')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="unit">Satuan<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('unit') is-invalid @enderror" required
                                                id="unit" name="unit" placeholder="Masukkan Satuan..." value="{{ old('unit', $medicine->unit) }}">
                                            @error('unit')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="price">Harga<span class="text-danger">*</span></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input id="price" type="text" name="price" class="form-control rupiah @error('price') is-invalid @enderror" placeholder="0,00" value="{{ old('price', $medicine->price) ? change_decimal_format_to_currency(old('price', $medicine->price)) : '' }}" required>
                                                @error('price')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                </div>



                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Simpan</button>
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

        $(`#medicine_type_id`).select2();

        $(`.rupiah`).inputmask("currency", {
            radixPoint: ',',
            allowMinus: false,
        });
    });
</script>
@endpush