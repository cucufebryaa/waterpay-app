<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Admin - Waterpay PAMS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="register-container d-flex align-items-center justify-content-center">
    <div class="register-box bg-white p-5 rounded-3 shadow">
        <h2 class="text-center mb-4">Daftar Akun Admin</h2>
        <form method="POST" action="{{ route('register.admin.store') }}" enctype="multipart/form-data">
            @csrf
            
            <!-- Upload Foto Profil -->
            <div class="mb-4 text-center">
                <label class="form-label d-block text-muted mb-2">Foto Profil (opsional)</label>
                <div class="profile-pic-div mx-auto">
                    <img src="https://via.placeholder.com/100/CCCCCC/FFFFFF?text=P" id="photo" alt="Profile Image" class="rounded-circle">
                    <input type="file" name="logo_pam" id="file" class="d-none" accept="image/*">
                    <label for="file" id="uploadBtn" class="btn btn-sm btn-secondary mt-2">Pilih Gambar</label>
                </div>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="Nama Lengkap" required>
            </div>
            
            <div class="mb-3">
                <label for="company_name" class="form-label">Nama Company</label>
                <input type="text" class="form-control form-control-sm" id="company_name" name="company_name" placeholder="Masukkan Nama Company" required>
            </div>

            <div class="mb-3">
                <label for="penanggung_jawab" class="form-label">Penanggung Jawab</label>
                <input type="text" class="form-control form-control-sm" id="penanggung_jawab" name="penanggung_jawab" placeholder="Penanggung Jawab" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control form-control-sm" id="email" name="email" placeholder="Email" required>
            </div>
            
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat Lengkap</label>
                <input type="text" class="form-control form-control-sm" id="alamat" name="alamat" placeholder="Alamat Lengkap" required>
            </div>
            
            <div class="mb-3">
                <label for="no_hp" class="form-label">No. HP</label>
                <input type="tel" class="form-control form-control-sm" id="no_hp" name="no_hp" placeholder="Nomor Handphone" required>
            </div>
            
            <div class="mb-3">
                <label for="nama_bank" class="form-label">Nama Bank</label>
                <select id="nama_bank" name="nama_bank" class="form-select form-select-sm" required>
                    <option selected disabled value="">Pilih Bank...</option>
                    <option value="BCA">BCA</option>
                    <option value="Mandiri">Mandiri</option>
                    <option value="BRI">BRI</option>
                    <option value="BNI">BNI</option>
                    <option value="CIMB Niaga">CIMB Niaga</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="no_rekening" class="form-label">No. Rekening</label>
                <input type="text" class="form-control form-control-sm" id="no_rekening" name="no_rekening" placeholder="No. Rekening" required>
            </div>
            
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control form-control-sm" id="username" name="username" placeholder="Username" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control form-control-sm" id="password" name="password" placeholder="Password" required>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control form-control-sm" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Daftar</button>
        </form>
        <div class="text-center mt-3">
            <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/script.js') }}"></script>
</body>
</html>