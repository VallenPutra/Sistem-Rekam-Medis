@extends('layouts.app')
@section('title', 'Resep Masuk')
@section('page-title', 'Resep Masuk — Apotek')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><strong>Daftar Resep</strong></div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>No Kunjungan</th>
                    <th>Pasien</th>
                    <th>Dokter</th>
                    <th>Waktu</th>
                    <th>Jml Obat</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resep as $r)
                @php $warna = ['menunggu'=>'danger','diproses'=>'warning','selesai'=>'success']; @endphp
                <tr>
                    <td><small>{{ $r->kunjungan->no_kunjungan }}</small></td>
                    <td>{{ $r->kunjungan->pasien->nama }}</td>
                    <td>{{ $r->dokter->name }}</td>
                    <td><small>{{ $r->created_at->isoFormat('D MMM, HH:mm') }}</small></td>
                    <td>{{ $r->detail->count() }} item</td>
                    <td>Rp {{ number_format($r->total_harga_obat) }}</td>
                    <td><span class="badge bg-{{ $warna[$r->status] }}">{{ ucfirst($r->status) }}</span></td>
                    <td>
                        <a href="{{ route('admin.resep.show', $r) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                        @if($r->status === 'menunggu')
                        <form action="{{ route('admin.resep.proses', $r) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Proses & serahkan resep ini?')">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-success">Proses</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada resep</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($resep->hasPages())
    <div class="card-footer bg-white">{{ $resep->links() }}</div>
    @endif
</div>
@endsection
