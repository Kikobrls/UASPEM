@extends('layouts.app')

@section('title', 'Daftar Karyawan')
@section('page-title', 'Daftar Karyawan')

@section('breadcrumb')
<li class="breadcrumb-item active">Karyawan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Karyawan</h3>
                <div class="card-tools">
                    <a href="{{ route('karyawan.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Karyawan
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="80">Foto</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($karyawan as $item)
                        <tr>
                            <td>
                                @if($item->foto)
                                <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($item->nama_lengkap) }}&background=random&size=50" alt="Avatar" class="img-thumbnail" style="width: 50px; height: 50px;">
                                @endif
                            </td>
                            <td>{{ $item->nip }}</td>
                            <td>{{ $item->nama_lengkap }}</td>
                            <td>{{ $item->jabatan->nama_jabatan }}</td>
                            <td>{{ $item->user->email }}</td>
                            <td>{{ $item->no_telepon ?? '-' }}</td>
                            <td>
                                @if($item->status == 'aktif')
                                <span class="badge badge-success">Aktif</span>
                                @else
                                <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('karyawan.show', $item->id) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('karyawan.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('karyawan.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus karyawan ini?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data karyawan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
