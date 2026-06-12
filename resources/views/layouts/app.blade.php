<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Klinik') - Aplikasi Rekam Medis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { background: #f4f6fb; }
        .sidebar { width: 220px; min-height: 100vh; background: #2c3e6e; position: fixed; top: 0; left: 0; z-index: 100; }
        .sidebar .brand { padding: 20px 16px; color: #fff; font-weight: 700; font-size: 15px; border-bottom: 1px solid rgba(255,255,255,.15); }
        .sidebar .nav-link { color: rgba(255,255,255,.75); padding: 10px 16px; font-size: 14px; border-radius: 6px; margin: 2px 8px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: rgba(255,255,255,.15); color: #fff; }
        .sidebar .nav-link i { width: 20px; }
        .sidebar .nav-section { padding: 8px 16px 4px; font-size: 11px; color: rgba(255,255,255,.4); text-transform: uppercase; letter-spacing: 1px; }
        .main-content { margin-left: 220px; padding: 24px; }
        .topbar { background: #fff; padding: 12px 24px; margin: -24px -24px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
        .badge-menu { position: absolute; top: 6px; right: 6px; }
    </style>
    @stack('styles')
</head>
<body>
<div class="sidebar">
    <div class="brand"><i class="fa fa-hospital me-2"></i>Rekam Medis</div>
    <nav class="mt-2">
        @if(auth()->user()->isAdmin())
            <div class="nav-section">Menu</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link @active('admin/dashboard')">
                <i class="fa fa-dashboard"></i> Dashboard
            </a>
            <div class="nav-section">Pasien</div>
            <a href="{{ route('admin.antrian.index') }}" class="nav-link @active('admin/antrian*')">
                <i class="fa fa-list-ol"></i> Antrian
            </a>
            <a href="{{ route('admin.pasien.index') }}" class="nav-link @active('admin/pasien*')">
                <i class="fa fa-users"></i> Data Pasien
            </a>
            <div class="nav-section">Apotek & Kasir</div>
            <a href="{{ route('admin.resep.index') }}" class="nav-link d-flex justify-content-between align-items-center @active('admin/resep*')">
                <div><i class="fa fa-prescription"></i> Resep Masuk</div>
                @php $resepMenunggu = \App\Models\Resep::where('status','menunggu')->count() @endphp
                @if($resepMenunggu > 0) <span class="badge bg-danger rounded-pill">{{ $resepMenunggu }}</span> @endif
            </a>
            <a href="{{ route('admin.kasir.index') }}" class="nav-link @active('admin/kasir*')">
                <i class="fa fa-cash-register"></i> Kasir
            </a>
            <a href="{{ route('admin.nota.index') }}" class="nav-link @active('admin/nota*')">
                <i class="fa fa-file-invoice-dollar"></i> Riwayat Nota
            </a>
            <div class="nav-section">Master Data</div>
            <a href="{{ route('admin.obat.index') }}" class="nav-link @active('admin/obat*')">
                <i class="fa fa-pills"></i> Obat
            </a>
            <a href="{{ route('admin.kamar.index') }}" class="nav-link @active('admin/kamar*')">
                <i class="fa fa-bed"></i> Kamar
            </a>
            <a href="{{ route('admin.tindakan.index') }}" class="nav-link @active('admin/tindakan*')">
                <i class="fa fa-stethoscope"></i> Tindakan
            </a>
            <a href="{{ route('admin.akun.index') }}" class="nav-link @active('admin/akun*')">
                <i class="fa fa-user-cog"></i> Akun
            </a>
        @else
            <div class="nav-section">Menu</div>
            <a href="{{ route('dokter.dashboard') }}" class="nav-link @active('dokter/dashboard')">
                <i class="fa fa-dashboard"></i> Dashboard
            </a>
        @endif
    </nav>
</div>
<div class="main-content">
    <div class="topbar">
        <h6 class="mb-0 fw-semibold">@yield('page-title', 'Dashboard')</h6>
        <div class="d-flex align-items-center gap-3">
            <small class="text-muted">{{ now()->isoFormat('dddd, D MMMM Y') }}</small>
            <div class="dropdown">
                <button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fa fa-user-circle me-1"></i>{{ auth()->user()->name }}
                    <span class="badge bg-{{ auth()->user()->isAdmin() ? 'primary' : 'success' }} ms-1">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-danger"><i class="fa fa-sign-out me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="fa fa-check-circle me-2"></i>{{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show"><i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>