@extends('layouts.app')
@section('title', 'Rincian Nota')
@section('page-title', 'Rincian Transaksi Pembayaran')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.nota.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fa fa-arrow-left me-1"></i> Kembali ke Riwayat
    </a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">Data Kunjungan & Pasien</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td width="40%" class="text-muted">No. Invoice</td><td>: <strong>{{ $kunjungan->no_kunjungan }}</strong></td></tr>
                    <tr><td class="text-muted">Waktu Bayar</td><td>: {{ \Carbon\Carbon::parse($kunjungan->bayar_at)->isoFormat('D MMMM Y, HH:mm') }} WIB</td></tr>
                    <tr class="border-top"><td class="text-muted pt-2">Nama Pasien</td><td class="pt-2 fw-semibold">{{ $kunjungan->pasien->nama }}</td></tr>
                    <tr><td class="text-muted">No. RM</td><td>{{ $kunjungan->pasien->no_rm }}</td></tr>
                    <tr><td class="text-muted">Dokter PJ</td><td>dr. {{ $kunjungan->dokter->name }}</td></tr>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm bg-success text-white text-center p-3 mb-4">
            <div class="text-uppercase small tracking-wider opacity-75">Total Pembayaran (Lunas)</div>
            <div class="display-6 fw-bold my-1">Rp {{ number_format($total) }}</div>
            <div class="mt-2">
                <a href="{{ route('admin.kasir.nota', $kunjungan) }}" target="_blank" class="btn btn-light btn-sm text-success fw-bold w-100">
                    <i class="fa fa-print me-1"></i> Cetak Ulang Nota Struk
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">Rincian Item yang Dibayar</div>
            <div class="card-body p-0">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Deskripsi Item / Layanan</th>
                            <th class="text-end" width="120">Harga Satuan</th>
                            <th class="text-center" width="80">Qty</th>
                            <th class="text-end" width="150">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="fw-semibold">Jasa Konsultasi & Pemeriksaan Dokter</div>
                                <small class="text-muted">Tarif pelayanan dokter umum/spesialis</small>
                            </td>
                            <td class="text-end">Rp {{ number_format($kunjungan->jasa_dokter ?? 50000) }}</td>
                            <td class="text-center">1</td>
                            <td class="text-end fw-semibold">Rp {{ number_format($kunjungan->jasa_dokter ?? 50000) }}</td>
                        </tr>

                        @if($kunjungan->tindakan->count() > 0)
                            <tr class="table-light"><td colspan="4" class="fw-bold small text-muted">TINDAKAN MEDIS</td></tr>
                            @foreach($kunjungan->tindakan as $t)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-semibold">{{ $t->tindakan->nama_tindakan }}</div>
                                    @if($t->keterangan)<small class="text-muted">{{ $t->keterangan }}</small>@endif
                                </td>
                                <td class="text-end">Rp {{ number_format($t->tarif) }}</td>
                                <td class="text-center">{{ $t->jumlah }}</td>
                                <td class="text-end fw-semibold">Rp {{ number_format($t->subtotal) }}</td>
                            </tr>
                            @endforeach
                        @endif

                        @if($kunjungan->resep && $kunjungan->resep->detail->count() > 0)
                            <tr class="table-light"><td colspan="4" class="fw-bold small text-muted">FARMASI / RESEP OBAT</td></tr>
                            @foreach($kunjungan->resep->detail as $od)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-semibold">{{ $od->obat->nama_obat }}</div>
                                    <small class="text-muted">Satuan: {{ $od->obat->satuan }}</small>
                                </td>
                                <td class="text-end">Rp {{ number_format($od->harga_jual) }}</td>
                                <td class="text-center">{{ $od->jumlah }}</td>
                                <td class="text-end fw-semibold">Rp {{ number_format($od->subtotal) }}</td>
                            </tr>
                            @endforeach
                        @endif

                        @if($kunjungan->rawatInap)
                            <tr class="table-light"><td colspan="4" class="fw-bold small text-muted">RAWAT INAP</td></tr>
                            @php 
                                $ri = $kunjungan->rawatInap;
                                $tglKeluar = $ri->tanggal_keluar ? \Carbon\Carbon::parse($ri->tanggal_keluar) : \Carbon\Carbon::parse($kunjungan->bayar_at);
                                $durasi = \Carbon\Carbon::parse($ri->tanggal_masuk)->diffInDays($tglKeluar) ?: 1;
                                $subtotalKamar = $ri->tarif_kamar * $durasi;
                            @endphp
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-semibold">Kamar: {{ $ri->kamar->nama_kamar }} (Kelas {{ $ri->kamar->kelas }})</div>
                                    <small class="text-muted">Masuk: {{ \Carbon\Carbon::parse($ri->tanggal_masuk)->format('d/m/Y') }}</small>
                                </td>
                                <td class="text-end">Rp {{ number_format($ri->tarif_kamar) }}</td>
                                <td class="text-center">{{ $durasi }} Hari</td>
                                <td class="text-end fw-semibold">Rp {{ number_format($subtotalKamar) }}</td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot class="table-light border-top">
                        <tr>
                            <td colspan="3" class="text-end fw-bold py-3">GRAND TOTAL :</td>
                            <td class="text-end fw-bold text-success py-3 fs-5">Rp {{ number_format($total) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection