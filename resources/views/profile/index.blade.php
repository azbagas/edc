@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
    <style>
        #photo {
            display:none;
        }
    </style>
@endsection

@section('header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Profile</h1>
                </div><!-- /.col -->

            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <form action="/profile" method="POST" autocomplete="off" spellcheck="false" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="row">
                    <div class="col-md-3">
                        <div class="card pb-md-3">
                            <div class="card-body">
                                <div class="text-center">
                                    <img class="profile-user-img img-fluid img-circle" src="{{ asset('storage/' . Auth::user()->photo) }}" alt="User profile picture" style="width: 100px; height: 100px; object-fit: cover;">
                                    <input id="photo" name="photo" type="file" />
                                    <a href="#" id="upload_link" class="d-block mt-3">Ganti foto profil</a>​
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">


                        <div class="card">
                            <div class="card-body">

                                <h4>Informasi Profil</h4>
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input type="text" class="form-control" id="name"
                                        value="{{ Auth::user()->name }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <input type="text" class="form-control" id="role"
                                        value="{{ implode(', ',Auth::user()->roles->pluck('name')->toArray()) }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                                        id="username" name="username" placeholder="Masukkan username..."
                                        value="{{ old('username', Auth::user()->username) }}">
                                    @error('username')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" placeholder="Masukkan email..."
                                        value="{{ old('email', Auth::user()->email) }}">
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <br>
                                <h4>Update Password</h4>
                                <div class="form-group">
                                    <label for="current_password">Password saat ini</label>
                                    <input type="password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        id="current_password" name="current_password"
                                        placeholder="Masukkan password saat ini...">
                                    @error('current_password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="new_password">Password baru</label>
                                    <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                        id="new_password" name="new_password" placeholder="Masukkan password baru...">
                                    @error('new_password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="new_password_confirmation">Konfirmasi password baru</label>
                                    <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                        id="new_password_confirmation" name="new_password_confirmation"
                                        placeholder="Masukkan ulang password baru...">
                                    @error('new_password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>

                    </div>

                </div>
                <!-- /.row -->
            </form>
        </div><!-- /.container-fluid -->
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $("#upload_link").on('click', function(e){
                e.preventDefault();
                $("#photo").trigger('click');
            });

            // Untuk nampilin preview image saat di-upload
            function previewImage()  {
                const image = $('#photo')[0];
                const imgPreview = $('.profile-user-img');

                const blob = URL.createObjectURL(image.files[0]);
                imgPreview.attr('src', blob);
            }

            $("#photo").on('change', previewImage);
        });
    </script>
@endpush