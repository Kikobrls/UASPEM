@extends('layouts.app')

@section('title', 'Dashboard Karyawan')
@section('page-title', 'Dashboard Karyawan')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Info Karyawan -->
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
                        <b>Gaji Pokok</b> <a class="float-right">Rp {{ number_format($karyawan->jabatan->gaji_pokok, 0, ',', '.') }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Total Gaji Diterima</b> <a class="float-right">Rp {{ number_format($totalGajiDiterima, 0, ',', '.') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Gaji</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Periode</th>
                            <th>Gaji Pokok</th>
                            <th>Bonus</th>
                            <th>Potongan</th>
                            <th>Gaji Bersih</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gajiTerbaru as $item)
                        <tr>
                            <td>{{ date('F Y', mktime(0, 0, 0, $item->bulan, 1, $item->tahun)) }}</td>
                            <td>Rp {{ number_format($item->gaji_pokok, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->total_bonus, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->total_potongan, 0, ',', '.') }}</td>
                            <td><strong>Rp {{ number_format($item->gaji_bersih, 0, ',', '.') }}</strong></td>
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
                                <a href="{{ route('slip.show', $item->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-file-invoice"></i> Slip
                                </a>
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
