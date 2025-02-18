function showThankYouMessage(event) {
    event.preventDefault(); // Prevents the default form submission
    document.getElementById('contact-form').style.display = 'none'; // Hide all form fields
    document.getElementById('form-title').style.display = 'none';
    document.getElementById('thankYouMessage').style.display = 'block'; // Show the thank you message
}

function resetForm() {
    window.location.reload(); // Reload the page
}