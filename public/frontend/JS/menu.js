// Wait until the DOM is fully loaded
    document.addEventListener("DOMContentLoaded", function () {
        // Get all navigation links
        const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

        // Add a click event listener to each link
        navLinks.forEach(link => {
            link.addEventListener('click', function () {
                // Remove the 'active' class from all links
                navLinks.forEach(nav => nav.classList.remove('active'));

                // Add the 'active' class to the clicked link
                this.classList.add('active');
            });
        });
    });
    
    document.addEventListener("DOMContentLoaded", function () {
        const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
        const currentPath = window.location.pathname;

        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    });