class CustomSidebar extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
                    <div class="sidebar">
                <div class="menu-btn"><i class="bi bi-list"></i></div>
                <div class="head">
                    <div class="user-img">
                        <img src="images/logo.svg" alt="">
                    </div>
                </div>
                <div class="nav">
                    <div class="menu w-100">
                        <ul class="p-0">
                            <li class="active"><a href="dashboard.html"><i class="bi bi-house"></i><span class="text">Dashboard</span></a></li>
                            <li><a href="users.html"><i class="bi bi-people"></i><span class="text">Users</span></a></li>
                            <li><a href="franchise.html"><i class="bi bi-building-add"></i><span class="text">Franchise</span></a></li>
                            <li><a href="product.html"><i class="bi bi-box2"></i><span class="text">Products</span></a></li>
                            <li><a href="appointment.html"><i class="bi bi-journal"></i></i><span class="text">Appointments</span></a></li>
                            <li><a href="quotation.html"><i class="bi bi-receipt-cutoff"></i><span class="text">Quotations</span></a></li>
                            <li><a href="#"><i class="bi bi-database"></i><span class="text">Masters</span><i class="arrow ph-bold ph-caret-down"></i></a>
                                <ul class="sub-menu">
                                    <li><a href="zipcode.html"><span class="text">ZIP Codes</span></a></li>
                                    <li><a href="productType.html"><span class="text">Product Type</span></a></li>
                                    <li><a href="supplierName.html"><span class="text">Supplier Name</span></a></li>
                                    <li><a href="supplierCollection.html"><span class="text">Supplier Collection</span></a></li>
                                    <li><a href="suppliercollectiondesign.html"><span class="text">Supplier Design</span></a></li>
                                    <li><a href="color.html"><span class="text">Color</span></a></li>
                                    <li><a href="composition.html"><span class="text">Composition</span></a></li>
                                    <li><a href="type.html"><span class="text">Type</span></a></li>
                                    <li><a href="usage.html"><span class="text">Usage</span></a></li>
                                    <li><a href="designType.html"><span class="text">Design Type</span></a></li>
                                </ul>
                            </li>
                            <li><a href="#"><i class="bi bi-truck"></i><span class="text">Orders</span></a></li>
                            <li><a href="#"><i class="bi bi-file-earmark-bar-graph"></i><span class="text">Reports</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>

        
        `;
    }
}

customElements.define("custom-sidebar", CustomSidebar);