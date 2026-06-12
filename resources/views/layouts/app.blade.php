<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Klinik Sehat') — Sistem Rekam Medis</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- Google Fonts: Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ── Variabel & Reset ─────────────────────────────── */
        :root {
            --primary:     #2563eb;
            --primary-lt:  #eff6ff;
            --sidebar-w:   260px;
            --navbar-h:    60px;
            --gray-50:     #f8fafc;
            --gray-100:    #f1f5f9;
            --gray-200:    #e2e8f0;
            --gray-400:    #94a3b8;
            --gray-600:    #475569;
            --gray-800:    #1e293b;
            --success:     #16a34a;
            --danger:      #dc2626;
            --warning:     #d97706;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--gray-50);
            color: var(--gray-800);
            margin: 0;
        }

        /* ── Navbar ──────────────────────────────────────── */
        .navbar-top {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--navbar-h);
            background: #fff;
            border-bottom: 1px solid var(--gray-200);
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            z-index: 1030;
            gap: 1rem;
        }

        .navbar-top .brand {
            display: flex;
            align-items: center;
            gap: .5rem;
            text-decoration: none;
            color: var(--gray-800);
            font-weight: 700;
            font-size: 1.1rem;
            width: var(--sidebar-w);
        }

        .navbar-top .brand .icon-wrap {
            width: 32px; height: 32px;
            background: var(--primary);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: .9rem;
            flex-shrink: 0;
        }

        .navbar-top .nav-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .btn-sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.3rem;
            color: var(--gray-600);
            cursor: pointer;
            padding: .25rem .5rem;
            border-radius: 6px;
            display: none;
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .875rem;
            color: var(--gray-600);
        }

        .user-badge .avatar {
            width: 34px; height: 34px;
            background: var(--primary-lt);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: var(--primary);
            font-size: .75rem;
            font-weight: 600;
        }

        /* ── Sidebar ─────────────────────────────────────── */
        .sidebar {
            position: fixed;
            top: var(--navbar-h);
            left: 0;
            width: var(--sidebar-w);
            height: calc(100vh - var(--navbar-h));
            background: #fff;
            border-right: 1px solid var(--gray-200);
            overflow-y: auto;
            padding: 1.25rem 1rem;
            z-index: 1020;
            transition: transform .3s ease;
        }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: var(--gray-200); border-radius: 4px; }

        .sidebar-label {
            font-size: .65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--gray-400);
            padding: .5rem .75rem .25rem;
            margin-top: .5rem;
        }

        .sidebar-nav { list-style: none; padding: 0; margin: 0; }

        .sidebar-nav li a {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .55rem .75rem;
            border-radius: 8px;
            color: var(--gray-600);
            text-decoration: none;
            font-size: .875rem;
            font-weight: 500;
            transition: all .15s;
        }

        .sidebar-nav li a:hover {
            background: var(--gray-100);
            color: var(--gray-800);
        }

        .sidebar-nav li a.active {
            background: var(--primary-lt);
            color: var(--primary);
        }

        .sidebar-nav li a i {
            font-size: 1.05rem;
            width: 20px;
            text-align: center;
        }

        /* ── Main Content ────────────────────────────────── */
        .main-content {
            margin-left: var(--sidebar-w);
            margin-top: var(--navbar-h);
            padding: 2rem;
            min-height: calc(100vh - var(--navbar-h));
        }

        /* ── Page Header ─────────────────────────────────── */
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1.75rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title {
            font-size: 1.375rem;
            font-weight: 700;
            color: var(--gray-800);
            margin: 0;
        }

        .page-subtitle {
            font-size: .8rem;
            color: var(--gray-400);
            margin: .15rem 0 0;
        }

        /* ── Card ─────────────────────────────────────────── */
        .card {
            background: #fff;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,.04);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid var(--gray-200);
            padding: 1rem 1.25rem;
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
            font-size: .9rem;
        }

        .card-body { padding: 1.25rem; }

        /* ── Stat Cards ──────────────────────────────────── */
        .stat-card {
            background: #fff;
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .stat-icon.blue   { background: var(--primary-lt); color: var(--primary); }
        .stat-icon.green  { background: #f0fdf4; color: var(--success); }
        .stat-icon.amber  { background: #fffbeb; color: var(--warning); }
        .stat-icon.purple { background: #f5f3ff; color: #7c3aed; }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--gray-800);
            line-height: 1;
        }

        .stat-label {
            font-size: .8rem;
            color: var(--gray-400);
            margin-top: .2rem;
        }

        /* ── Table ───────────────────────────────────────── */
        .table-wrapper {
            overflow-x: auto;
        }

        .table {
            margin: 0;
            font-size: .875rem;
        }

        .table thead th {
            background: var(--gray-50);
            color: var(--gray-600);
            font-weight: 600;
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            border-bottom: 1px solid var(--gray-200);
            border-top: none;
            padding: .75rem 1rem;
            white-space: nowrap;
        }

        .table tbody td {
            padding: .85rem 1rem;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
            color: var(--gray-800);
        }

        .table tbody tr:last-child td { border-bottom: none; }

        .table tbody tr:hover td { background: var(--gray-50); }

        /* ── Badges ─────────────────────────────────────── */
        .badge-gender {
            display: inline-flex;
            align-items: center;
            gap: .25rem;
            padding: .25rem .65rem;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 600;
        }

        .badge-male   { background: #eff6ff; color: #2563eb; }
        .badge-female { background: #fdf2f8; color: #db2777; }

        .badge-stok-ok  { background: #f0fdf4; color: #16a34a; }
        .badge-stok-low { background: #fefce8; color: #ca8a04; }
        .badge-stok-out { background: #fef2f2; color: #dc2626; }

        /* ── Buttons ─────────────────────────────────────── */
        .btn {
            font-size: .8rem;
            font-weight: 500;
            border-radius: 8px;
            padding: .45rem 1rem;
            transition: all .15s;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
        }

        .btn-sm {
            padding: .3rem .7rem;
            font-size: .75rem;
            border-radius: 6px;
        }

        .btn-action {
            width: 30px; height: 30px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: .85rem;
        }

        /* ── Form ────────────────────────────────────────── */
        .form-label {
            font-size: .8rem;
            font-weight: 600;
            color: var(--gray-600);
            margin-bottom: .4rem;
        }

        .form-control, .form-select {
            font-size: .875rem;
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            padding: .55rem .9rem;
            color: var(--gray-800);
            background: #fff;
            transition: border-color .15s, box-shadow .15s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
            outline: none;
        }

        textarea.form-control { resize: vertical; min-height: 90px; }

        .invalid-feedback { font-size: .75rem; }

        /* ── Search Bar ──────────────────────────────────── */
        .search-wrap {
            position: relative;
        }

        .search-wrap .search-icon {
            position: absolute;
            left: .9rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: .9rem;
        }

        .search-wrap .form-control {
            padding-left: 2.4rem;
        }

        /* ── Alert ───────────────────────────────────────── */
        .alert {
            border: none;
            border-radius: 10px;
            font-size: .85rem;
            padding: .85rem 1.1rem;
        }

        /* ── Pagination ──────────────────────────────────── */
        .pagination { gap: .25rem; }
        .page-link {
            border-radius: 8px !important;
            border: 1px solid var(--gray-200);
            color: var(--gray-600);
            font-size: .8rem;
            padding: .4rem .7rem;
        }
        .page-item.active .page-link {
            background: var(--primary);
            border-color: var(--primary);
        }

        /* ── Sidebar Overlay (Mobile) ────────────────────── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.35);
            z-index: 1015;
        }

        /* ── Responsive ──────────────────────────────────── */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .sidebar-overlay.show { display: block; }
            .main-content { margin-left: 0; }
            .btn-sidebar-toggle { display: block; }
            .navbar-top .brand { width: auto; }
        }

        @media (max-width: 575.98px) {
            .main-content { padding: 1.25rem 1rem; }
            .stat-value { font-size: 1.5rem; }
        }
    </style>
</head>
<body>

<!-- ═══ Navbar ═══════════════════════════════════════════════════════════ -->
<header class="navbar-top">
    <a href="{{ route('dashboard') }}" class="brand">
        <div class="icon-wrap"><i class="bi bi-hospital"></i></div>
        <span>Klinik Sehat</span>
    </a>

    <button class="btn-sidebar-toggle" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <div class="nav-right">
        <div class="user-badge">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-box-arrow-right me-1"></i>Logout
            </button>
        </form>
    </div>
</header>

<!-- ═══ Sidebar Overlay ═══════════════════════════════════════════════════ -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ═══ Sidebar ══════════════════════════════════════════════════════════ -->
<aside class="sidebar" id="sidebar">
    <ul class="sidebar-nav">
        <li>
            <a href="{{ route('dashboard') }}"
               class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
        </li>
    </ul>

    <div class="sidebar-label">Manajemen</div>
    <ul class="sidebar-nav">
        <li>
            <a href="{{ route('patients.index') }}"
               class="{{ request()->routeIs('patients.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Data Pasien
            </a>
        </li>
        <li>
            <a href="{{ route('medical-records.index') }}"
               class="{{ request()->routeIs('medical-records.*') ? 'active' : '' }}">
                <i class="bi bi-file-medical"></i> Rekam Medis
            </a>
        </li>
        <li>
            <a href="{{ route('medicines.index') }}"
               class="{{ request()->routeIs('medicines.*') ? 'active' : '' }}">
                <i class="bi bi-capsule"></i> Obat
            </a>
        </li>
    </ul>
</aside>

<!-- ═══ Main Content ══════════════════════════════════════════════════════ -->
<main class="main-content">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</main>

{{-- Bootstrap 5 JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ── Sidebar Toggle (Mobile) ─────────────────────────────────────────
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sidebarOverlay');
    const toggle   = document.getElementById('sidebarToggle');

    toggle.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    });

    // ── SweetAlert untuk Flash Messages ────────────────────────────────
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 2500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
        });
    @endif

    // ── Konfirmasi Hapus dengan SweetAlert ─────────────────────────────
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = document.getElementById(this.dataset.form);
            Swal.fire({
                title: 'Hapus Data?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                borderRadius: '12px',
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
</script>

@yield('scripts')
</body>
</html>
