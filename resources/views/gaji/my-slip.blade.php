@extends('layouts.app')

@section('title', 'Slip Gaji Saya')
@section('page-title', 'Slip Gaji Saya')

@section('breadcrumb')
<li class="breadcrumb-item active">Slip Gaji</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Slip Gaji - {{ $karyawan->nama_lengkap }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($gaji as $item)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card {{ $item->status == 'dibayar' ? 'border-success' : ($item->status == 'disetujui' ? 'border-primary' : 'border-warning') }}">
                            <div class="card-header {{ $item->status == 'dibayar' ? 'bg-success' : ($item->status == 'disetujui' ? 'bg-primary' : 'bg-warning') }}">
                                <h5 class="card-title mb-0 text-white">
                                    {{ date('F Y', mktime(0, 0, 0, $item->bulan, 1, $item->tahun)) }}
                                </h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-borderless mb-2">
                                    <tr>
                                        <td>Gaji Pokok</td>
                                        <td class="text-right">Rp {{ number_format($item->gaji_pokok, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Bonus</td>
                                        <td class="text-right">Rp {{ number_format($item->total_bonus, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Potongan</td>
                                        <td class="text-right">Rp {{ number_format($item->total_potongan, 0, ',', '.') }}</td>
                                    </tr>
                                </table>
                                <hr>
                                <div class="row">
                                    <div class="col-6">
                                        <strong>Gaji Bersih:</strong>
                                    </div>
                                    <div class="col-6 text-right">
                                        <strong>Rp {{ number_format($item->gaji_bersih, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    @if($item->status == 'draft')
                                    <span class="badge badge-warning">Draft</span>
                                    @elseif($item->status == 'disetujui')
                                    <span class="badge badge-primary">Disetujui</span>
                                    @else
                                    <span class="badge badge-success">Dibayar</span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('slip.show', $item->id) }}" class="btn btn-info btn-sm btn-block">
                                    <i class="fas fa-file-invoice"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            Belum ada data slip gaji.
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="card-footer">
                {{ $gaji->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
