<div class="modal fade" id="modal-edit-{{ $patient->id }}" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Pasien</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="" autocomplete="off" spellcheck="false">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="id-edit-{{ $patient->id }}">No Pasien</label>
                        <input type="text" class="form-control" id="id-edit-{{ $patient->id }}" name="id"
                            value="{{ $patient->id }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="name-edit-{{ $patient->id }}">Nama Pasien</label>
                        <input type="text" class="form-control" id="name-edit-{{ $patient->id }}" name="name"
                            value="{{ $patient->name }}" placeholder="Masukkan nama pasien...">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_of_birth-edit-{{ $patient->id }}">Tanggal
                                    Lahir</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" id="date_of_birth-edit-{{ $patient->id }}"
                                        name="date_of_birth" class="form-control" placeholder="dd-mm-yyyy"
                                        value="{{ $patient->date_of_birth }}">
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jenis Kelamin</label>

                                <div class="row">
                                    <div class="col-sm-4 col-md-5">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                id="laki-laki-edit-{{ $patient->id }}" name="gender"
                                                value="Laki-laki"
                                                {{ $patient->gender == 'Laki-laki' ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="laki-laki-edit-{{ $patient->id }}">Laki-laki</label>
                                        </div>

                                    </div>
                                    <div class="col-sm-8 col-md-7">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                id="perempuan-edit-{{ $patient->id }}" name="gender"
                                                value="Perempuan"
                                                {{ $patient->gender == 'Perempuan' ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="perempuan-edit-{{ $patient->id }}">Perempuan</label>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone-edit-{{ $patient->id }}">No Telepon</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                            <input type="text" id="phone-edit-{{ $patient->id }}" name="phone"
                                class="form-control" placeholder="Masukkan nomor telepon..."
                                value="{{ $patient->phone }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address-edit-{{ $patient->id }}">Alamat</label>
                        <textarea id="address-edit-{{ $patient->id }}" name="address" class="form-control" rows="3"
                            placeholder="Masukkan alamat...">{{ $patient->address }}</textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
