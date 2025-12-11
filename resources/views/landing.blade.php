@extends('layouts.guest')

@section('title', 'Selamat Datang di Waterpay PAMS')

@section('content')

<style>
    /* --- MODERN DESIGN SYSTEM VARIABLES --- */
    :root {
        --primary-gradient: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        --secondary-bg: #f8fafc;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    body {
        font-family: 'Nunito', sans-serif;
        background-color: var(--secondary-bg);
        color: var(--text-dark);
    }

    /* --- HERO SECTION --- */
    .hero-section {
        background: white;
        padding: 6rem 0 5rem;
        position: relative;
        overflow: hidden;
    }
    /* Background Decoration */
    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 60%; height: 150%;
        background: radial-gradient(circle, rgba(59,130,246,0.05) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        z-index: 0;
    }
    .hero-content { position: relative; z-index: 1; }
    
    .hero-badge {
        background: rgba(59, 130, 246, 0.1);
        color: #2563eb;
        padding: 0.5rem 1rem;
        border-radius: 50rem;
        font-weight: 700;
        font-size: 0.85rem;
        display: inline-flex; align-items: center; gap: 0.5rem;
        margin-bottom: 1.5rem;
    }
    
    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -1px;
        background: -webkit-linear-gradient(315deg, #1e293b 0%, #3b82f6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 1.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.25rem;
        color: var(--text-muted);
        margin-bottom: 2.5rem;
        line-height: 1.6;
        font-weight: 400;
    }

    .btn-gradient {
        background: var(--primary-gradient);
        color: white;
        border: none;
        padding: 1rem 2.5rem;
        border-radius: 50rem;
        font-weight: 700;
        font-size: 1rem;
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.25);
        transition: all 0.3s ease;
    }
    .btn-gradient:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(59, 130, 246, 0.35);
        color: white;
    }
    
    .btn-outline-modern {
        background: white;
        color: var(--text-dark);
        border: 2px solid #e2e8f0;
        padding: 1rem 2.5rem;
        border-radius: 50rem;
        font-weight: 700;
        transition: all 0.3s ease;
    }
    .btn-outline-modern:hover {
        border-color: #3b82f6;
        color: #3b82f6;
        background: #f8fafc;
    }

    /* --- FEATURES SECTION --- */
    .section-padding { padding: 6rem 0; }
    .section-header { text-align: center; margin-bottom: 4rem; max-width: 800px; margin-left: auto; margin-right: auto; }
    .section-title { font-size: 2.5rem; font-weight: 800; color: var(--text-dark); margin-bottom: 1rem; }
    .section-desc { font-size: 1.1rem; color: var(--text-muted); }

    .feature-card {
        background: white;
        padding: 2.5rem;
        border-radius: 1.5rem;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        transition: all 0.3s ease;
        height: 100%;
    }
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        border-color: rgba(59, 130, 246, 0.2);
    }
    .feature-icon-box {
        width: 70px; height: 70px;
        border-radius: 1.2rem;
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem;
        margin-bottom: 1.5rem;
    }
    .feature-card h5 { font-weight: 700; font-size: 1.25rem; margin-bottom: 1rem; color: var(--text-dark); }
    .feature-card p { color: var(--text-muted); line-height: 1.6; margin: 0; }

    /* --- CTA SECTION --- */
    .cta-box {
        background: white;
        border-radius: 2rem;
        padding: 4rem;
        box-shadow: 0 20px 60px rgba(0,0,0,0.05);
    }
    .check-list-item { display: flex; align-items: start; gap: 1rem; margin-bottom: 1.5rem; }
    .check-icon {
        background: #dcfce7; color: #166534;
        padding: 0.25rem; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
    }

    /* --- CONTACT FORM --- */
    .contact-card {
        background: white;
        border-radius: 2rem;
        padding: 3.5rem;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
    }
    .form-control-modern {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1rem 1.25rem;
        font-size: 1rem;
        transition: all 0.3s;
    }
    .form-control-modern:focus {
        background: white;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        outline: none;
    }
    .btn-whatsapp {
        background: #25D366;
        color: white;
        border: none;
        border-radius: 1rem;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.3s;
        box-shadow: 0 10px 20px rgba(37, 211, 102, 0.2);
    }
    .btn-whatsapp:hover {
        background: #128C7E;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(37, 211, 102, 0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-title { font-size: 2.5rem; }
        .hero-section { padding: 4rem 0; }
        .cta-box, .contact-card { padding: 2rem; }
    }
</style>

<!-- HERO SECTION -->
<section class="hero-section">
    <div class="container hero-content">
        <div class="row align-items-center">
            <div class="col-lg-6 order-2 order-lg-1">
                <div class="hero-badge">
                    <i class="bi bi-stars"></i> Platform Manajemen Air #1
                </div>
                <h1 class="hero-title">
                    Kelola Tagihan Air <br>Jadi Lebih Mudah
                </h1>
                <p class="hero-subtitle">
                    Sistem manajemen dan pembayaran tagihan air bersih yang modern, cepat, dan terintegrasi Payment Gateway untuk PAMS Anda.
                </p>
                <div class="d-flex flex-column flex-sm-row gap-3">
                    <a href="{{ route('register.admin') }}" class="btn btn-gradient">
                        <i class="bi bi-rocket-takeoff-fill me-2"></i> Mulai Sekarang
                    </a>
                    <a href="#tentang" class="btn btn-outline-modern">
                        Pelajari Fitur
                    </a>
                </div>
                
                <div class="mt-5 d-flex gap-4 text-muted fw-bold small text-uppercase ls-1">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-primary"></i> Setup Instan
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-primary"></i> Aman
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-primary"></i> 24/7 Support
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-1 order-lg-2 mb-5 mb-lg-0 text-center">
                <!-- Gunakan Ilustrasi 3D/Modern -->
                <!-- IMAGE REPLACEMENT: Link sebelumnya broken, diganti placeholder aman -->
                <img src={{ asset('images/logo.jpg') }} alt="Waterpay App" class="img-fluid" style="max-width: 90%; filter: drop-shadow(0 20px 40px rgba(0,0,0,0.1)); border-radius: 20px;">
            </div>
        </div>
    </div>
</section>

<!-- FEATURES SECTION -->
<section id="tentang" class="section-padding" style="background: #f8fafc;">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Solusi Digital Terpadu</h2>
            <p class="section-desc">
                Waterpay App hadir untuk mengubah cara manual pengelolaan air PAM menjadi sistem digital yang efisien dan transparan.
            </p>
        </div>

        <div class="row g-4">
            <!-- Feature 1 -->
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon-box">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h5>Manajemen Pelanggan</h5>
                    <p>Database pelanggan terpusat dengan riwayat pemakaian yang tercatat rapi dan mudah diakses kapan saja.</p>
                </div>
            </div>
            <!-- Feature 2 -->
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon-box">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <h5>Pembayaran Digital</h5>
                    <p>Integrasi Xendit untuk pembayaran via Virtual Account & E-Wallet. Tagihan lunas otomatis.</p>
                </div>
            </div>
            <!-- Feature 3 -->
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon-box">
                        <i class="bi bi-whatsapp"></i>
                    </div>
                    <h5>Notifikasi WhatsApp</h5>
                    <p>Kirim tagihan dan pengingat jatuh tempo ke WhatsApp pelanggan secara otomatis (Auto-Blast).</p>
                </div>
            </div>
            <!-- Feature 4 -->
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon-box">
                        <i class="bi bi-bar-chart-fill"></i>
                    </div>
                    <h5>Laporan Real-time</h5>
                    <p>Pantau pendapatan, tunggakan, dan kinerja operasional perusahaan melalui dashboard interaktif.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA / ABOUT SECTION -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="cta-box">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <!-- IMAGE REPLACEMENT: Link sebelumnya broken, diganti placeholder aman -->
                    <img src={{ asset('images/logo.jpg') }} alt="Analytics" class="img-fluid rounded-4">
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <div class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill fw-bold">KHUSUS PAMS</div>
                    <h2 class="section-title mb-4">Mengapa Beralih ke Waterpay?</h2>
                    <p class="text-muted mb-4">
                        Kami mengerti tantangan operasional Anda. Waterpay dirancang khusus untuk memangkas birokrasi dan meningkatkan cashflow perusahaan air.
                    </p>
                    
                    <div class="check-list-item">
                        <div class="check-icon"><i class="bi bi-check-lg"></i></div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Akses Cloud 24/7</h6>
                            <p class="text-muted small m-0">Kelola data dari mana saja tanpa perlu install server fisik.</p>
                        </div>
                    </div>
                    <div class="check-list-item">
                        <div class="check-icon"><i class="bi bi-check-lg"></i></div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Modern Dashboard</h6>
                            <p class="text-muted small m-0">Desain antarmuka yang intuitif dan mudah digunakan oleh semua kalangan.</p>
                        </div>
                    </div>
                    
                    <div class="mt-5">
                        <a href="{{ route('register.admin') }}" class="btn btn-gradient w-100 w-sm-auto text-center">
                            Daftarkan Perusahaan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CONTACT SECTION -->
<section id="kontak" class="section-padding" style="background: #f1f5f9;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="contact-card">
                    <div class="text-center mb-5">
                        <h2 class="fw-bold mb-2">Hubungi Kami</h2>
                        <p class="text-muted">Punya pertanyaan teknis atau butuh demo? Kami siap membantu.</p>
                    </div>

                    <form action="#" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="fw-bold text-dark small mb-2">Nama Lengkap</label>
                                <input type="text" class="form-control form-control-modern" name="name" placeholder="Nama Anda" required>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-bold text-dark small mb-2">Email Perusahaan</label>
                                <input type="email" class="form-control form-control-modern" name="email" placeholder="name@company.com" required>
                            </div>
                            <div class="col-12">
                                <label class="fw-bold text-dark small mb-2">Pesan Anda</label>
                                <textarea class="form-control form-control-modern" name="message" rows="4" placeholder="Tuliskan pertanyaan Anda..." required></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-dark w-100 py-3 rounded-pill fw-bold">
                                    <i class="bi bi-send-fill me-2"></i> Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="text-center my-4 position-relative">
                        <hr class="opacity-25">
                        <span class="bg-white px-3 text-muted small position-absolute top-50 start-50 translate-middle">ATAU</span>
                    </div>

                    <div class="d-grid">
                        <a href="https://wa.me/6281226212203" class="btn btn-whatsapp py-3" target="_blank">
                            <i class="bi bi-whatsapp me-2 fs-5 align-middle"></i> 
                            Chat Admin via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection