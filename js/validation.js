// Client-side form validation
document.addEventListener('DOMContentLoaded', function () {

    // Register form
    const regForm = document.getElementById('regForm');
    if (regForm) {
        regForm.addEventListener('submit', e => {
            let valid = true;
            regForm.querySelectorAll('[required]').forEach(input => {
                const err = input.nextElementSibling;
                if (!input.checkValidity()) {
                    if (err) err.textContent = input.validationMessage;
                    input.classList.add('is-invalid');
                    valid = false;
                } else {
                    if (err) err.textContent = '';
                    input.classList.remove('is-invalid');
                }
            });
            if (!valid) e.preventDefault();
        });
    }

    // Login form
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', e => {
            let valid = true;
            loginForm.querySelectorAll('[required]').forEach(input => {
                const err = input.nextElementSibling;
                if (!input.checkValidity()) {
                    if (err) err.textContent = input.validationMessage;
                    input.classList.add('is-invalid');
                    valid = false;
                } else {
                    if (err) err.textContent = '';
                    input.classList.remove('is-invalid');
                }
            });
            if (!valid) e.preventDefault();
        });
    }

});