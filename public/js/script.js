// Tambahkan efek fade-in sederhana pada elemen saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    const heroImage = document.querySelector('.hero-image-container img');
    if (heroImage) {
        // Efek animasi sudah diatur di CSS dengan keyframe
        // JavaScript hanya diperlukan untuk interaksi yang lebih kompleks
    }

    // Contoh efek untuk tombol
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', () => {
            btn.style.transform = 'translateY(-2px) scale(1.02)';
        });
        btn.addEventListener('mouseleave', () => {
            btn.style.transform = 'translateY(0) scale(1)';
        });
    });

    const togglePassword = document.querySelector('.toggle-password');
    if (togglePassword) {
        togglePassword.addEventListener('click', function (e) {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });
    }
    // Fungsionalitas untuk pratinjau foto
    const photo = document.querySelector('#photo');
    const file = document.querySelector('#file');
    
    if (photo && file) {
        file.addEventListener('change', function() {
            const chosenFile = this.files[0];
            if (chosenFile) {
                const reader = new FileReader();
                reader.addEventListener('load', function() {
                    photo.setAttribute('src', reader.result);
                });
                reader.readAsDataURL(chosenFile);
            }
        });
    }
});