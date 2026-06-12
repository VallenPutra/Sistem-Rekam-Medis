{{-- resources/views/admin/tindakan/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Data Tindakan')
@section('page-title', 'Master Data Tindakan Medis')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>Daftar Tindakan</strong>
        <a href="{{ route('admin.tindakan.create') }}" class="btn btn-sm btn-primary">
            <i class="fa fa-plus me-1"></i>Tambah Tindakan
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Nama Tindakan</th>
                    <th>Tarif</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tindakan as $t)
                <tr>
                    <td><small class="text-muted">{{ $t->kode_tindakan }}</small></td>
                    <td>{{ $t->nama_tindakan }}</td>
                    <td>Rp {{ number_format($t->tarif) }}</td>
                    <td><small>{{ $t->keterangan ?? '-' }}</small></td>
                    <td>
                        <a href="{{ route('admin.tindakan.edit', $t) }}" class="btn btn-sm btn-outline-warning">Edit</a>

                        <form action="{{ route('admin.tindakan.destroy', $t) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus tindakan {{ $t->nama_tindakan }}? Data tidak bisa dikembalikan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fa fa-trash me-1"></i>Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data tindakan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tindakan->hasPages())
    <div class="card-footer bg-white">{{ $tindakan->links() }}</div>
    @endif
</div>
@endsection