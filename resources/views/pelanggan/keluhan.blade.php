@extends('layouts.app')

@section('title', 'Formulir Pengajuan Keluhan')

@section('content')

<style>
    /* Page Header */
    .complaint-header {
        padding: 0 0 1rem;
        border-bottom: 1px solid #e3e6f0;
        margin-bottom: 1.5rem;
    }

    .complaint-header h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .complaint-header h1 i {
        font-size: 1.5rem;
        color: #3b82f6;
    }

    .complaint-header p {
        color: #6c757d;
        font-size: 0.95rem;
        margin: 0;
    }

    /* Form Container */
    .form-container {
        width: 100%;
        max-width: 100%;
    }

    .modern-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        border: 1px solid #e8ecf1;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .card-section {
        padding: 2rem;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #2c3e50;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e8ecf1;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title i {
        font-size: 1.1rem;
        color: #3b82f6;
    }

    /* Info Box */
    .info-box {
        background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
        border: 2px solid #bfdbfe;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: start;
        gap: 1rem;
    }

    .info-box i {
        font-size: 1.5rem;
        color: #3b82f6;
        flex-shrink: 0;
        margin-top: 0.125rem;
    }

    .info-box-content {
        flex: 1;
    }

    .info-box-content h6 {
        margin: 0 0 0.375rem 0;
        font-weight: 700;
        color: #1e40af;
        font-size: 0.95rem;
    }

    .info-box-content p {
        margin: 0;
        font-size: 0.85rem;
        color: #64748b;
        line-height: 1.5;
    }

    /* Form Elements */
    .form-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label i {
        font-size: 0.9rem;
        color: #3b82f6;
    }

    .form-control {
        border: 2px solid #e8ecf1;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        background: white;
    }

    .form-control:disabled,
    .form-control[readonly] {
        background: #f8f9fc;
        color: #64748b;
        cursor: not-allowed;
    }

    .form-control.is-invalid {
        border-color: #ef4444;
    }

    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    .invalid-feedback {
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
        color: #ef4444;
    }

    .invalid-feedback::before {
        content: '⚠';
        font-size: 1rem;
    }

    /* Textarea */
    textarea.form-control {
        resize: vertical;
        min-height: 150px;
    }

    /* Required Field Indicator */
    .required-field::after {
        content: '*';
        color: #ef4444;
        margin-left: 0.25rem;
        font-weight: 700;
    }

    /* Character Counter */
    .char-counter {
        font-size: 0.8rem;
        color: #9ca3af;
        text-align: right;
        margin-top: 0.375rem;
    }

    .char-counter.limit-approaching {
        color: #f59e0b;
    }

    .char-counter.limit-reached {
        color: #ef4444;
        font-weight: 600;
    }

    /* Buttons */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding-top: 2rem;
        border-top: 2px solid #e8ecf1;
        margin-top: 2rem;
    }

    .btn {
        padding: 0.875rem 2.5rem;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 700;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn i {
        font-size: 1.1rem;
    }

    .btn-submit {
        background: linear-gradient(135deg, #3b82f6, #06b6d4);
        color: white;
        border: none;
        min-width: 200px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
        color: white;
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* Footer Note */
    .form-footer {
        text-align: center;
        padding-top: 1.5rem;
        border-top: 1px solid #e8ecf1;
        margin-top: 1.5rem;
    }

    .form-footer p {
        font-size: 0.85rem;
        color: #9ca3af;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .form-footer i {
        color: #10b981;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .complaint-header {
            padding: 0 0 0.75rem;
        }

        .complaint-header h1 {
            font-size: 1.5rem;
        }

        .card-section {
            padding: 1.5rem;
        }

        .btn {
            width: 100%;
            min-width: auto;
        }

        .info-box {
            padding: 1rem;
        }
    }

    @media (max-width: 576px) {
        .card-section {
            padding: 1.25rem;
        }

        .section-title {
            font-size: 0.9rem;
        }

        .complaint-header h1 {
            font-size: 1.35rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            font-size: 0.85rem;
        }
    }
</style>

<!-- Page Header -->
<div class="complaint-header">
    <h1>
        <i class="bi bi-file-earmark-text"></i>
        Formulir Pengajuan Keluhan
    </h1>
    <p>Sampaikan keluhan Anda, kami akan segera menindaklanjuti</p>
</div>

<div class="form-container">
    <!-- Form Card -->
    <div class="modern-card">
        <div class="card-section">
            <!-- Info Box -->
            <div class="info-box">
                <i class="bi bi-info-circle-fill"></i>
                <div class="info-box-content">
                    <h6>Informasi Otomatis</h6>
                    <p>Data identitas Anda telah terisi secara otomatis oleh sistem. Silakan isi deskripsi keluhan Anda dengan jelas dan detail.</p>
                </div>
            </div>

            <form action="{{ route('pelanggan.transaction.store') }}" method="POST" id="complaintForm">
                @csrf
                
                <!-- User Information Section -->
                <div class="section-title">
                    <i class="bi bi-person-badge"></i>
                    Identitas Pelapor
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="username" class="form-label">
                            <i class="bi bi-person"></i>
                            Username / Nama
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="username" 
                               name="username" 
                               value="{{ $user->username ?? $user->name }}" 
                               readonly>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope"></i>
                            Email Address
                        </label>
                        <input type="email" 
                               class="form-control" 
                               id="email" 
                               name="email" 
                               value="{{ $user->email }}" 
                               readonly>
                    </div>

                    <div class="col-12">
                        <label for="company_name" class="form-label">
                            <i class="bi bi-building"></i>
                            Nama Perusahaan
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="company_name" 
                               name="company_name" 
                               value="{{ $company_name }}" 
                               readonly>
                    </div>
                </div>

                <!-- Complaint Description Section -->
                <div class="section-title">
                    <i class="bi bi-chat-left-text"></i>
                    Detail Keluhan
                </div>

                <div class="row g-4">
                    <div class="col-12">
                        <label for="isi_keluhan" class="form-label required-field">
                            <i class="bi bi-pencil-square"></i>
                            Deskripsi Keluhan
                        </label>
                        <textarea class="form-control @error('isi_keluhan') is-invalid @enderror" 
                                  id="isi_keluhan" 
                                  name="isi_keluhan" 
                                  rows="6"
                                  placeholder="Jelaskan keluhan Anda dengan detail. Contoh: masalah pada instalasi air, tagihan tidak sesuai, layanan customer service, dll."
                                  maxlength="1000"
                                  required>{{ old('isi_keluhan') }}</textarea>
                        <div class="char-counter" id="charCounter">0 / 1000 karakter</div>
                        @error('isi_keluhan')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-submit" id="submitBtn">
                        <i class="bi bi-send-fill"></i>
                        <span>Kirim Keluhan</span>
                    </button>
                </div>
            </form>

            <!-- Footer Note -->
            <div class="form-footer">
                <p>
                    <i class="bi bi-shield-check"></i>
                    Data Anda aman dan terenkripsi
                    <span class="mx-2">•</span>
                    © {{ date('Y') }} WaterPay System
                </p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('isi_keluhan');
        const charCounter = document.getElementById('charCounter');
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('complaintForm');

        // Character counter
        if (textarea && charCounter) {
            function updateCounter() {
                const length = textarea.value.length;
                const maxLength = 1000;
                charCounter.textContent = `${length} / ${maxLength} karakter`;

                // Color coding
                charCounter.classList.remove('limit-approaching', 'limit-reached');
                if (length >= maxLength) {
                    charCounter.classList.add('limit-reached');
                } else if (length >= maxLength * 0.9) {
                    charCounter.classList.add('limit-approaching');
                }
            }

            textarea.addEventListener('input', updateCounter);
            updateCounter(); // Initial call
        }

        // Form validation
        if (form) {
            form.addEventListener('submit', function(e) {
                const complaint = textarea.value.trim();

                if (complaint.length < 10) {
                    e.preventDefault();
                    textarea.classList.add('is-invalid');
                    
                    // Create or update error message
                    let errorMsg = textarea.nextElementSibling;
                    if (!errorMsg || !errorMsg.classList.contains('invalid-feedback')) {
                        errorMsg = document.createElement('div');
                        errorMsg.className = 'invalid-feedback d-block';
                        textarea.parentNode.insertBefore(errorMsg, charCounter);
                    }
                    errorMsg.textContent = 'Deskripsi keluhan minimal 10 karakter';
                    
                    textarea.focus();
                    return false;
                }

                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengirim...';
            });

            // Remove invalid class on input
            textarea.addEventListener('input', function() {
                if (this.value.trim().length >= 10) {
                    this.classList.remove('is-invalid');
                    const errorMsg = this.nextElementSibling;
                    if (errorMsg && errorMsg.classList.contains('invalid-feedback')) {
                        errorMsg.remove();
                    }
                }
            });
        }

        // Auto-expand textarea
        if (textarea) {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 400) + 'px';
            });
        }
    });
</script>
@endpush