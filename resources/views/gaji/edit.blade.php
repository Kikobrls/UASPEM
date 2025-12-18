@extends('layouts.app')

@section('title', 'Edit Gaji')
@section('page-title', 'Edit Gaji')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('gaji.index') }}">Gaji</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Edit Gaji</h3>
            </div>
            <form action="{{ route('gaji.update', $gaji->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Karyawan <span class="text-danger">*</span></label>
                                <select class="form-control @error('karyawan_id') is-invalid @enderror" name="karyawan_id" id="karyawan_id" required>
                                    <option value="">Pilih Karyawan</option>
                                    @foreach($karyawan as $item)
                                    <option value="{{ $item->id }}" data-gaji="{{ $item->jabatan->gaji_pokok }}" {{ old('karyawan_id', $gaji->karyawan_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->nip }} - {{ $item->nama_lengkap }} ({{ $item->jabatan->nama_jabatan }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('karyawan_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Bulan <span class="text-danger">*</span></label>
                                <select class="form-control @error('bulan') is-invalid @enderror" name="bulan" required>
                                    <option value="">Pilih Bulan</option>
                                    @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('bulan', $gaji->bulan) == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                    @endfor
                                </select>
                                @error('bulan')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tahun <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('tahun') is-invalid @enderror" name="tahun" value="{{ old('tahun', $gaji->tahun) }}" min="2020" required>
                                @error('tahun')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Gaji Pokok</label>
                        <input type="text" class="form-control" id="gaji_pokok_display" value="Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}" readonly>
                        <small class="text-muted">Gaji pokok akan otomatis terisi sesuai jabatan karyawan</small>
                    </div>

                    <hr>
                    <h5>Bonus</h5>
                    <div id="bonus-container">
                        @forelse($gaji->bonus as $index => $bonus)
                        <div class="bonus-item mb-2">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="bonus[{{ $index }}][nama]" placeholder="Nama Bonus" value="{{ $bonus->nama_bonus }}">
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control bonus-jumlah" name="bonus[{{ $index }}][jumlah]" placeholder="Jumlah" min="0" value="{{ $bonus->jumlah }}">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="bonus[{{ $index }}][keterangan]" placeholder="Keterangan" value="{{ $bonus->keterangan }}">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm remove-bonus">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="bonus-item mb-2">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="bonus[0][nama]" placeholder="Nama Bonus">
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control bonus-jumlah" name="bonus[0][jumlah]" placeholder="Jumlah" min="0">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="bonus[0][keterangan]" placeholder="Keterangan">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm remove-bonus" disabled>
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforelse
                    </div>
                    <button type="button" class="btn btn-success btn-sm" id="add-bonus">
                        <i class="fas fa-plus"></i> Tambah Bonus
                    </button>

                    <hr>
                    <h5>Potongan</h5>
                    <div id="potongan-container">
                        @forelse($gaji->potongan as $index => $potongan)
                        <div class="potongan-item mb-2">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="potongan[{{ $index }}][nama]" placeholder="Nama Potongan" value="{{ $potongan->nama_potongan }}">
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control potongan-jumlah" name="potongan[{{ $index }}][jumlah]" placeholder="Jumlah" min="0" value="{{ $potongan->jumlah }}">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="potongan[{{ $index }}][keterangan]" placeholder="Keterangan" value="{{ $potongan->keterangan }}">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm remove-potongan">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="potongan-item mb-2">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="potongan[0][nama]" placeholder="Nama Potongan">
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control potongan-jumlah" name="potongan[0][jumlah]" placeholder="Jumlah" min="0">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="potongan[0][keterangan]" placeholder="Keterangan">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm remove-potongan" disabled>
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforelse
                    </div>
                    <button type="button" class="btn btn-success btn-sm" id="add-potongan">
                        <i class="fas fa-plus"></i> Tambah Potongan
                    </button>

                    <hr>
                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea class="form-control @error('catatan') is-invalid @enderror" name="catatan" rows="3">{{ old('catatan', $gaji->catatan) }}</textarea>
                        @error('catatan')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="alert alert-info">
                        <h5>Ringkasan:</h5>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td>Gaji Pokok</td>
                                <td class="text-right" id="summary-gaji-pokok">Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Total Bonus</td>
                                <td class="text-right" id="summary-bonus">Rp 0</td>
                            </tr>
                            <tr>
                                <td>Total Potongan</td>
                                <td class="text-right" id="summary-potongan">Rp 0</td>
                            </tr>
                            <tr class="font-weight-bold">
                                <td><strong>Gaji Bersih</strong></td>
                                <td class="text-right"><strong id="summary-gaji-bersih">Rp 0</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="{{ route('gaji.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let bonusIndex = {{ count($gaji->bonus) }};
let potonganIndex = {{ count($gaji->potongan) }};
let gajiPokok = {{ $gaji->gaji_pokok }};

// Format rupiah
function formatRupiah(angka) {
    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Update gaji pokok saat karyawan dipilih
$('#karyawan_id').change(function() {
    const selected = $(this).find(':selected');
    gajiPokok = parseFloat(selected.data('gaji')) || 0;
    $('#gaji_pokok_display').val(formatRupiah(gajiPokok));
    hitungTotal();
});

// Tambah bonus
$('#add-bonus').click(function() {
    const html = `
        <div class="bonus-item mb-2">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="bonus[${bonusIndex}][nama]" placeholder="Nama Bonus">
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control bonus-jumlah" name="bonus[${bonusIndex}][jumlah]" placeholder="Jumlah" min="0">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="bonus[${bonusIndex}][keterangan]" placeholder="Keterangan">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-bonus">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    $('#bonus-container').append(html);
    bonusIndex++;
});

// Hapus bonus
$(document).on('click', '.remove-bonus', function() {
    $(this).closest('.bonus-item').remove();
    hitungTotal();
});

// Tambah potongan
$('#add-potongan').click(function() {
    const html = `
        <div class="potongan-item mb-2">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="potongan[${potonganIndex}][nama]" placeholder="Nama Potongan">
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control potongan-jumlah" name="potongan[${potonganIndex}][jumlah]" placeholder="Jumlah" min="0">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="potongan[${potonganIndex}][keterangan]" placeholder="Keterangan">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-potongan">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    $('#potongan-container').append(html);
    potonganIndex++;
});

// Hapus potongan
$(document).on('click', '.remove-potongan', function() {
    $(this).closest('.potongan-item').remove();
    hitungTotal();
});

// Hitung total saat input berubah
$(document).on('input', '.bonus-jumlah, .potongan-jumlah', function() {
    hitungTotal();
});

// Fungsi hitung total
function hitungTotal() {
    let totalBonus = 0;
    let totalPotongan = 0;

    $('.bonus-jumlah').each(function() {
        totalBonus += parseFloat($(this).val()) || 0;
    });

    $('.potongan-jumlah').each(function() {
        totalPotongan += parseFloat($(this).val()) || 0;
    });

    const gajiBersih = gajiPokok + totalBonus - totalPotongan;

    $('#summary-gaji-pokok').text(formatRupiah(gajiPokok));
    $('#summary-bonus').text(formatRupiah(totalBonus));
    $('#summary-potongan').text(formatRupiah(totalPotongan));
    $('#summary-gaji-bersih').text(formatRupiah(gajiBersih));
}

// Hitung total saat halaman dimuat
$(document).ready(function() {
    hitungTotal();
});
</script>
@endpush
@endsection
