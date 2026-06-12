@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Ringkasan data klinik hari ini — {{ now()->isoFormat('dddd, D MMMM Y') }}</p>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-4">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="stat-value">{{ $totalPasien }}</div>
                    <div class="stat-label">Total Pasien</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="stat-card">
                <div class="stat-icon green"><i class="bi bi-file-medical-fill"></i></div>
                <div>
                    <div class="stat-value">{{ $totalRekamMedis }}</div>
                    <div class="stat-label">Total Rekam Medis</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="stat-card">
                <div class="stat-icon amber"><i class="bi bi-capsule-pill"></i></div>
                <div>
                    <div class="stat-value">{{ $totalObat }}</div>
                    <div class="stat-label">Jenis Obat</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kunjungan Terbaru --}}
    <div class="card">
        <div class="card-header d-flex align-items-center gap-2">
            <i class="bi bi-clock-history text-primary"></i>
            Kunjungan Terbaru
        </div>
        <div class="card-body p-0">
            @if($kunjunganTerbaru->isEmpty())
                <div class="text-center py-5" style="color:#94a3b8">
                    <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:.5rem"></i>
                    Belum ada data kunjungan.
                </div>
            @else
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Pasien</th>
                                <th>Tanggal Kunjungan</th>
                                <th>Keluhan</th>
                                <th>Diagnosa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kunjunganTerbaru as $i => $record)
                                <tr>
                                    <td style="color:#94a3b8;font-size:.75rem">{{ $i + 1 }}</td>
                                    <td>
                                        <div style="font-weight:600">{{ $record->patient->nama }}</div>
                                        <div style="font-size:.72rem;color:#94a3b8">
                                            {{ $record->patient->jenis_kelamin }}
                                        </div>
                                    </td>
                                    <td>
                                        <span style="font-size:.8rem">
                                            {{ $record->tanggal_kunjungan->isoFormat('D MMM Y') }}
                                        </span>
                                    </td>
                                    <td style="max-width:180px">
                                        <span class="text-truncate d-block" style="max-width:160px" title="{{ $record->keluhan }}">
                                            {{ $record->keluhan }}
                                        </span>
                                    </td>
                                    <td style="max-width:180px">
                                        <span class="text-truncate d-block" style="max-width:160px" title="{{ $record->diagnosa }}">
                                            {{ $record->diagnosa }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('medical-records.show', $record) }}"
                                           class="btn btn-sm btn-outline-primary btn-action">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        @if($kunjunganTerbaru->isNotEmpty())
            <div class="card-footer bg-transparent border-top-0 text-end py-2 pe-3">
                <a href="{{ route('medical-records.index') }}" class="btn btn-sm btn-primary">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        @endif
    </div>
@endsection
