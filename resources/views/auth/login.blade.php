<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Portal Berita Admin</title>
    @vite(['resources/js/app.js'])
    <style>
        body { min-height: 100vh; background: linear-gradient(135deg, #151923, #d60000); display: grid; place-items: center; }
        .login-card { width: min(92vw, 430px); border: 0; border-radius: 1.25rem; box-shadow: 0 24px 80px rgba(0,0,0,.24); }
    </style>
</head>
<body>
    <div class="card login-card">
        <div class="card-body p-4 p-md-5">
            <img src="{{ asset('images/logo.svg') }}" alt="PortalBerita" class="mb-4" style="max-width: 220px;">
            <h1 class="h3 mb-2">Login Admin</h1>
            <p class="text-muted mb-4">Masuk untuk mengelola berita dan kategori.</p>

            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Password</label>
                    <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-check mb-4">
                    <input id="remember" name="remember" type="checkbox" class="form-check-input">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button class="btn btn-danger w-100 py-2" type="submit">Login</button>
            </form>
            <p class="text-muted small mt-4 mb-0">Default seeder: admin@portalberita.test / password</p>
        </div>
    </div>
</body>
</html>
