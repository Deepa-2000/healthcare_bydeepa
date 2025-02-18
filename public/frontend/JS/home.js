
$('.testimonial-carousel').owlCarousel({
    loop: true,
    responsiveClass: true,
    dots: true,
    nav: false,
    autoplay: true,
    margin: 20,
    autoplayTimeout: 5000,
    autoplayHoverPause: true,
    responsive: {
        0: {
            items: 1,
        },
        600: {
            items: 2,
        },
        1000: {
            items: 2,
        }
    }
})

$('.service-carousel').owlCarousel({
    loop: true,
    responsiveClass: true,
    dots: true,
    nav: false,
    autoplay: true,
    margin: 20,
    autoplayTimeout: 4000,
    autoplayHoverPause: true,
    responsive: {
        0: {
            items: 1,
        },
        1000: {
            items: 2,
        }
    }
})
$('.service-card-carousel').owlCarousel({
    loop: true,
    responsiveClass: true,
    dots: true,
    nav: false,
    autoplay: true,
    margin: 20,
    autoplayTimeout: 3000,
    autoplayHoverPause: false,
    responsive: {
        0: {
            items: 1,
        },
        600: {
            items: 2,
        },
        1000: {
            items: 3,
        }
    }
})

document.addEventListener("DOMContentLoaded", function () {
    // Select all custom accordion buttons
    const customAccordionButtons = document.querySelectorAll('.accordion-button.custom-accordion-button');

    // Add event listeners for the collapse and expand events
    customAccordionButtons.forEach((button) => {
        const accordionCollapse = button.closest('.accordion-item').querySelector('.accordion-collapse');

        // Add listener when the accordion is shown (expanded)
        accordionCollapse.addEventListener('shown.bs.collapse', function () {
            const accordionItem = button.closest('.accordion-item.custom-accordion-item');
            // Add 'active-header' to the clicked button and 'active' to the parent accordion item
            button.classList.add('active-header');
            accordionItem.classList.add('active');
        });

        // Add listener when the accordion is hidden (collapsed)
        accordionCollapse.addEventListener('hidden.bs.collapse', function () {
            const accordionItem = button.closest('.accordion-item.custom-accordion-item');
            // Remove 'active-header' from the button and 'active' from the parent accordion item
            button.classList.remove('active-header');
            accordionItem.classList.remove('active');
        });
    });
});


// changing the accordion image dinamycially

document.addEventListener("DOMContentLoaded", function () {
    const accordionImage = document.querySelector('.accordionSideImage');

    // Define images corresponding to each accordion item
    const accordionImages = {
        1: 'public/frontend/images/HOW1.png', // Image for Accordion Item #1
        2: 'public/frontend/images/HOW2.png', // Image for Accordion Item #2
        3: 'public/frontend/images/HOW1.png', // Image for Accordion Item #3
        4: 'public/frontend/images/HOW2.png', // Image for Accordion Item #4
    };

    // Listen for when a new accordion item is opened
    document.querySelectorAll('.accordion-collapse').forEach((accordionItem, index) => {
        accordionItem.addEventListener('show.bs.collapse', function () {
            const accordionNumber = index + 1; // Get the accordion number (1-based)
            if (accordionImages[accordionNumber]) {
                accordionImage.src = accordionImages[accordionNumber]; // Change image based on accordion number
            }
        });
    });
});



$(document).ready(function () {
    // Carousel data for each tab
    const carouselData = {
        livingRoom: [
            `<div class="item"><div class="card product-card"><img src="public/frontend/images/livingroom1.png" alt=""><div class="card-body p-4"><p class="m-0 text-color">We help you Create a stylish and trendy living spaces that impresses and amazes your guests.</p></div></div></div>`,
            `<div class="item"><div class="card product-card"><img src="public/frontend/images/livingroom3.png" alt=""><div class="card-body p-4"><p class="m-0 text-color">The lounge part of the living area deserves the perfect quite drapes. And we have just the right solutions for you</p></div></div></div>`
        ],
        bedroom: [
            `<div class="item"><div class="card product-card"><img src="public/frontend/images/bedroom1.png" alt=""><div class="card-body p-4"><p class="m-0 text-color">Eyelet curtains over the French windows bring the perfect touch of flair to your bedrooms.</p></div></div></div>`,
            `<div class="item"><div class="card product-card"><img src="public/frontend/images/bedroom2.png" alt=""><div class="card-body p-4"><p class="m-0 text-color">Four-poster beds require matching curtains and sheers to elevate their style. At Curtains and Blinds, we offer fabrics that perfectly complement and complete the Mediterranean aesthetic.</p></div></div></div>`
        ],
        office: [
            `<div class="item"><div class="card product-card"><img src="public/frontend/images/office1.png" alt=""><div class="card-body p-4"><p class="m-0 text-color">At CAB we have tailor made solutions for your conference areas. A perfect combination of curtains and blinds</p></div></div></div>`,
            `<div class="item"><div class="card product-card"><img src="public/frontend/images/office2.png" alt=""><div class="card-body p-4"><p class="m-0 text-color">Home offices are becoming an important part of our personal spaces. At curtains and blinds we ensure the correct colours and textures are used to ensure enhanced productivity.</p></div></div></div>`
        ]
    };

    // Function to initialize Owl Carousel
    function initCarousel() {
        $(".service-carousel").owlCarousel({
            loop: true,
            responsiveClass: true,
            dots: false,
            nav: false,
            autoplay: false,
            margin: 20,
            responsive: {
                0: {
                    items: 1,
                },
                1000: {
                    items: 2,
                }
            }
        });
    }

    // Function to load content based on the selected tab
    function loadCarouselContent(category) {
        // Destroy the existing carousel (if initialized)
        $(".service-carousel").trigger('destroy.owl.carousel').html('');

        // Append new items for the selected category
        carouselData[category].forEach(item => {
            $(".service-carousel").append(item);
        });

        // Re-initialize Owl Carousel
        initCarousel();
    }

    // Initially load the 'Living Room' tab content
    loadCarouselContent('livingRoom');

    // Handle tab clicks
    $('.services-tab a').click(function (e) {
        e.preventDefault();

        // Update active class
        $('.services-tab a').removeClass('active');
        $(this).addClass('active');

        // Load content for the clicked tab
        const target = $(this).data('target');
        loadCarouselContent(target);
    });
});

/**
 * Toggles visibility of sections based on the presence of a zip code in the input field.
 * - If a zip code is entered, hides the zip code input section and displays a redirect message.
 *   Also redirects to the schedule appointment page after a delay.
 * - If no zip code is entered, displays an error message prompting for a zip code.
 */
function toggleSections() {
    const zipCodeInput = document.getElementById('ZipCodeInput').value;
    if (zipCodeInput) {
        document.getElementById('checkzipCode').classList.add('d-none');
        document.getElementById('redirectMsg').classList.remove('d-none');
        document.getElementById('ZipCodeInput-error').classList.add('d-none');

        // Redirect to schedule appointment page after 5 seconds
        setTimeout(function () {
            window.location.href = '/appointments'; // Replace with the actual URL
        }, 3000);
    } else {
        document.getElementById('ZipCodeInput-error').classList.remove('d-none');
        //alert("Please enter a Zip Code.");
    }
}

