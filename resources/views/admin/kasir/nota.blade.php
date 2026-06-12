{{-- resources/views/admin/kasir/nota.blade.php --}}
@extends('layouts.app')
@section('title', 'Nota Pembayaran')
@section('page-title', 'Nota Pembayaran')

@section('content')
<div class="row justify-content-center">
<div class="col-md-7">
<div class="card border-0 shadow-sm" id="nota">
    <div class="card-body p-4">
        <div class="text-center mb-3">
            <h5 class="fw-bold">🏥 KLINIK SEHAT BERSAMA</h5>
            <small class="text-muted">Jl. Contoh No. 1 | Telp: 021-1234567</small>
            <hr>
            <h6>KUITANSI PEMBAYARAN</h6>
        </div>

        <table class="table table-borderless table-sm">
            <tr><td width="150">No Kunjungan</td><td>: {{ $kunjungan->no_kunjungan }}</td></tr>
            <tr><td>Tanggal</td><td>: {{ $kunjungan->bayar_at?->isoFormat('D MMMM Y, HH:mm') ?? now()->isoFormat('D MMMM Y') }}</td></tr>
            <tr><td>Nama Pasien</td><td>: {{ $kunjungan->pasien->nama }}</td></tr>
            <tr><td>No RM</td><td>: {{ $kunjungan->pasien->no_rm }}</td></tr>
            <tr><td>Dokter</td><td>: {{ $kunjungan->dokter->name }}</td></tr>
            <tr><td>Diagnosis</td><td>: {{ $kunjungan->diagnosis }}</td></tr>
        </table>

        <hr>
        <table class="table table-sm">
            <thead><tr><th>Keterangan</th><th class="text-end">Jumlah</th></tr></thead>
            <tbody>
                <tr><td>Jasa Dokter</td><td class="text-end">Rp {{ number_format($kunjungan->jasa_dokter) }}</td></tr>

                @if($kunjungan->tindakan->count())
                    @foreach($kunjungan->tindakan as $t)
                    <tr><td>{{ $t->tindakan->nama_tindakan }} x{{ $t->jumlah }}</td>
                        <td class="text-end">Rp {{ number_format($t->subtotal) }}</td></tr>
                    @endforeach
                @endif

                @if($kunjungan->resep)
                    @foreach($kunjungan->resep->detail as $d)
                    <tr><td>{{ $d->obat->nama_obat }} x{{ $d->jumlah }}</td>
                        <td class="text-end">Rp {{ number_format($d->subtotal) }}</td></tr>
                    @endforeach
                @endif

                @if($kunjungan->rawatInap)
                <tr><td>Kamar {{ $kunjungan->rawatInap->kamar->nama_kamar }} ({{ $kunjungan->rawatInap->lama_hari }} hari)</td>
                    <td class="text-end">Rp {{ number_format($kunjungan->rawatInap->total_biaya_kamar) }}</td></tr>
                @endif
            </tbody>
            <tfoot>
                <tr class="table-primary fw-bold">
                    <td>TOTAL</td>
                    <td class="text-end">Rp {{ number_format($total ?? $kunjungan->hitungTotalBiaya()) }}</td>
                </tr>
            </tfoot>
        </table>
        <div class="text-center mt-3">
            <small class="text-muted">Terima kasih atas kepercayaan Anda</small>
        </div>
    </div>
</div>
<div class="mt-3 d-flex gap-2 justify-content-center no-print">
    <button onclick="window.print()" class="btn btn-primary"><i class="fa fa-print me-1"></i>Cetak</button>
    <a href="{{ route('admin.kasir.index') }}" class="btn btn-secondary">← Kembali</a>
</div>
</div>
</div>
@push('styles')
<style>@media print { .no-print { display:none!important; } .main-content { margin-left:0!important; padding:0!important; } .sidebar,.topbar { display:none!important; } }</style>
@endpush
@endsection