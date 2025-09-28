document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    if (togglePassword && password) {
        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // toggle the icon
            const icon = this.querySelector('i');
            if (type === 'password') {
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    }

    const registrationForm = document.querySelector('#registrationForm');
    if (registrationForm) {
        const nextButtons = registrationForm.querySelectorAll('.btn-next');
        const prevButtons = registrationForm.querySelectorAll('.btn-prev');
        const formSteps = registrationForm.querySelectorAll('.form-step');
        const progressSteps = document.querySelectorAll('.progress-step');
        let currentStep = 1;

        nextButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Validasi input sebelum lanjut
                if (validateStep(currentStep)) {
                    currentStep++;
                    updateFormSteps();
                    updateProgressBar();
                }
            });
        });

        prevButtons.forEach(button => {
            button.addEventListener('click', () => {
                currentStep--;
                updateFormSteps();
                updateProgressBar();
            });
        });

        function updateFormSteps() {
            formSteps.forEach(step => {
                step.classList.remove('active');
                if (parseInt(step.dataset.step) === currentStep) {
                    step.classList.add('active');
                }
            });
        }
        
        function updateProgressBar() {
            progressSteps.forEach((step, index) => {
                if (index < currentStep) {
                    step.classList.add('active');
                } else {
                    step.classList.remove('active');
                }
            });
        }

        function validateStep(stepNumber) {
            const currentFormStep = registrationForm.querySelector(`.form-step[data-step="${stepNumber}"]`);
            const inputs = currentFormStep.querySelectorAll('input[required], select[required]');
            let isValid = true;
            inputs.forEach(input => {
                if (!input.value) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            return isValid;
        }
        
        // Menghapus 'is-invalid' saat pengguna mulai mengetik
        registrationForm.querySelectorAll('input[required], select[required]').forEach(input => {
            input.addEventListener('input', () => {
                if(input.classList.contains('is-invalid')) {
                    input.classList.remove('is-invalid');
                }
            });
        });
    }
});

