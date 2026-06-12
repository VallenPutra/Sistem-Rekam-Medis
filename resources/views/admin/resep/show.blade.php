@extends('layouts.app')
@section('title', 'Detail Resep')
@section('page-title', 'Detail Resep')

@section('content')
<div class="row justify-content-center">
<div class="col-md-7">
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>Resep — {{ $resep->kunjungan->no_kunjungan }}</strong>
        @php $warna = ['menunggu'=>'danger','diproses'=>'warning','selesai'=>'success']; @endphp
        <span class="badge bg-{{ $warna[$resep->status] }} fs-6">{{ ucfirst($resep->status) }}</span>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-6">
                <small class="text-muted d-block">Pasien</small>
                <strong>{{ $resep->kunjungan->pasien->nama }}</strong>
                <small class="text-muted d-block">{{ $resep->kunjungan->pasien->no_rm }}</small>
            </div>
            <div class="col-6">
                <small class="text-muted d-block">Dokter</small>
                <strong>{{ $resep->dokter->name }}</strong>
                <small class="text-muted d-block">{{ $resep->created_at->isoFormat('D MMM Y, HH:mm') }}</small>
            </div>
        </div>
        @if($resep->catatan)
        <div class="alert alert-info py-2"><small><strong>Catatan Dokter:</strong> {{ $resep->catatan }}</small></div>
        @endif

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Nama Obat</th>
                    <th>Jumlah</th>
                    <th>Aturan Pakai</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resep->detail as $d)
                <tr>
                    <td>
                        {{ $d->obat->nama_obat }}
                        @if($d->obat->stok < $d->jumlah)
                        <span class="badge bg-danger ms-1">Stok kurang!</span>
                        @endif
                    </td>
                    <td>{{ $d->jumlah }} {{ $d->obat->satuan }}</td>
                    <td>{{ $d->aturan_pakai }}</td>
                    <td>Rp {{ number_format($d->harga_satuan) }}</td>
                    <td>Rp {{ number_format($d->subtotal) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-primary fw-bold">
                    <td colspan="4" class="text-end">Total</td>
                    <td>Rp {{ number_format($resep->total_harga_obat) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="d-flex gap-2">
            @if($resep->status === 'menunggu')
            <form action="{{ route('admin.resep.proses', $resep) }}" method="POST"
                  onsubmit="return confirm('Proses & serahkan resep ini? Stok obat akan dikurangi.')">
                @csrf @method('PATCH')
                <button class="btn btn-success"><i class="fa fa-check me-1"></i>Proses & Serahkan Obat</button>
            </form>
            @endif
            <a href="{{ route('admin.resep.index') }}" class="btn btn-secondary">← Kembali</a>
        </div>
    </div>
</div>
</div>
</div>
@endsection
