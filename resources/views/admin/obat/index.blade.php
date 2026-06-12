@extends('layouts.app')
@section('title', 'Data Obat')
@section('page-title', 'Inventaris Obat')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>Daftar Obat</strong>
        <a href="{{ route('admin.obat.create') }}" class="btn btn-sm btn-primary">
            <i class="fa fa-plus me-1"></i>Tambah Obat
        </a>
    </div>
    <div class="card-body border-bottom">
        <form method="GET" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari nama/kode obat..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-secondary">Cari</button>
                @if(request('search'))
                    <a href="{{ route('admin.obat.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Nama Obat</th>
                    <th>Satuan</th>
                    <th>Stok</th>
                    <th>Harga Jual</th>
                    <th>Kadaluarsa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($obat as $o)
                <tr class="{{ $o->isStokRendah() ? 'table-warning' : '' }}">
                    <td><small class="text-muted">{{ $o->kode_obat }}</small></td>
                    <td>
                        {{ $o->nama_obat }}
                        @if($o->isStokRendah())
                        <span class="badge bg-danger ms-1">Stok Rendah</span>
                        @endif
                    </td>
                    <td>{{ $o->satuan }}</td>
                    <td>
                        <span class="fw-bold {{ $o->isStokRendah() ? 'text-danger' : 'text-success' }}">
                            {{ $o->stok }}
                        </span>
                        <small class="text-muted">/ min {{ $o->stok_minimum }}</small>
                    </td>
                    <td>Rp {{ number_format($o->harga_jual) }}</td>
                    <td>
                        @if($o->tanggal_kadaluarsa)
                            <span class="{{ $o->tanggal_kadaluarsa->isPast() ? 'text-danger fw-bold' : '' }}">
                                {{ $o->tanggal_kadaluarsa->isoFormat('D MMM Y') }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.obat.edit', $o) }}" class="btn btn-sm btn-outline-warning">Edit</a>

                        <form action="{{ route('admin.obat.destroy', $o) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus obat {{ $o->nama_obat }}? Tindakan ini permanen.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fa fa-trash me-1"></i>Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data obat</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($obat->hasPages())
    <div class="card-footer bg-white">{{ $obat->links() }}</div>
    @endif
</div>
@endsection