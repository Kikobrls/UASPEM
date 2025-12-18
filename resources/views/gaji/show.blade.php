@extends('layouts.app')

@section('title', 'Slip Gaji')
@section('page-title', 'Slip Gaji')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('gaji.index') }}">Gaji</a></li>
<li class="breadcrumb-item active">Slip Gaji</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Slip Gaji - {{ date('F Y', mktime(0, 0, 0, $gaji->bulan, 1, $gaji->tahun)) }}</h3>
                <div class="card-tools">
                    <button onclick="window.print()" class="btn btn-primary btn-sm">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Info Karyawan -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">NIP</th>
                                <td>: {{ $gaji->karyawan->nip }}</td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td>: {{ $gaji->karyawan->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <th>Jabatan</th>
                                <td>: {{ $gaji->karyawan->jabatan->nama_jabatan }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Periode</th>
                                <td>: {{ date('F Y', mktime(0, 0, 0, $gaji->bulan, 1, $gaji->tahun)) }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>: 
                                    @if($gaji->status == 'draft')
                                    <span class="badge badge-warning">Draft</span>
                                    @elseif($gaji->status == 'disetujui')
                                    <span class="badge badge-primary">Disetujui</span>
                                    @else
                                    <span class="badge badge-success">Dibayar</span>
                                    @endif
                                </td>
                            </tr>
                            @if($gaji->status != 'draft')
                            <tr>
                                <th>Disetujui Oleh</th>
                                <td>: {{ $gaji->disetujuiOleh->name ?? '-' }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <hr>

                <!-- Detail Gaji -->
                <div class="row">
                    <div class="col-md-6">
                        <h5>Pendapatan</h5>
                        <table class="table table-striped">
                            <tr>
                                <td>Gaji Pokok</td>
                                <td class="text-right">Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
                            </tr>
                            @foreach($gaji->bonus as $bonus)
                            <tr>
                                <td>{{ $bonus->nama_bonus }}</td>
                                <td class="text-right">Rp {{ number_format($bonus->jumlah, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr class="bg-light">
                                <th>Total Pendapatan</th>
                                <th class="text-right">Rp {{ number_format($gaji->gaji_pokok + $gaji->total_bonus, 0, ',', '.') }}</th>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Potongan</h5>
                        <table class="table table-striped">
                            @forelse($gaji->potongan as $potongan)
                            <tr>
                                <td>{{ $potongan->nama_potongan }}</td>
                                <td class="text-right">Rp {{ number_format($potongan->jumlah, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center">Tidak ada potongan</td>
                            </tr>
                            @endforelse
                            <tr class="bg-light">
                                <th>Total Potongan</th>
                                <th class="text-right">Rp {{ number_format($gaji->total_potongan, 0, ',', '.') }}</th>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <!-- Total Gaji Bersih -->
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success">
                            <h4 class="mb-0">
                                <strong>Gaji Bersih:</strong>
                                <span class="float-right">Rp {{ number_format($gaji->gaji_bersih, 0, ',', '.') }}</span>
                            </h4>
                        </div>
                    </div>
                </div>

                @if($gaji->catatan)
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <strong>Catatan:</strong><br>
                            {{ $gaji->catatan }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .main-header, .main-sidebar, .main-footer, .card-footer, .card-tools {
        display: none !important;
    }
    .content-wrapper {
        margin: 0 !important;
        padding: 0 !important;
    }
}
</style>
@endpush
@endsection
