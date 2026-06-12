@extends('layouts.app')
@section('title', 'Kasir')
@section('page-title', 'Kasir — Tagihan Pasien')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><strong>Tagihan Belum Dibayar</strong></div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>No Kunjungan</th>
                    <th>Pasien</th>
                    <th>Dokter</th>
                    <th>Tanggal</th>
                    <th>Diagnosis</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tagihan as $t)
                <tr>
                    <td><small>{{ $t->no_kunjungan }}</small></td>
                    <td>
                        <div class="fw-semibold">{{ $t->pasien->nama }}</div>
                        <small class="text-muted">{{ $t->pasien->no_rm }}</small>
                    </td>
                    <td>{{ $t->dokter->name }}</td>
                    <td><small>{{ $t->tanggal_kunjungan->isoFormat('D MMM Y') }}</small></td>
                    <td><small>{{ $t->diagnosis ?? '-' }}</small></td>
                    <td>
                        <a href="{{ route('admin.kasir.show', $t) }}" class="btn btn-sm btn-primary">
                            <i class="fa fa-money-bill me-1"></i>Bayar
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-5">
                    <i class="fa fa-check-circle fa-2x text-success d-block mb-2"></i>
                    Semua tagihan sudah lunas
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tagihan->hasPages())
    <div class="card-footer bg-white">{{ $tagihan->links() }}</div>
    @endif
</div>
@endsection
