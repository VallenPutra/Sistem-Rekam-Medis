{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rekam Medis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh">
    <div class="card shadow" style="width:400px">
        <div class="card-header bg-primary text-white text-center py-3">
            <h5 class="mb-0"> Aplikasi Rekam Medis</h5>
        </div>
        <div class="card-body p-4">
            <h6 class="mb-3">Masuk ke Sistem</h6>

            {{-- Menampilkan Notifikasi Sukses Setelah Berhasil Ganti Password --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                {{-- PERBAIKAN: Checkbox 'Remember' dihapus, digantikan link Lupa Password yang rapi di kanan bawah --}}
                <div class="mb-4 clearfix">
                    <a href="{{ route('password.request') }}" class="text-decoration-none small float-end">Lupa Password?</a>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            @if(!App\Models\User::where('role','admin')->exists())
                <hr>
                <p class="text-center text-muted small">Belum ada akun admin?
                    <a href="{{ route('register') }}">Daftar sekarang</a>
                </p>
            @endif
        </div>
    </div>
</body>
</html>