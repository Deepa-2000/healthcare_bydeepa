class CustomFooter extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `

        <footer class="container-fluid w-100">
            <div class="container">
                <div class="footerTop">
                    <div class="row d-flex justify-content-between">
                        <div class="col-md-6 d-flex flex-column">
                            <a class="p-2" href="#"><img class="me-3" src="images/location.svg" alt="">Find your
                                location</a>
                            <a class="p-2" href="#"><img class="me-3" src="images/call.svg" alt="">(888) 765 82737</a>
                        </div>
                        <div class="col-md-6 d-flex flex-column align-items-end">
                            <div>
                                <h4>Follow Us</h4>
                                <div class="social-links d-flex justify-content-start">
                                    <a href="#" class="pe-3"><img src="images/facebook.svg" alt=""></a>
                                    <a href="#" class="pe-3"><img src="images/instagram.svg" alt=""></a>
                                    <a href="#" class="pe-3"><img src="images/linkedin.svg" alt=""></a>
                                    <a href="#" class="pe-3"><img src="images/twitter.svg" alt=""></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="nav-link-row mt-4">
                    <nav class="navbar justify-content-center"
                        style="border-top: 1px solid; border-bottom: 1px solid; border-radius: 0px; padding: 0px !important;">
                        <ul class="navbar-nav d-flex flex-row p-0" style="column-gap: 64px; flex-wrap: wrap;">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Curtains</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Terms & Conditions</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Accessibility</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Privacy Policy for California Residents</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">DO NOT SELL MY INFO</a>
                            </li>
                        </ul>
                    </nav>
                </div>

                <div class="footer-content mt-4 w-75 m-auto">
                    <p class="text-center text-color">*Virtual consultations are not available at all locations. Not all
                        products available in Canada</p>
                    <p class="text-center text-color">©2024 Budget Blinds, LLC, All Rights Reserved. Budget Blinds is a
                        trademark of Budget Blinds, LLC and a Home Franchise Concepts Brand. Inspired Drapes is sold
                        exclusively through Budget Blinds and is a trademark of Budget Blinds, LLC. Each franchise is
                        independently owned and operated and may not offer all products represented on this website.</p>
                </div>
            </div>
        </footer>
        
        `;
    }
}

customElements.define("custom-footer", CustomFooter);