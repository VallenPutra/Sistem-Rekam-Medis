<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Klinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3">
                <div class="card-body">
                    <h5 class="fw-bold mb-1">Lupa Password?</h5>
                    <p class="text-muted small mb-4">Masukkan email terdaftar. Kami akan mengirimkan link reset password ke Gmail kamu.</p>

                    @if(session('success'))
                        <div class="alert alert-success small">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger small">
                            @foreach($errors->all() as $e)
                                <div>{{ $e }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Alamat Email</label>
                            <input type="email" name="email" class="form-control" placeholder="nama@gmail.com" required value="{{ old('email') }}">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mb-2">Kirim Link Reset</button>
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-decoration-none small text-secondary">Kembali ke Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>