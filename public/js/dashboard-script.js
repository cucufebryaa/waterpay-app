document.addEventListener('DOMContentLoaded', function() {
    var el = document.getElementById("wrapper");
    var toggleButton = document.getElementById("menu-toggle");
    
    if (el && toggleButton) {
        toggleButton.addEventListener('click', function(e) {
            e.preventDefault();
            el.classList.toggle("toggled");
        });
    }

    // Fungsionalitas untuk menampilkan password
    const passwordButtons = document.querySelectorAll('.toggle-password-btn');
    passwordButtons.forEach(button => {
        button.addEventListener('click', function() {
            const password = this.dataset.password;
            const passwordField = this.previousElementSibling; // Ambil elemen <span> sebelumnya

            if (passwordField.textContent === '[terenkripsi]') {
                passwordField.textContent = password;
                this.querySelector('i').classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                passwordField.textContent = '[terenkripsi]';
                this.querySelector('i').classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    });
});