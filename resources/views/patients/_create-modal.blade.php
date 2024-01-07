<div class="modal fade" id="modal-create" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah pasien baru</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="/patients" method="POST" autocomplete="off" spellcheck="false">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="id">No Pasien</label>
                        {{-- Cuma tampilan aja, gak dipassing --}}
                        <input type="text" class="form-control" id="id" name="id" value="{{ $newPatientId }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="name">Nama Pasien</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                            placeholder="Masukkan nama pasien...">
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_of_birth">Tanggal Lahir</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" id="date_of_birth" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror"
                                        placeholder="dd-mm-yyyy">
                                    @error('date_of_birth')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
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
                                            <input class="form-check-input" type="radio" id="laki-laki" name="gender"
                                                value="Laki-laki" required>
                                            <label class="form-check-label" for="laki-laki">Laki-laki</label>
                                        </div>

                                    </div>
                                    <div class="col-sm-8 col-md-7">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="perempuan" name="gender"
                                                value="Perempuan" required>
                                            <label class="form-check-label" for="perempuan">Perempuan</label>
                                        </div>
                                    </div>
                                </div>
                                @error('gender')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone">No Telepon</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                            <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                placeholder="Masukkan nomor telepon...">
                            @error('phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="3" placeholder="Masukkan alamat..."></textarea>
                        @error('address')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah pasien</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
