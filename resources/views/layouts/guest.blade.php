<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') — Klinik Sehat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 4px 20px rgba(0,0,0,.06);
        }

        .auth-brand {
            text-align: center;
            margin-bottom: 1.75rem;
        }

        .brand-icon {
            width: 52px; height: 52px;
            background: #2563eb;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.5rem;
            margin-bottom: .75rem;
        }

        .brand-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .brand-sub {
            font-size: .78rem;
            color: #94a3b8;
            margin: .2rem 0 0;
        }

        .auth-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: .25rem;
        }

        .auth-sub {
            font-size: .8rem;
            color: #94a3b8;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-size: .8rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: .4rem;
        }

        .form-control {
            font-size: .875rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: .6rem .9rem;
            color: #1e293b;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        }

        .btn-primary {
            background: #2563eb;
            border-color: #2563eb;
            font-size: .875rem;
            font-weight: 600;
            border-radius: 8px;
            padding: .65rem;
        }

        .btn-primary:hover { background: #1d4ed8; border-color: #1d4ed8; }

        .invalid-feedback { font-size: .75rem; }
        .alert { font-size: .82rem; border-radius: 8px; border: none; }

        .divider {
            text-align: center;
            margin: 1.25rem 0;
            color: #94a3b8;
            font-size: .75rem;
            position: relative;
        }

        .divider::before, .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 42%;
            height: 1px;
            background: #e2e8f0;
        }

        .divider::before { left: 0; }
        .divider::after  { right: 0; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-brand">
            <div class="brand-icon"><i class="bi bi-hospital"></i></div>
            <p class="brand-name">Klinik Sehat</p>
            <p class="brand-sub">Sistem Rekam Medis</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
