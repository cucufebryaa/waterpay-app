@extends('layouts.guest')

@section('title', 'Selamat Datang di Waterpay PAMS')

@section('content')
{{-- HERO SECTION (Sudah ada sebelumnya) --}}
<div class="main-content">
    <div class="container-custom">
        <div class="row align-items-center">
            {{-- Konten hero section Anda di sini... --}}
            <div class="col-md-7 hero-text">
                <h1 class="display-3 fw-bold mb-3">
                    Kelola Tagihan Air Jadi Lebih Mudah
                </h1>
                <p class="lead mb-4">
                    Sistem manajemen dan pembayaran tagihan air bersih yang modern, cepat, dan terintegrasi untuk PAMS Anda.
                </p>
                <div class="d-flex flex-column flex-sm-row">
                    <a href="{{ route('register.admin') }}" class="btn btn-primary btn-lg me-sm-3 mb-3 mb-sm-0">Mulai Sekarang</a>
                    <a href="#tentang" class="btn btn-outline-secondary btn-lg">About U</a>
                </div>
            </div>
            <div class="col-md-5 d-none d-md-block">
                <div class="hero-image-container">
                </div>
            </div>
        </div>
    </div>
</div>
{{-- AKHIR HERO SECTION --}}


{{-- =============================================== --}}
{{-- MULAI SECTION TENTANG KAMI                    --}}
{{-- =============================================== --}}
<section id="tentang" class="section bg-light-gray">
    <div class="container-custom">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h2 class="section-title">Waterpay App – Solusi Digital untuk Manajemen Perusahaan Air PAM</h2>
                <p class="section-subtitle">
                    Kelola operasional perusahaan air PAM Anda dengan lebih mudah, cepat, dan efisien.
                </p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <p class="text-center">
                    Waterpay App adalah platform berbasis cloud yang dirancang khusus untuk mendukung perusahaan penyedia layanan air PAM dalam mengelola pelanggan, pembayaran, petugas lapangan, hingga laporan keuangan dalam satu dashboard terpadu.
                </p>

                <h4 class="about-subheading">✨ Mengapa Memilih Waterpay App?</h4>
                <ul class="feature-list">
                    <li><span class="emoji">🔹</span> <strong>Manajemen Pelanggan Terpusat:</strong> Simpan dan kelola seluruh data pelanggan air PAM secara rapi, aman, dan mudah diakses kapan saja.</li>
                    <li><span class="emoji">🔹</span> <strong>Pembayaran Tagihan Lebih Praktis:</strong> Sediakan pilihan pembayaran digital yang aman dan transparan untuk pelanggan. Tagihan bisa diselesaikan tanpa harus datang langsung.</li>
                    <li><span class="emoji">🔹</span> <strong>Monitoring Petugas Lapangan:</strong> Atur jadwal, lacak kinerja, dan pantau aktivitas petugas lapangan dengan lebih terstruktur melalui dashboard.</li>
                    <li><span class="emoji">🔹</span> <strong>Laporan Keuangan & Operasional Real-Time:</strong> Dapatkan laporan detail mengenai tagihan, pendapatan, dan performa perusahaan secara instan.</li>
                </ul>

                <h4 class="about-subheading">💼 Dibuat Khusus untuk Perusahaan Air PAM</h4>
                <p>
                    Waterpay App terinspirasi dari sistem manajemen modern seperti Talenta, namun fokus pada kebutuhan operasional perusahaan air PAM. Setiap perusahaan yang berlangganan akan mendapatkan dashboard eksklusif dengan fitur manajemen lengkap sesuai kebutuhan mereka.
                </p>
                
                <h4 class="about-subheading">🚀 Keunggulan Waterpay App</h4>
                <ul class="feature-list">
                    <li><span class="emoji">✔️</span> Sistem berbasis cloud: akses di mana saja, kapan saja.</li>
                    <li><span class="emoji">✔️</span> Desain modern dan mudah digunakan oleh semua level pengguna.</li>
                    <li><span class="emoji">✔️</span> Dukungan penuh untuk transformasi digital perusahaan air PAM.</li>
                    <li><span class="emoji">✔️</span> Efisiensi tinggi: hemat waktu, tenaga, dan biaya operasional.</li>
                </ul>
                
                <div class="text-center mt-5">
                    <h4 class="fw-bold">🌊 Transformasi Digital Perusahaan Air PAM Dimulai di Sini</h4>
                    <p class="text-muted">Bergabunglah dengan perusahaan air PAM yang sudah mempercayai Waterpay App sebagai solusi manajemen modern mereka.</p>
                    <a href="{{ route('register.admin') }}" class="btn btn-primary btn-lg mt-3">
                        👉 Daftarkan Perusahaan Anda
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- =============================================== --}}
{{-- AKHIR SECTION TENTANG KAMI                      --}}
{{-- =============================================== --}}


{{-- =============================================== --}}
{{-- MULAI SECTION KONTAK                          --}}
{{-- =============================================== --}}
<section id="kontak" class="section">
    <div class="container-custom">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="section-title">Hubungi Kami</h2>
                <p class="section-subtitle">
                    Punya pertanyaan atau butuh informasi lebih lanjut? Silakan isi form di bawah ini atau hubungi kami langsung melalui WhatsApp.
                </p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-7">
                {{-- Form Kontak --}}
                <form action="#" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="address" name="address">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Pesan</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Kirim Pesan</button>
                    </div>
                </form>

                {{-- Pemisah atau Tombol WhatsApp --}}
                <div class="text-center my-4">
                    <span class="text-muted">ATAU</span>
                </div>

                {{-- Tombol WhatsApp --}}
                <div class="d-grid">
                    {{-- PENTING: Ganti 6281234567890 dengan nomor WhatsApp Admin Anda --}}
                    <a href="https://wa.me/6281234567890" class="btn btn-whatsapp btn-lg" target="_blank">
                        <i class="bi bi-whatsapp me-2"></i> 
                        Tekan untuk menghubungi admin
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- =============================================== --}}
{{-- AKHIR SECTION KONTAK                          --}}
{{-- =============================================== --}}

@endsection