<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Waterpay PAMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="register-container d-flex align-items-center justify-content-center">
    <div class="register-box bg-white p-5 rounded-3 shadow">
        <h2 class="text-center mb-4">Daftar Akun Admin</h2>
        <form method="POST" action="{{ route('register.admin') }}">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nama Lengkap" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Alamat</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            </div>
            
            <div class="mb-3">
                <label for="alamat" class="form-label">Email</label>
                <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat Lengkap" required>
            </div>
            
            <div class="mb-3">
                <label for="no_hp" class="form-label">No_HP</label>
                <input type="tel" class="form-control" id="no_hp" name="no_hp" placeholder="Nomor Handphone" required>
            </div>
            
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Daftar</button>
        </form>
        <div class="text-center mt-3">
            <p>Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>