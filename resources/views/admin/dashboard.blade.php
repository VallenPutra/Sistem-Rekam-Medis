@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-primary text-white rounded p-3"><i class="fa fa-users fa-lg"></i></div>
                <div><div class="fw-bold fs-4">{{ $total_pasien_hari_ini }}</div><small class="text-muted">Pasien Hari Ini</small></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-warning text-white rounded p-3"><i class="fa fa-bed fa-lg"></i></div>
                <div><div class="fw-bold fs-4">{{ $total_rawat_inap }}</div><small class="text-muted">Rawat Inap Aktif</small></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-danger text-white rounded p-3"><i class="fa fa-prescription fa-lg"></i></div>
                <div><div class="fw-bold fs-4">{{ $resep_menunggu }}</div><small class="text-muted">Resep Menunggu</small></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <strong>Antrian Hari Ini</strong>
                <a href="{{ route('admin.antrian.create') }}" class="btn btn-sm btn-primary">+ Daftarkan Pasien</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50">No</th>
                            <th>Pasien</th>
                            <th>Dokter</th>
                            <th>Keluhan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($antrian_hari_ini as $a)
                        <tr>
                            <td class="text-center fw-bold">{{ $a->nomor_antrian }}</td>
                            <td>
                                <div class="fw-semibold">{{ $a->pasien->nama }}</div>
                                <small class="text-muted">{{ $a->pasien->no_rm }}</small>
                            </td>
                            <td>{{ $a->dokter->name }}</td>
                            <td><small>{{ Str::limit($a->keluhan_utama, 30) }}</small></td>
                            <td>
                                @php $statusColor = ['menunggu'=>'warning','sedang_diperiksa'=>'primary','selesai'=>'success','rawat_inap'=>'info','batal'=>'secondary'] @endphp
                                <span class="badge bg-{{ $statusColor[$a->status] }}">{{ str_replace('_', ' ', $a->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada antrian hari ini</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white"><strong>Akses Cepat</strong></div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('admin.antrian.create') }}" class="btn btn-outline-primary"><i class="fa fa-plus me-2"></i>Daftarkan Pasien</a>
                <a href="{{ route('admin.resep.index') }}" class="btn btn-outline-danger"><i class="fa fa-prescription me-2"></i>Lihat Resep Masuk</a>
                <a href="{{ route('admin.pasien.create') }}" class="btn btn-outline-secondary"><i class="fa fa-user-plus me-2"></i>Daftar Pasien Baru</a>
            </div>
        </div>
        @if($obat_stok_rendah > 0)
        <div class="alert alert-warning">
            <i class="fa fa-exclamation-triangle me-2"></i>
            <strong>{{ $obat_stok_rendah }}</strong> obat stok hampir habis!
            <a href="{{ route('admin.obat.index') }}" class="alert-link">Cek sekarang</a>
        </div>
        @endif
    </div>
</div>
@endsection
