@extends('layouts.app')

@section('title', 'Manajemen Gaji')
@section('page-title', 'Manajemen Gaji')

@section('breadcrumb')
<li class="breadcrumb-item active">Gaji</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Gaji</h3>
                <div class="card-tools">
                    <a href="{{ route('gaji.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Gaji
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <form action="{{ route('gaji.index') }}" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <select name="status" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="dibayar" {{ request('status') == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="bulan" class="form-control">
                                <option value="">Semua Bulan</option>
                                @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="tahun" class="form-control" placeholder="Tahun" value="{{ request('tahun') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('gaji.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>NIP</th>
                                <th>Nama Karyawan</th>
                                <th>Jabatan</th>
                                <th>Periode</th>
                                <th>Gaji Bersih</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gaji as $item)
                            <tr>
                                <td>{{ $item->karyawan->nip }}</td>
                                <td>{{ $item->karyawan->nama_lengkap }}</td>
                                <td>{{ $item->karyawan->jabatan->nama_jabatan }}</td>
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
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($item->status == 'draft')
                                    <a href="{{ route('gaji.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('gaji.approve', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Setujui gaji ini?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('gaji.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @elseif($item->status == 'disetujui' && auth()->user()->isAdmin())
                                    <form action="{{ route('gaji.pay', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Tandai sebagai dibayar?')">
                                            <i class="fas fa-money-bill"></i> Bayar
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data gaji</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $gaji->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
