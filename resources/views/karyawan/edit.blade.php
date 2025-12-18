@extends('layouts.app')

@section('title', 'Edit Karyawan')
@section('page-title', 'Edit Karyawan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('karyawan.index') }}">Karyawan</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Profile & Status -->
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center position-relative">
                        <div id="preview-container" class="mb-3">
                            @if($karyawan->foto)
                            <img id="preview-image" src="{{ asset('storage/' . $karyawan->foto) }}" alt="Foto" class="profile-user-img img-fluid img-circle" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #adb5bd;">
                            @else
                            <img id="preview-image" src="https://ui-avatars.com/api/?name={{ urlencode($karyawan->nama_lengkap) }}&background=0D6EFD&color=fff&size=150" alt="Preview" class="profile-user-img img-fluid img-circle" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #adb5bd;">
                            @endif
                        </div>
                        <h3 class="profile-username text-center font-weight-bold mb-0">{{ $karyawan->nama_lengkap }}</h3>
                        <p class="text-muted text-center">{{ $karyawan->jabatan->nama_jabatan }}</p>
                    </div>

                    <form action="{{ route('karyawan.update', $karyawan->id) }}" method="POST" enctype="multipart/form-data" id="main-form">
                        @csrf
                        @method('PUT')
                        
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Status Kepegawaian</b> 
                                <span class="float-right">
                                    <select class="form-control form-control-sm @error('status') is-invalid @enderror" name="status" required>
                                        <option value="aktif" {{ old('status', $karyawan->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="nonaktif" {{ old('status', $karyawan->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                </span>
                            </li>
                            <li class="list-group-item border-bottom-0">
                                <b>Ubah Foto</b>
                                <div class="mt-2">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('foto') is-invalid @enderror" name="foto" id="foto-input" accept="image/*">
                                        <label class="custom-file-label" for="foto-input">Pilih file...</label>
                                    </div>
                                    @error('foto')<span class="text-danger small">{{ $message }}</span>@enderror
                                    <small class="text-muted d-block mt-1">JPG/PNG, Max 2MB. Kosongkan jika tidak ingin mengubah.</small>
                                </div>
                            </li>
                        </ul>
                </div>
            </div>

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Informasi Akun</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label><i class="fas fa-envelope mr-1"></i> Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $karyawan->user->email) }}" required>
                        @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-lock mr-1"></i> Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Kosongkan jika tidak berubah">
                        @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-user-tag mr-1"></i> Role <span class="text-danger">*</span></label>
                        <select class="form-control @error('role') is-invalid @enderror" name="role" required>
                            <option value="admin" {{ old('role', $karyawan->user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="manager" {{ old('role', $karyawan->user->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="karyawan" {{ old('role', $karyawan->user->role) == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                        </select>
                        @error('role')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form Data -->
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-id-card mr-1"></i> Biodata Lengkap Karyawan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>NIP <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                    </div>
                                    <input type="text" class="form-control @error('nip') is-invalid @enderror" name="nip" value="{{ old('nip', $karyawan->nip) }}" required>
                                </div>
                                @error('nip')<span class="text-danger small">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" name="nama_lengkap" value="{{ old('nama_lengkap', $karyawan->nama_lengkap) }}" required>
                                </div>
                                @error('nama_lengkap')<span class="text-danger small">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jabatan <span class="text-danger">*</span></label>
                                <select class="form-control select2 @error('jabatan_id') is-invalid @enderror" name="jabatan_id" required>
                                    @foreach($jabatan as $item)
                                    <option value="{{ $item->id }}" {{ old('jabatan_id', $karyawan->jabatan_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_jabatan }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('jabatan_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                <div class="d-flex mt-2">
                                    <div class="custom-control custom-radio mr-3">
                                        <input class="custom-control-input" type="radio" id="jkL" name="jenis_kelamin" value="L" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'L' ? 'checked' : '' }} required>
                                        <label for="jkL" class="custom-control-label">Laki-laki</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="jkP" name="jenis_kelamin" value="P" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'P' ? 'checked' : '' }} required>
                                        <label for="jkP" class="custom-control-label">Perempuan</label>
                                    </div>
                                </div>
                                @error('jenis_kelamin')<span class="text-danger small">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Lahir</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    </div>
                                    <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" name="tanggal_lahir" value="{{ old('tanggal_lahir', $karyawan->tanggal_lahir?->format('Y-m-d')) }}">
                                </div>
                                @error('tanggal_lahir')<span class="text-danger small">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Masuk <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror" name="tanggal_masuk" value="{{ old('tanggal_masuk', $karyawan->tanggal_masuk->format('Y-m-d')) }}" required>
                                </div>
                                @error('tanggal_masuk')<span class="text-danger small">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>No. Telepon</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                            <input type="text" class="form-control @error('no_telepon') is-invalid @enderror" name="no_telepon" value="{{ old('no_telepon', $karyawan->no_telepon) }}" placeholder="08xxxxxxxxxx">
                        </div>
                        @error('no_telepon')<span class="text-danger small">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label>Alamat Lengkap</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="4" placeholder="Alamat sesuai KTP/Domisili">{{ old('alamat', $karyawan->alamat) }}</textarea>
                        @error('alamat')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                </div>
                <div class="card-footer bg-white">
                    <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm font-weight-bold">
                        <i class="fas fa-save mr-1"></i> SIMPAN PERUBAHAN
                    </button>
                    <a href="{{ route('karyawan.index') }}" class="btn btn-default px-4 py-2 border ml-2">
                        <i class="fas fa-arrow-left mr-1"></i> KEMBALI
                    </a>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Preview foto
        $('#foto-input').on('change', function(e) {
            const file = e.target.files[0];
            const reader = new FileReader();
            const fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);

            reader.onload = function(e) {
                $('#preview-image').attr('src', e.target.result);
            }
            if (file) {
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush
