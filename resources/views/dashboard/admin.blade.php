@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Info boxes -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalKaryawan }}</h3>
                <p>Total Karyawan Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('karyawan.index') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalGajiDraft }}</h3>
                <p>Gaji Draft</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <a href="{{ route('gaji.index') }}?status=draft" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $totalGajiMenunggu }}</h3>
                <p>Menunggu Pembayaran</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
            <a href="{{ route('gaji.index') }}?status=disetujui" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalGajiDibayar }}</h3>
                <p>Gaji Dibayar</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('gaji.index') }}?status=dibayar" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Tabel Gaji Terbaru -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Gaji Terbaru</h3>
                <div class="card-tools">
                    <a href="{{ route('gaji.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Gaji
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
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
                        @forelse($gajiTerbaru as $item)
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
    </div>
</div>
@endsection
