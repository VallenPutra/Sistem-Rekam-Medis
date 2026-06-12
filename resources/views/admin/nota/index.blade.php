@extends('layouts.app')
@section('title', 'Riwayat Nota')
@section('page-title', 'Riwayat Nota & Transaksi')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <strong>Arsip Pembayaran Lunas</strong>
    </div>
    <div class="card-body border-bottom">
        <form method="GET" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari nama pasien atau No. RM..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-secondary">Cari</button>
                @if(request('search'))
                    <a href="{{ route('admin.nota.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Waktu Bayar</th>
                    <th>No. Kunjungan</th>
                    <th>Pasien</th>
                    <th>Dokter PJ</th>
                    <th>Total Biaya</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nota as $n)
                <tr>
                    <td>
                        <small class="text-muted">
                            {{ $n->bayar_at ? \Carbon\Carbon::parse($n->bayar_at)->isoFormat('D MMM Y, HH:mm') : '-' }}
                        </small>
                    </td>
                    <td><small class="fw-bold text-primary">{{ $n->no_kunjungan }}</small></td>
                    <td>
                        <div class="fw-semibold">{{ $n->pasien->nama }}</div>
                        <small class="text-muted">RM: {{ $n->pasien->no_rm }}</small>
                    </td>
                    <td>dr. {{ $n->dokter->name }}</td>
                    <td class="fw-bold text-success">Rp {{ number_format($n->total_biaya) }}</td>
                    <td>
                        <a href="{{ route('admin.nota.show', $n) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-eye me-1"></i>Rincian
                        </a>
                        <a href="{{ route('admin.kasir.nota', $n) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="fa fa-print me-1"></i>Cetak Nota
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-5">Belum ada riwayat pembayaran lunas</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($nota->hasPages())
    <div class="card-footer bg-white">{{ $nota->links() }}</div>
    @endif
</div>
@endsection