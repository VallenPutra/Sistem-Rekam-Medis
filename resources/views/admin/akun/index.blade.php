@extends('layouts.app')
@section('title', 'Manajemen Akun')
@section('page-title', 'Manajemen Akun')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>Daftar Akun</strong>
        <a href="{{ route('admin.akun.create') }}" class="btn btn-sm btn-primary">
            <i class="fa fa-plus me-1"></i>Tambah Akun
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Spesialis</th>
                    <th>No HP</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>
                        <span class="badge bg-{{ $u->role=='admin'?'primary':'success' }}">
                            {{ ucfirst($u->role) }}
                        </span>
                    </td>
                    <td>{{ $u->spesialis ?? '-' }}</td>
                    <td>{{ $u->no_hp ?? '-' }}</td>
                    <td>
                        <span class="badge bg-{{ $u->aktif ? 'success' : 'secondary' }}">
                            {{ $u->aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.akun.edit', $u) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                        
                        @if($u->id !== auth()->id())
                        <form action="{{ route('admin.akun.toggle-aktif', $u) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('{{ $u->aktif ? 'Nonaktifkan' : 'Aktifkan' }} akun ini?')">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-{{ $u->aktif ? 'danger' : 'success' }}">
                                {{ $u->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.akun.destroy', $u) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Apakah Anda yakin ingin MENGHAPUS akun {{ $u->name }}? Data yang dihapus akan dipindahkan ke arsip sistem.')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="fa fa-trash me-1"></i>Hapus
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection