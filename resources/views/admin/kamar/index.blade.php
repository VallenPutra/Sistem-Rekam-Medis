@extends('layouts.app')
@section('title', 'Data Kamar')
@section('page-title', 'Data Kamar Rawat Inap')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>Daftar Kamar</strong>
        <a href="{{ route('admin.kamar.create') }}" class="btn btn-sm btn-primary">
            <i class="fa fa-plus me-1"></i>Tambah Kamar
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama Kamar</th>
                    <th>Kelas</th>
                    <th>Tarif/Hari</th>
                    <th>Kapasitas</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kamar as $k)
                @php $warna = ['tersedia'=>'success','terisi'=>'warning','maintenance'=>'secondary']; @endphp
                <tr>
                    <td class="fw-semibold">{{ $k->nama_kamar }}</td>
                    <td>{{ $k->kelas }}</td>
                    <td>Rp {{ number_format($k->tarif_per_hari) }}</td>
                    <td>{{ $k->kapasitas }} orang</td>
                    <td>
                        <span class="badge bg-{{ $warna[$k->status] }}">{{ ucfirst($k->status) }}</span>
                    </td>
                    <td><small>{{ $k->keterangan ?? '-' }}</small></td>
                    <td>
                        <a href="{{ route('admin.kamar.edit', $k) }}" class="btn btn-sm btn-outline-warning">Edit</a>

                        <form action="{{ route('admin.kamar.destroy', $k) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus {{ $k->nama_kamar }}? Data tidak bisa dikembalikan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fa fa-trash me-1"></i>Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data kamar</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection