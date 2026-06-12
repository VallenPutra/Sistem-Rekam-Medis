@extends('layouts.guest')
@section('title', 'Login')

@section('content')
    <h5 class="auth-title">Masuk ke Akun</h5>
    <p class="auth-sub">Gunakan email dan password yang terdaftar</p>

    <form action="{{ route('login') }}" method="POST">
        @csrf

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label">Alamat Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="contoh@email.com" autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Masukkan password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Remember Me --}}
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" name="remember" id="remember">
            <label class="form-check-label" for="remember" style="font-size:.8rem;color:#475569">
                Ingat saya
            </label>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
        </button>
    </form>

    <div class="divider">atau</div>

    <p class="text-center mb-0" style="font-size:.82rem;color:#94a3b8">
        Belum punya akun?
        <a href="{{ route('register') }}" style="color:#2563eb;font-weight:600;text-decoration:none">
            Daftar di sini
        </a>
    </p>
@endsection
