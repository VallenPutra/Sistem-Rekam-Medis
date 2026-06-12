@extends('layouts.app')
@section('title', 'Detail Pasien')
@section('page-title', 'Detail Pasien')

@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between">
                <strong>Identitas Pasien</strong>
                <a href="{{ route('admin.pasien.edit', $pasien) }}" class="btn btn-sm btn-outline-warning"><i class="fa fa-edit"></i></a>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width:60px;height:60px;font-size:24px">
                        <i class="fa fa-user"></i>
                    </div>
                    <div class="fw-bold fs-5 mt-2">{{ $pasien->nama }}</div>
                    <span class="badge bg-secondary">{{ $pasien->no_rm }}</span>
                </div>
                <table class="table table-sm table-borderless">
                    <tr><td class="text-muted">Umur</td><td>{{ $pasien->umur }} tahun</td></tr>
                    <tr><td class="text-muted">JK</td><td>{{ $pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                    <tr><td class="text-muted">Tgl Lahir</td><td>{{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->isoFormat('D MMMM Y') }}</td></tr>
                    <tr><td class="text-muted">Gol. Darah</td><td>{{ $pasien->golongan_darah ?? '-' }}</td></tr>
                    <tr><td class="text-muted">NIK</td><td>{{ $pasien->nik ?? '-' }}</td></tr>
                    <tr><td class="text-muted">No HP</td><td>{{ $pasien->no_hp ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Pembayaran</td><td><span class="badge bg-{{ $pasien->jenis_pembayaran=='bpjs'?'success':'secondary' }}">{{ strtoupper($pasien->jenis_pembayaran) }}</span></td></tr>
                    @if($pasien->no_bpjs)
                    <tr><td class="text-muted">No BPJS</td><td>{{ $pasien->no_bpjs }}</td></tr>
                    @endif
                    <tr><td class="text-muted">Alamat</td><td>{{ $pasien->alamat ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <strong>Riwayat Kunjungan</strong>
                <a href="{{ route('admin.antrian.create') }}" class="btn btn-sm btn-primary">+ Daftarkan</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No Kunjungan</th>
                            <th>Tanggal</th>
                            <th>Dokter</th>
                            <th>Diagnosis</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $k)
                        <tr>
                            <td><small class="text-muted">{{ $k->no_kunjungan }}</small></td>
                            <td><small>{{ $k->tanggal_kunjungan->isoFormat('D MMM Y') }}</small></td>
                            <td><small>{{ $k->dokter->name }}</small></td>
                            <td><small>{{ $k->diagnosis ?? '-' }}</small></td>
                            <td>
                                @php $statusColor = ['menunggu'=>'warning','sedang_diperiksa'=>'primary','selesai'=>'success','rawat_inap'=>'info','batal'=>'secondary'] @endphp
                                <span class="badge bg-{{ $statusColor[$k->status] }}">{{ str_replace('_',' ',$k->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Belum ada riwayat kunjungan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
