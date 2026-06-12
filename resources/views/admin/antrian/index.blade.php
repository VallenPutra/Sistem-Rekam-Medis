@extends('layouts.app')
@section('title', 'Antrian')
@section('page-title', 'Antrian Hari Ini')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>Antrian — {{ now()->isoFormat('dddd, D MMMM Y') }}</strong>
        <a href="{{ route('admin.antrian.create') }}" class="btn btn-sm btn-primary">
            <i class="fa fa-plus me-1"></i>Daftarkan Pasien
        </a>
    </div>
    @if($dokterList->count())
    <div class="card-body border-bottom">
        <div class="row g-2">
            @foreach($dokterList as $d)
            @php $jumlah = $antrian->where('dokter_id', $d->id)->count(); @endphp
            <div class="col-auto">
                <span class="badge bg-primary fs-6 p-2">dr. {{ $d->name }}: {{ $jumlah }} pasien</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th width="60">No</th>
                    <th>Pasien</th>
                    <th>Dokter</th>
                    <th>Keluhan</th>
                    <th>Tanda Vital</th>
                    <th>Status</th>
                    <th>Aksi</th> </tr>
            </thead>
            <tbody>
                @forelse($antrian as $a)
                @php
                    $warna = ['menunggu'=>'warning','sedang_diperiksa'=>'primary','selesai'=>'success','rawat_inap'=>'info','batal'=>'secondary'];
                @endphp
                <tr class="{{ $a->status == 'sedang_diperiksa' ? 'table-primary' : '' }}">
                    <td class="text-center fw-bold fs-5">{{ $a->nomor_antrian }}</td>
                    <td>
                        <div class="fw-semibold">{{ $a->pasien->nama }}</div>
                        <small class="text-muted">{{ $a->pasien->no_rm }} · {{ $a->pasien->umur }} thn · {{ $a->pasien->jenis_kelamin }}</small>
                    </td>
                    <td>{{ $a->dokter->name }}</td>
                    <td><small>{{ $a->keluhan_utama }}</small></td>
                    <td>
                        <small>
                            @if($a->tekanan_darah) TD: {{ $a->tekanan_darah }}<br>@endif
                            @if($a->suhu_tubuh) S: {{ $a->suhu_tubuh }}°C<br>@endif
                            @if($a->berat_badan) BB: {{ $a->berat_badan }} kg @endif
                        </small>
                    </td>
                    <td>
                        <span class="badge bg-{{ $warna[$a->status] }}">
                            {{ str_replace('_', ' ', ucfirst($a->status)) }}
                        </span>
                    </td>
                    <td>
                        @if($a->status === 'menunggu')
                        <form action="{{ route('admin.antrian.destroy', $a) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Apakah Anda yakin ingin membatalkan/menghapus antrian nomor {{ $a->nomor_antrian }} untuk pasien {{ $a->pasien->nama }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Batalkan Antrian">
                                <i class="fa fa-times me-1"></i>Batal
                            </button>
                        </form>
                        @else
                        <span class="text-muted"><small>-</small></span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-5">Belum ada antrian hari ini</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection