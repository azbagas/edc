@extends('layouts.app')

@section('title', 'Edit Diagnosis')

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Diagnosis</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/diagnoses">Diagnosis</a></li>
                        <li class="breadcrumb-item active">Edit Diagnosis</li>
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
                        <form action="/diagnoses/{{ $diagnosis->id }}" method="POST" autocomplete="off" spellcheck="false">
                            @csrf
                            @method('put')
                            <div class="card-body">
                                
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="disease_id">Penyakit<span class="text-danger">*</span></label>
                                            <select id="disease_id" name="disease_id" class="form-control select2 @error('disease_id') is-invalid @enderror" style="width: 100%;" data-placeholder="-- Pilih penyakit --" required>
                                                <option></option>
                                                @foreach ($diseases as $disease)
                                                    <option value="{{ $disease->id }}" @selected(old('disease_id', $diagnosis->disease_id) == $disease->id)>{{ $disease->disease_code }} - {{ $disease->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('disease_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="diagnosis_code">Kode Diagnosis<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('diagnosis_code') is-invalid @enderror" required
                                                id="diagnosis_code" name="diagnosis_code" placeholder="Masukkan kode diagnosis..." value="{{ old('diagnosis_code', $diagnosis->diagnosis_code) }}">
                                            @error('diagnosis_code')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="name">Nama Diagnosis<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" required
                                                id="name" name="name" placeholder="Masukkan nama diagnosis..." value="{{ old('name', $diagnosis->name) }}">
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>
                                </div>



                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Edit</button>
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

        $(`#disease_id`).select2();
    });
</script>
@endpush