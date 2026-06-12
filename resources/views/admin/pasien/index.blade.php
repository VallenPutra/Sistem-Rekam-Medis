@extends('layouts.app')
@section('title', 'Data Pasien')
@section('page-title', 'Data Pasien')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>Daftar Pasien</strong>
        <a href="{{ route('admin.pasien.create') }}" class="btn btn-sm btn-primary">
            <i class="fa fa-plus me-1"></i>Tambah Pasien
        </a>
    </div>
    <div class="card-body border-bottom">
        <form method="GET" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari nama, No RM, NIK..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-secondary">Cari</button>
                @if(request('search'))
                    <a href="{{ route('admin.pasien.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>No RM</th>
                    <th>Nama</th>
                    <th>JK</th>
                    <th>Umur</th>
                    <th>No HP</th>
                    <th>Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pasien as $p)
                <tr>
                    <td><span class="badge bg-secondary">{{ $p->no_rm }}</span></td>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>{{ $p->umur }} thn</td>
                    <td>{{ $p->no_hp ?? '-' }}</td>
                    <td>
                        <span class="badge bg-{{ $p->jenis_pembayaran == 'bpjs' ? 'success' : 'secondary' }}">
                            {{ strtoupper($p->jenis_pembayaran) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.pasien.show', $p) }}" class="btn btn-sm btn-outline-info">Detail</a>
                        <a href="{{ route('admin.pasien.edit', $p) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                        <a href="{{ route('admin.antrian.create') }}?pasien_id={{ $p->id }}" class="btn btn-sm btn-outline-primary">Daftar</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data pasien</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pasien->hasPages())
    <div class="card-footer bg-white">{{ $pasien->links() }}</div>
    @endif
</div>
@endsection
