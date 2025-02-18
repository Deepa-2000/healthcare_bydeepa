// Toggle New Password Field
const newPasswordInput = document.getElementById('newPassword');
const toggleNewPassword = document.getElementById('toggleNewPassword');
const toggleNewIcon = document.getElementById('toggleNewIcon');

toggleNewPassword.addEventListener('click', function () {
    const type = newPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    newPasswordInput.setAttribute('type', type);
    toggleNewIcon.classList.toggle('bi-eye');
    toggleNewIcon.classList.toggle('bi-eye-slash');
});

// Toggle Confirm Password Field
const confirmPasswordInput = document.getElementById('confirmPassword');
const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
const toggleConfirmIcon = document.getElementById('toggleConfirmIcon');

toggleConfirmPassword.addEventListener('click', function () {
    const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    confirmPasswordInput.setAttribute('type', type);
    toggleConfirmIcon.classList.toggle('bi-eye');
    toggleConfirmIcon.classList.toggle('bi-eye-slash');
});

// Validation on Form Submit
const form = document.getElementById('passwordForm');
const newPasswordError = document.getElementById('newPasswordError');
const confirmPasswordError = document.getElementById('confirmPasswordError');

form.addEventListener('submit', function (event) {
    // Prevent form submission to validate
    event.preventDefault();

    let isValid = true;

    // Check if New Password is at least 8 characters long
    if (newPasswordInput.value.length < 8) {
        newPasswordError.style.display = 'block';
        isValid = false;
    } else {
        newPasswordError.style.display = 'none';
    }

    // Check if both passwords match
    if (newPasswordInput.value !== confirmPasswordInput.value) {
        confirmPasswordError.style.display = 'block';
        isValid = false;
    } else {
        confirmPasswordError.style.display = 'none';
    }

    // Submit form if all validations pass
    if (isValid) {
        form.submit();
    }
});