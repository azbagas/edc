@extends('layouts.app')

@section('title', 'Edit Tindakan')

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Tindakan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/treatments">Tindakan</a></li>
                        <li class="breadcrumb-item active">Edit Tindakan</li>
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
                        <form action="/treatments/{{ $treatment->id }}" method="POST" autocomplete="off" spellcheck="false">
                            @csrf
                            @method('put')

                            <div class="card-body">
                                
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="treatment_type_id">Jenis Tindakan<span class="text-danger">*</span></label>
                                            <select id="treatment_type_id" name="treatment_type_id" class="form-control select2 @error('treatment_type_id') is-invalid @enderror" style="width: 100%;" data-placeholder="-- Pilih jenis tindakan --" required>
                                                <option></option>
                                                @foreach ($treatment_types as $treatment_type)
                                                    <option value="{{ $treatment_type->id }}" @selected(old('treatment_type_id', $treatment->treatment_type_id) == $treatment_type->id)>{{ $treatment_type->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('treatment_type_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="name">Nama Tindakan<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" required
                                                id="name" name="name" placeholder="Masukkan nama tindakan..." value="{{ old('name', $treatment->name) }}">
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
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

        $(`#treatment_type_id`).select2();
    });
</script>
@endpush