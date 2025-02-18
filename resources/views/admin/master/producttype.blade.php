@extends('admin.layouts.app')
@section('title', 'Manage Product Type')
@section('content')
<div class="dataOverviewSection mt-3">
    <div class="section-title">
        <h6 class="fw-bold m-0">All Product Types <span class="fw-normal text-muted">({{ $productTypes->count() }})</span></h6>
        <a href="#" class="primary-btn addBtn" data-bs-toggle="modal" data-bs-target="#addProductTypeModal">+
            Add Product Type</a>
    </div>

    <!-- Add Product Type Modal Start -->
    <div class="modal fade" id="addProductTypeModal" tabindex="-1" aria-labelledby="addProductTypeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addProductTypeModalLabel">Add Product Type</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('product-types.store') }}" method="POST" id="productTypeForm">
                    @csrf
                    <input type="hidden" name="_method" value="POST" id="methodFieldProductType">
                    <div class="modal-body">
                        <div class="mb-1 w-100">
                            <label for="ProductTypeInput" class="form-label mb-1">Product Type <span class="text-danger">*</span></label>
                            <input type="text" class="form-control w-100" id="ProductTypeInput" name="product_type" required>
                        </div>

                        <div class="mb-1 w-100">
                            <label for="ProductUnitInput" class="form-label mb-1">Unit <span class="text-danger">*</span></label>
                            <select class="form-control w-100" name="product_unit[]" id="product_unit" multiple>
                                <option value="Meter">Meter</option>
                                <option value="Square Meter">Square Meter</option>
                                <option value="Feet">Feet</option>
                                <option value="Square Feet">Square Feet</option>
                                <option value="Panel">Panel</option>
                                <option value="Nos">Nos</option>
                                <option value="Roll">Roll</option>
                                <option value="Channel fitting">Channel fitting</option>
                                <option value="Box">Box</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="secondary-btn addBtn" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="primary-btn addBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Product Type Modal End -->

    <div class="dataOverview mt-3">
        <div class="table-responsive">
            <table class="table" id="projectsTable">
                <thead>
                    <tr>
                        <th style="border-top-left-radius: 6px; border-bottom-left-radius: 6px;" scope="col">S/N</th>
                        <th scope="col">Product Type</th>
                        <th scope="col">Product Unit</th>
                        <th style="border-top-right-radius: 6px; border-bottom-right-radius: 6px;" scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productTypes as $productType)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $productType->product_type }}</td>
                        <td>{{ $productType->product_unit }}</td>
                        <td>
                            <div class="dropdown">
                                <i class="bi bi-three-dots-vertical" type="button" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item small" href="#" onclick="editProductType({{ $productType->id }})">Edit</a></li>
                                    <li>
                                        <button type="button" class="dropdown-item small text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteId({{ $productType->id }})">
                                            Delete
                                        </button>                                        
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>                
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function editProductType(id) {
    fetch(`/product-types/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('ProductTypeInput').value = data.product_type;
            let productUnitSelect = document.getElementById('product_unit');
            let selectedUnits = Array.isArray(data.product_unit) ? data.product_unit : [];
            if (productUnitSelect && productUnitSelect.options) {
                for (let option of productUnitSelect.options) {
                    option.selected = selectedUnits.includes(option.value);
                }
            }
            document.getElementById('methodFieldProductType').value = 'PUT';
            document.querySelector('#productTypeForm').action = `/product-types/${id}`;
            document.querySelector('#addProductTypeModalLabel').textContent = 'Edit Product Type';
        })
        .catch(error => {
            console.error("Error fetching product type:", error);
        });

    // Show modal
    new bootstrap.Modal(document.getElementById('addProductTypeModal')).show();
}


    $(document).ready(function() {
        $("#productTypeForm").validate({
            rules: {
                product_type: {
                    required: true,
                    maxlength: 255
                },
                product_unit: {
                    required: true,
                    maxlength: 255
                }
            },
            messages: {
                product_type: {
                    required: "Please enter the product type",
                    maxlength: "Product type must not exceed 255 characters"
                }
            },
            errorElement: "div",
            errorPlacement: function (error, element) {
                error.addClass("form-text text-danger xsmall");
                error.insertAfter(element);
            },
            highlight: function (element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            }
        });

        $('#addProductTypeModal').on('hidden.bs.modal', function () {
            $('#productTypeForm')[0].reset();
            $('#productTypeForm').validate().resetForm();
        });

        $('#addProductTypeModal').on('show.bs.modal', function () {
            $('#productTypeForm').validate().resetForm();
        });
    });
</script>
<script>
    let deleteId = null;

    function setDeleteId(id) {
        deleteId = id;
    }
    document.getElementById('confirmDelete').addEventListener('click', function () {
        if (deleteId) {
            const form = document.createElement('form');
            form.action = `/product-types/${deleteId}`;
            form.method = 'POST';
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
</script>

@endsection
