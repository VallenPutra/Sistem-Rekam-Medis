@extends('layouts.guest')
@section('title', 'Daftar')

@section('content')
    <h5 class="auth-title">Buat Akun Baru</h5>
    <p class="auth-sub">Isi data di bawah untuk mendaftar</p>

    <form action="{{ route('register') }}" method="POST">
        @csrf

        {{-- Nama --}}
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="form-control @error('name') is-invalid @enderror"
                   placeholder="Nama lengkap Anda" autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label">Alamat Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="contoh@email.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Minimal 6 karakter">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation"
                   class="form-control"
                   placeholder="Ulangi password">
        </div>

        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-person-plus me-1"></i> Daftar Sekarang
        </button>
    </form>

    <div class="divider">atau</div>

    <p class="text-center mb-0" style="font-size:.82rem;color:#94a3b8">
        Sudah punya akun?
        <a href="{{ route('login') }}" style="color:#2563eb;font-weight:600;text-decoration:none">
            Masuk di sini
        </a>
    </p>
@endsection
