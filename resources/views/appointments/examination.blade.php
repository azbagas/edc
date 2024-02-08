@extends('layouts.app')

@section('title', 'Pemeriksaan')

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pemeriksaan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/appointments">Pertemuan</a></li>
                        <li class="breadcrumb-item active">Pemeriksaan</li>
                    </ol>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')
    <div class="content">
        <form action="/appointments/{{ $appointment->id }}/examination" method="POST" autocomplete="off" spellcheck="false">
            @method('put')
            @csrf

            <div class="container-fluid">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
    
                {{-- Informasi pemeriksaan --}}
                <div class="row">
                    <div class="col">
    
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Informasi Pemeriksaan
                                </h3>
                            </div>
                            <div class="card-body">
                                <dl class="row mb-0">
                                    <dt class="col-sm-3 col-xl-2">Tanggal</dt>
                                    <dd class="col-sm-9 col-xl-10"><span class="d-none d-sm-inline">:</span> {{ \Carbon\Carbon::parse($appointment->date_time)->translatedFormat('j F Y') }}</dd>
    
                                    <dt class="col-sm-3 col-xl-2">Dokter</dt>
                                    <dd class="col-sm-9 col-xl-10"><span class="d-none d-sm-inline">:</span> {{ $appointment->doctor->user->name }}</dd>
    
                                    <dt class="col-sm-3 col-xl-2">Asisten</dt>
                                    <dd class="col-sm-9 col-xl-10"><span class="d-none d-sm-inline">:</span> {{ $appointment->assistant->name }}</dd>
                                    
                                    <dt class="col-sm-3 col-xl-2">Admin</dt>
                                    <dd class="col-sm-9 col-xl-10"><span class="d-none d-sm-inline">:</span> {{ $appointment->admin->user->name }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
    
                {{-- Data diri pasien --}}
                <div class="row">
                    <div class="col">
    
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Data Diri Pasien
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <dl class="row mb-0">
                                            <dt class="col-sm-4 col-xl-3">Nomor Pasien</dt>
                                            <dd class="col-sm-8 col-xl-9"><span class="d-none d-sm-inline">:</span> {{ $appointment->patient->id }}</dd>
            
                                            <dt class="col-sm-4 col-xl-3">Nama</dt>
                                            <dd class="col-sm-8 col-xl-9"><span class="d-none d-sm-inline">:</span> {{ $appointment->patient->name }}</dd>
            
                                            <dt class="col-sm-4 col-xl-3">Tanggal Lahir</dt>
                                            <dd class="col-sm-8 col-xl-9"><span class="d-none d-sm-inline">:</span> {{ $appointment->patient->date_of_birth }} ({{ \Carbon\Carbon::parse($appointment->patient->date_of_birth)->diffInYears(\Carbon\Carbon::parse($appointment->date_time)) }} tahun)</dd>
            
                                            <dt class="col-sm-4 col-xl-3">Jenis Kelamin</dt>
                                            <dd class="col-sm-8 col-xl-9"><span class="d-none d-sm-inline">:</span> {{ $appointment->patient->gender }}</dd>
                                            
                                            <dt class="col-sm-4 col-xl-3">Nomor Telepon</dt>
                                            <dd class="col-sm-8 col-xl-9"><span class="d-none d-sm-inline">:</span> {{ $appointment->patient->phone }}</dd>
                                            
                                            <dt class="col-sm-4 col-xl-3">Alamat</dt>
                                            <dd class="col-sm-8 col-xl-9"><span class="d-none d-sm-inline">:</span> {{ $appointment->patient->address }}</dd>
                                        </dl>
                                        
                                    </div>
                                    <div class="col-sm-6">
                                        <h5>Kondisi Pasien</h5>
                                        <p>{{ generate_patient_conditions_string($appointment->patient_condition) }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        @include('appointments._show-appointment-histories-modal')
                                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-lg">
                                            Lihat riwayat pertemuan
                                        </button>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
    
                {{-- Keluhan --}}
                <div class="row">
                    <div class="col">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Keluhan
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <p>{{ $appointment->complaint }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
    
                {{-- Diagnosis --}}
                <div class="row">
                    <div class="col">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Diagnosis
                                </h3>
                            </div>
                            <div class="card-body">
    
                                {{-- Diagnosis container --}}
                                <div id="diagnosis-container">

                                </div>
                                {{-- End of diagnosis container --}}
    
                                <div class="row">
                                    <div class="col">
                                        <button type="button" class="btn btn-success btn-sm" id="add-diagnosis">
                                            <i class="fa fa-plus mr-2"></i>Tambah Diagnosis
                                        </button>
                                    </div>
                                </div>
                    
    
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!-- /.row -->
    
    
                {{-- Tindakan --}}
                <div class="row">
                    <div class="col">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Tindakan
                                </h3>
                            </div>
                            <div class="card-body">
    
                                {{-- Treatment container --}}
                                <div id="treatment-container">
    
                                </div>
                                {{-- End of treatment container --}}
    
                                <div class="row">
                                    <div class="col">
                                        <button type="button" class="btn btn-success btn-sm" id="add-treatment">
                                            <i class="fa fa-plus mr-2"></i>Tambah Tindakan
                                        </button>
                                    </div>
                                </div>
                    
    
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!-- /.row -->
    
    
                {{-- Tindakan --}}
                <div class="row">
                    <div class="col">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Resep Obat
                                </h3>
                            </div>
                            <div class="card-body">
    
                                {{-- medicine container --}}
                                <div id="medicine-container">
    
                                </div>
                                {{-- End of medicine container --}}
    
                                <div class="row">
                                    <div class="col">
                                        <button type="button" class="btn btn-success btn-sm" id="add-medicine">
                                            <i class="fa fa-plus mr-2"></i>Tambah Obat
                                        </button>
                                    </div>
                                </div>
                    
    
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!-- /.row -->
    
                <div class="row">
                    <div class="col">
                        <div class="d-flex justify-content-end mb-3">
                            <button type="submit" class="btn btn-primary">Lanjut ke pembayaran <i class="fa fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>
    
    
            </div><!-- /.container-fluid -->
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // ------------------ Diagnosis ------------------
            // Function add diagnosis section
            function addDiagnosis (i, diagnosis = []) {
                // isDiagnosis untuk mengecek apakah sudah ada diagnosis sebelumnya
                let isDiagnosis = diagnosis.length != 0;

                $('#diagnosis-container').append(`
                                <div class="diagnosis-section">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="disease-${i}">Penyakit<span class="text-danger">*</span></label>
                                                <select id="disease-${i}" name="disease[]" class="form-control select2" style="width: 100%;" data-placeholder="-- Pilih penyakit --" disabled required>
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="diagnosis-${i}">Diagnosis<span class="text-danger">*</span></label>
                                                <select id="diagnosis-${i}" name="diagnosis[]" class="form-control select2" style="width: 100%;" data-placeholder="-- Pilih diagnosis --" disabled required>
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 order-first order-md-last">
                                            <div class="d-flex justify-content-end">
                                                <button type="button" class="btn btn-danger btn-sm diagnosis-delete-button">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label for="diagnosis-note-${i}">Keterangan</label>
                                                <textarea id="diagnosis-note-${i}" name="diagnosis_note[]" class="form-control" rows="3"
                                                    placeholder="Masukkan keterangan..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                `);
                
                // Buat select menjadi select2
                $(`#disease-${i}`).select2();
                $(`#diagnosis-${i}`).select2();

                if (isDiagnosis) {
                    $.each(diseases, function (key, value) {
                        if (value.id == diagnosis.disease_id) {
                            $(`#disease-${i}`).append('<option value="' + value.id + '" selected>' + value.disease_code + ' - ' + value.name + '</option>');
                        } else {
                            $(`#disease-${i}`).append('<option value="' + value.id + '">' + value.disease_code + ' - ' + value.name + '</option>');
                        }
                    });
                    $(`#disease-${i}`).prop('disabled', false);

                    $.ajax({
                        url: '{{ route('getDiagnoses') }}?disease=' + diagnosis.disease_id,
                        type: 'get',
                        success: function (res) {
                            $.each(res, function (key, value) {
                                if (value.id == diagnosis.id) {
                                    $(`#diagnosis-${i}`).append('<option value="' + value.id + '" selected>' + value.diagnosis_code + ' - ' + value.name + '</option>');
                                } else {
                                    $(`#diagnosis-${i}`).append('<option value="' + value.id + '">' + value.diagnosis_code + ' - ' + value.name + '</option>');
                                }
                            });
                            $(`#diagnosis-${i}`).prop('disabled', false);
                        }
                    });

                    $(`#diagnosis-note-${i}`).val(diagnosis.pivot.note);
                    
                } else {
                    $.each(diseases, function (key, value) {
                        $(`#disease-${i}`).append('<option value="' + value.id + '">' + value.disease_code + ' - ' + value.name + '</option>');
                    });
                    $(`#disease-${i}`).prop('disabled', false);

                }
                
                // Buat agar bisa delete
                $('.diagnosis-delete-button').on('click', function () {
                    $(this).parents('.diagnosis-section').remove();
                });

                $(`#disease-${i}`).on('change', function () {
                    $(`#diagnosis-${i}`).prop('disabled', true);

                    let diseaseId = this.value;
                    $(`#diagnosis-${i}`).html('<option></option>');

                    $.ajax({
                        url: '{{ route('getDiagnoses') }}?disease=' + diseaseId,
                        type: 'get',
                        success: function (res) {
                            $.each(res, function (key, value) {
                                $(`#diagnosis-${i}`).append('<option value="' + value.id + '">' + value.diagnosis_code + ' - ' + value.name + '</option>');
                            });
                            $(`#diagnosis-${i}`).prop('disabled', false);
                        }
                    });
                });
            }

            let diagnosisCounter = 0;

            let diseases = null;
            $.ajax({
                url: '{{ route('getDiseases') }}',
                type: 'get',
                success: function (res) {
                    diseases = res;

                    // Inisialisasi diagnoses kalo ada
                    let diagnoses = @json($appointment->diagnoses ?? []);
                    if (diagnoses.length > 0) {
                        diagnosisCounter = diagnoses.length + 1;
                    
                        let i = 0;
                        diagnoses.forEach(diagnosis => {
                            addDiagnosis(i, diagnosis);
                            i++;
                        });
                    }

                }
            });

            $('#add-diagnosis').on('click', function () {
                addDiagnosis(diagnosisCounter);
                diagnosisCounter++;
            });





            // ------------------ Tindakan ------------------
            // Function add treatment section
            function addTreatment (i, treatment = []) {
                // isTreatment untuk mengecek apakah sudah ada treatment sebelumnya
                let isTreatment = treatment.length != 0;

                $('#treatment-container').append(`
                                <div class="treatment-section">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="treatment-type-${i}">Jenis Tindakan<span class="text-danger">*</span></label>
                                                <select id="treatment-type-${i}" name="treatment_type[]" class="form-control select2" style="width: 100%;" data-placeholder="-- Pilih jenis tindakan --" disabled required>
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="treatment-${i}">Tindakan<span class="text-danger">*</span></label>
                                                <select id="treatment-${i}" name="treatment[]" class="form-control select2" style="width: 100%;" data-placeholder="-- Pilih tindakan --" disabled required>
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 order-first order-md-last">
                                            <div class="d-flex justify-content-end">
                                                <button type="button" class="btn btn-danger btn-sm treatment-delete-button">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label for="treatment-note-${i}">Keterangan</label>
                                                <textarea id="treatment-note-${i}" name="treatment_note[]" class="form-control" rows="3"
                                                    placeholder="Masukkan keterangan..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="treatment-price-${i}">Harga<span class="text-danger">*</span></label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">Rp</span>
                                                    </div>
                                                    <input id="treatment-price-${i}" type="text" name="treatment_price[]" class="form-control rupiah" placeholder="0,00" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <hr>
                                </div>
                `);
                
                // Buat select menjadi select2
                $(`#treatment-type-${i}`).select2();
                $(`#treatment-${i}`).select2();
                $(`#treatment-price-${i}`).inputmask("currency", {
                    radixPoint: ',',
                    allowMinus: false,
                });

                if (isTreatment) {
                    $.each(treatmentTypes, function (key, value) {
                        if (value.id == treatment.treatment_type_id) {
                            $(`#treatment-type-${i}`).append('<option value="' + value.id + '" selected>' + value.name + '</option>');
                        } else {
                            $(`#treatment-type-${i}`).append('<option value="' + value.id + '">' + value.name + '</option>');
                        }
                    });
                    $(`#treatment-type-${i}`).prop('disabled', false);

                    $.ajax({
                        url: '{{ route('getTreatments') }}?treatment_type=' + treatment.treatment_type_id,
                        type: 'get',
                        success: function (res) {
                            $.each(res, function (key, value) {
                                if (value.id == treatment.id) {
                                    $(`#treatment-${i}`).append('<option value="' + value.id + '" selected>' + value.name + '</option>');
                                } else {
                                    $(`#treatment-${i}`).append('<option value="' + value.id + '">' + value.name + '</option>');
                                }
                            });
                            $(`#treatment-${i}`).prop('disabled', false);
                        }
                    });

                    $(`#treatment-note-${i}`).val(treatment.pivot.note);
                    $(`#treatment-price-${i}`).val(treatment.pivot.price/1);
                    
                } else {
                    $.each(treatmentTypes, function (key, value) {
                        $(`#treatment-type-${i}`).append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    $(`#treatment-type-${i}`).prop('disabled', false);

                }
                
                // Buat agar bisa delete
                $('.treatment-delete-button').on('click', function () {
                    $(this).parents('.treatment-section').remove();
                });

                $(`#treatment-type-${i}`).on('change', function () {
                    $(`#treatment-${i}`).prop('disabled', true);

                    let treatmentTypeId = this.value;
                    $(`#treatment-${i}`).html('<option></option>');

                    $.ajax({
                        url: '{{ route('getTreatments') }}?treatment_type=' + treatmentTypeId,
                        type: 'get',
                        success: function (res) {
                            $.each(res, function (key, value) {
                                $(`#treatment-${i}`).append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                            $(`#treatment-${i}`).prop('disabled', false);
                        }
                    });
                });
            }

            let treatmentCounter = 0;

            let treatmentTypes = null;
            $.ajax({
                url: '{{ route('getTreatmentTypes') }}',
                type: 'get',
                success: function (res) {
                    treatmentTypes = res;

                    // Inisialisasi treatments kalo ada
                    let treatments = @json($appointment->treatments ?? []);
                    if (treatments.length > 0) {
                        treatmentCounter = treatments.length + 1;
                    
                        let i = 0;
                        treatments.forEach(treatment => {
                            addTreatment(i, treatment);
                            i++;
                        });
                    }

                }
            });

            $('#add-treatment').on('click', function () {
                addTreatment(treatmentCounter);
                treatmentCounter++;
            });





            // ------------------ Obat ------------------
            function addMedicine (i, medicine = []) {
                let isMedicine = medicine.length != 0;

                $('#medicine-container').append(`
                                <div class="medicine-section">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="medicine-type-${i}">Jenis obat<span class="text-danger">*</span></label>
                                                <select id="medicine-type-${i}" name="medicine_type[]" class="form-control select2" style="width: 100%;" data-placeholder="-- Pilih Jenis obat --" disabled>
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="medicine-${i}">Obat<span class="text-danger">*</span></label>
                                                <select id="medicine-${i}" name="medicine[]" class="form-control select2" style="width: 100%;" data-placeholder="-- Pilih obat --" disabled required>
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label for="medicine-quantity-${i}">Jumlah<span class="text-danger">*</span></label>
                                                <input type="number" min="1" id="medicine-quantity-${i}" name="medicine_quantity[]" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" disabled required>
                                            </div>
                                        </div>
                                        <div class="col-md-5 order-first order-md-last">
                                            <div class="d-flex justify-content-end">
                                                <button type="button" class="btn btn-danger btn-sm medicine-delete-button">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <hr>
                                </div>
                `);

                $(`#medicine-type-${i}`).select2();
                $(`#medicine-${i}`).select2();

                if (isMedicine) {
                    $.each(medicineTypes, function (key, value) {
                        if (value.id == medicine.medicine_type_id) {
                            $(`#medicine-type-${i}`).append('<option value="' + value.id + '" selected>' + value.name + '</option>');
                        } else {
                            $(`#medicine-type-${i}`).append('<option value="' + value.id + '">' + value.name + '</option>');
                        }
                    });
                    $(`#medicine-type-${i}`).prop('disabled', false);

                    $.ajax({
                        url: '{{ route('getMedicines') }}?medicine_type=' + medicine.medicine_type_id,
                        type: 'get',
                        success: function (res) {
                            $.each(res, function (key, value) {
                                if (value.id == medicine.id) {
                                    if (value.dose != null) {
                                        $(`#medicine-${i}`).append(`<option value="${value.id}" selected>${value.name} | ${value.dose} | ${value.unit}</option>`);
                                    } else {
                                        $(`#medicine-${i}`).append(`<option value="${value.id}" selected>${value.name} | ${value.unit}</option>`);
                                    }
                                } else {
                                    if (value.dose != null) {
                                        $(`#medicine-${i}`).append(`<option value="${value.id}">${value.name} | ${value.dose} | ${value.unit}</option>`);
                                    } else {
                                        $(`#medicine-${i}`).append(`<option value="${value.id}">${value.name} | ${value.unit}</option>`);
                                    }
                                }
                            });
                            $(`#medicine-${i}`).prop('disabled', false);
                        }
                    });

                    $(`#medicine-quantity-${i}`).val(medicine.pivot.quantity);
                    $(`#medicine-quantity-${i}`).prop('disabled', false);
                
                } else {
                    $.each(medicineTypes, function (key, value) {
                        $(`#medicine-type-${i}`).append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    $(`#medicine-type-${i}`).prop('disabled', false);
                }

                $('.medicine-delete-button').on('click', function () {
                    $(this).parents('.medicine-section').remove();
                });

                $(`#medicine-type-${i}`).on('change', function () {
                    $(`#medicine-${i}`).prop('disabled', true);
                    $(`#medicine-quantity-${i}`).val('');
                    $(`#medicine-quantity-${i}`).prop('disabled', true);

                    let medicineTypeId = this.value;
                    $(`#medicine-${i}`).html('<option></option>');

                    $.ajax({
                        url: '{{ route('getMedicines') }}?medicine_type=' + medicineTypeId,
                        type: 'get',
                        success: function (res) {
                            $.each(res, function (key, value) {
                                if (value.dose != null) {
                                    $(`#medicine-${i}`).append(`<option value="${value.id}">${value.name} | ${value.dose} | ${value.unit}</option>`);
                                } else {
                                    $(`#medicine-${i}`).append(`<option value="${value.id}">${value.name} | ${value.unit}</option>`);
                                }
                            });
                            $(`#medicine-${i}`).prop('disabled', false);
                        }
                    });
                });

                $(`#medicine-${i}`).on('change', function () {
                    $(`#medicine-quantity-${i}`).val('1');
                    $(`#medicine-quantity-${i}`).prop('disabled', false);
                });
            }

            let medicineCounter = 0;

            let medicineTypes = null;
            $.ajax({
                url: '{{ route('getMedicineTypes') }}',
                type: 'get',
                success: function (res) {
                    medicineTypes = res;

                    let medicines = @json($appointment->medicines ?? []);
                    if (medicines.length > 0) {
                        medicineCounter = medicines.length + 1;
                    
                        let i = 0;
                        medicines.forEach(medicine => {
                            addMedicine(i, medicine);
                            i++;
                        });
                    }
                }
            });

            $('#add-medicine').on('click', function () {
                addMedicine(medicineCounter);
                medicineCounter++;
            });
            

        });
    </script>
@endpush