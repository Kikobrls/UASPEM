@extends('layouts.app')

@section('title', 'Detail Karyawan')
@section('page-title', 'Detail Karyawan')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('karyawan.index') }}">Karyawan</a></li>
<li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    @if($karyawan->foto)
                    <img class="profile-user-img img-fluid img-circle" src="{{ asset('storage/' . $karyawan->foto) }}" alt="Foto">
                    @else
                    <img class="profile-user-img img-fluid img-circle" src="https://via.placeholder.com/150" alt="Foto">
                    @endif
                </div>

                <h3 class="profile-username text-center">{{ $karyawan->nama_lengkap }}</h3>
                <p class="text-muted text-center">{{ $karyawan->jabatan->nama_jabatan }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>NIP</b> <a class="float-right">{{ $karyawan->nip }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Email</b> <a class="float-right">{{ $karyawan->user->email }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Role</b> <a class="float-right">{{ ucfirst($karyawan->user->role) }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Status</b> 
                        <a class="float-right">
                            @if($karyawan->status == 'aktif')
                            <span class="badge badge-success">Aktif</span>
                            @else
                            <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </a>
                    </li>
                </ul>

                <a href="{{ route('karyawan.edit', $karyawan->id) }}" class="btn btn-warning btn-block">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Lengkap</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">Nama Lengkap</th>
                        <td>{{ $karyawan->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>{{ $karyawan->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Lahir</th>
                        <td>{{ $karyawan->tanggal_lahir ? $karyawan->tanggal_lahir->format('d F Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Masuk</th>
                        <td>{{ $karyawan->tanggal_masuk->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>No. Telepon</th>
                        <td>{{ $karyawan->no_telepon ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $karyawan->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <td>{{ $karyawan->jabatan->nama_jabatan }}</td>
                    </tr>
                    <tr>
                        <th>Gaji Pokok</th>
                        <td>Rp {{ number_format($karyawan->jabatan->gaji_pokok, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Gaji</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Periode</th>
                            <th>Gaji Bersih</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($karyawan->gaji as $item)
                        <tr>
                            <td>{{ date('F Y', mktime(0, 0, 0, $item->bulan, 1, $item->tahun)) }}</td>
                            <td>Rp {{ number_format($item->gaji_bersih, 0, ',', '.') }}</td>
                            <td>
                                @if($item->status == 'draft')
                                <span class="badge badge-warning">Draft</span>
                                @elseif($item->status == 'disetujui')
                                <span class="badge badge-primary">Disetujui</span>
                                @else
                                <span class="badge badge-success">Dibayar</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('gaji.show', $item->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data gaji</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection
