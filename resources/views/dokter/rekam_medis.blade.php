@extends('layouts.app')
@section('title', 'Periksa Pasien')
@section('page-title', 'Pemeriksaan Pasien')

@section('content')
<div class="row g-3">
    {{-- Info Pasien --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="fw-bold fs-5">{{ $kunjungan->pasien->nama }}</div>
                        <small class="text-muted">No RM: {{ $kunjungan->pasien->no_rm }} | No Kunjungan: {{ $kunjungan->no_kunjungan }}</small>
                    </div>
                    <div class="col-md-4">
                        <small>Umur: <strong>{{ $kunjungan->pasien->umur }} tahun</strong> |
                        JK: <strong>{{ $kunjungan->pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</strong></small><br>
                        <small>Pembayaran: <span class="badge bg-{{ $kunjungan->pasien->jenis_pembayaran == 'bpjs' ? 'success' : 'secondary' }}">
                            {{ strtoupper($kunjungan->pasien->jenis_pembayaran) }}</span></small>
                    </div>
                    <div class="col-md-4">
                        <small>TD: <strong>{{ $kunjungan->tekanan_darah ?? '-' }}</strong> |
                        Suhu: <strong>{{ $kunjungan->suhu_tubuh ?? '-' }}°C</strong></small><br>
                        <small>BB: <strong>{{ $kunjungan->berat_badan ?? '-' }}kg</strong> |
                        TB: <strong>{{ $kunjungan->tinggi_badan ?? '-' }}cm</strong></small>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-muted">Keluhan: </span><strong>{{ $kunjungan->keluhan_utama }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Rekam Medis --}}
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><strong>📋 Rekam Medis</strong></div>
            <div class="card-body">
                <form action="{{ route('dokter.periksa.simpan', $kunjungan) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Anamnesis (Keluhan Detail)</label>
                        <textarea name="anamnesis" class="form-control" rows="3" required>{{ $kunjungan->anamnesis }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pemeriksaan Fisik</label>
                        <textarea name="pemeriksaan_fisik" class="form-control" rows="3" required>{{ $kunjungan->pemeriksaan_fisik }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Diagnosis</label>
                            <input type="text" name="diagnosis" class="form-control" value="{{ $kunjungan->diagnosis }}" required placeholder="Contoh: ISPA">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Kode ICD-10</label>
                            <input type="text" name="kode_icd10" class="form-control" value="{{ $kunjungan->kode_icd10 }}" placeholder="Contoh: J06">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan Dokter</label>
                        <textarea name="catatan_dokter" class="form-control" rows="2">{{ $kunjungan->catatan_dokter }}</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success"><i class="fa fa-save me-1"></i>Selesai & Simpan</button>
                        <a href="{{ route('dokter.rawat_inap.admisi', $kunjungan) }}" class="btn btn-warning">
                            <i class="fa fa-bed me-1"></i>Rawat Inap
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Tindakan & Resep --}}
    <div class="col-md-5">
        {{-- Tambah Tindakan --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white"><strong>⚕️ Tindakan Medis</strong></div>
            <div class="card-body">
                <form action="{{ route('dokter.tindakan.store', $kunjungan) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="row g-2">
                        <div class="col-8">
                            <select name="tindakan_id" class="form-select form-select-sm" required>
                                <option value="">-- Pilih Tindakan --</option>
                                @foreach($tindakanList as $t)
                                    <option value="{{ $t->id }}">{{ $t->nama_tindakan }} (Rp {{ number_format($t->tarif) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4">
                            <input type="number" name="jumlah" class="form-control form-control-sm" value="1" min="1" placeholder="Jml">
                        </div>
                        <div class="col-12">
                            <input type="text" name="keterangan" class="form-control form-control-sm" placeholder="Keterangan (opsional)">
                        </div>
                        <div class="col-12">
                            <button class="btn btn-sm btn-outline-primary w-100">+ Tambah Tindakan</button>
                        </div>
                    </div>
                </form>
                @foreach($kunjungan->tindakan as $t)
                <div class="d-flex justify-content-between align-items-center border-top py-1">
                    <small><strong>{{ $t->tindakan->nama_tindakan }}</strong> x{{ $t->jumlah }}<br>
                    Rp {{ number_format($t->subtotal) }}</small>
                    <form action="{{ route('dokter.tindakan.destroy', $t) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-link text-danger p-0"><i class="fa fa-trash"></i></button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>

        {{-- E-Resep --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white"><strong>💊 E-Resep</strong></div>
            <div class="card-body">
                <form action="{{ route('dokter.resep.store', $kunjungan) }}" method="POST">
                    @csrf
                    <div id="resep-items">
                        <div class="resep-item row g-1 mb-2">
                            <div class="col-5">
                                <select name="obat[0][obat_id]" class="form-select form-select-sm" required>
                                    <option value="">-- Obat --</option>
                                    @foreach($obatList as $o)
                                        <option value="{{ $o->id }}">{{ $o->nama_obat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <input type="number" name="obat[0][jumlah]" class="form-control form-control-sm" placeholder="Jml" min="1" required>
                            </div>
                            <div class="col-4">
                                <input type="text" name="obat[0][aturan_pakai]" class="form-control form-control-sm" placeholder="3x1" required>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary mb-2" onclick="tambahObat()">+ Tambah Obat</button>
                    <div class="mb-2">
                        <textarea name="catatan" class="form-control form-control-sm" rows="1" placeholder="Catatan resep..."></textarea>
                    </div>
                    <button class="btn btn-sm btn-danger w-100"><i class="fa fa-paper-plane me-1"></i>Kirim ke Apotek</button>
                </form>
                @if($kunjungan->resep)
                <div class="mt-2 p-2 bg-light rounded">
                    <small><strong>Resep terkirim:</strong>
                    <span class="badge bg-{{ $kunjungan->resep->status == 'selesai' ? 'success' : 'warning' }}">
                        {{ $kunjungan->resep->status }}</span></small>
                </div>
                @endif
            </div>
        </div>

        {{-- Upload Rontgen --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><strong>🩻 Foto Rontgen</strong></div>
            <div class="card-body">
                <form action="{{ route('dokter.rontgen.store', $kunjungan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-2">
                        <input type="text" name="bagian_tubuh" class="form-control form-control-sm" placeholder="Bagian tubuh (Dada, Perut, ...)" required>
                    </div>
                    <div class="mb-2">
                        <input type="file" name="foto_rontgen" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf" required>
                    </div>
                    <div class="mb-2">
                        <textarea name="hasil_analisis" class="form-control form-control-sm" rows="2" placeholder="Hasil analisis rontgen..."></textarea>
                    </div>
                    <button class="btn btn-sm btn-outline-info w-100"><i class="fa fa-upload me-1"></i>Upload</button>
                </form>
                @foreach($kunjungan->rontgen as $r)
                <div class="d-flex justify-content-between mt-2 border-top pt-1">
                    <small>{{ $r->bagian_tubuh }} - <a href="{{ Storage::url($r->file_path) }}" target="_blank">Lihat</a></small>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let obatIndex = 1;
function tambahObat() {
    const html = `
    <div class="resep-item row g-1 mb-2">
        <div class="col-5">
            <select name="obat[${obatIndex}][obat_id]" class="form-select form-select-sm" required>
                <option value="">-- Obat --</option>
                @foreach($obatList as $o)
                <option value="{{ $o->id }}">{{ $o->nama_obat }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <input type="number" name="obat[${obatIndex}][jumlah]" class="form-control form-control-sm" placeholder="Jml" min="1" required>
        </div>
        <div class="col-3">
            <input type="text" name="obat[${obatIndex}][aturan_pakai]" class="form-control form-control-sm" placeholder="3x1" required>
        </div>
        <div class="col-1">
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.resep-item').remove()">×</button>
        </div>
    </div>`;
    document.getElementById('resep-items').insertAdjacentHTML('beforeend', html);
    obatIndex++;
}
</script>
@endpush
@endsection
