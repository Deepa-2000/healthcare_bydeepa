@extends('admin.layouts.app')
@section('title', 'Manage Supplier Collection Design')
@section('content')
<div class="dataOverviewSection mt-3">
    <div class="section-title">
        <h6 class="fw-bold m-0">All Supplier Collection Design <span class="fw-normal text-muted">({{ $supplierCollectionDesigns->count() }})</span></h6>
        <a href="#" class="primary-btn addBtn" data-bs-toggle="modal" data-bs-target="#addSupplierCollectionDesignModal">+
            Add Supplier Collection Design</a>
    </div>

    <!-- Add Supplier Collection Design Modal Start -->
    <div class="modal fade" id="addSupplierCollectionDesignModal" tabindex="-1" aria-labelledby="addSupplierCollectionDesignModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addSupplierCollectionDesignModalLabel">Add Supplier Collection Design</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('supplierCollectionDesigns.store') }}" method="POST" id="supplierCollectionDesignForm">
    @csrf
    <input type="hidden" name="_method" value="POST" id="methodFieldSupplierCollectionDesign">
    
    <div class="modal-body">
        <!-- Supplier Dropdown -->
        <div class="mb-3 w-100">
            <label for="SupplierSelect" class="form-label mb-1">Supplier <span class="text-danger">*</span></label>
            <select class="form-control w-100" id="supplierId" name="supplier_id" required>
                <option value="" disabled selected>Select Supplier</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Supplier Collection Dropdown (Populated Dynamically) -->
        <div class="mb-1 w-100">
            <label for="supplierCollectionSelect" class="form-label mb-1">Supplier Collection <span class="text-danger">*</span></label>
            <select class="form-control w-100" id="supplierCollectionSelect" name="supplier_collection_id" required>
                <option value="" disabled selected>Select Supplier Collection</option>
                <!-- Options will be populated dynamically based on the selected supplier -->
            </select>
        </div>

        <!-- Design Input -->
        <div class="mb-1 w-100">
            <label for="SupplierCollectionDesignInput" class="form-label mb-1">Supplier Collection Design <span class="text-danger">*</span></label>
            <input type="text" class="form-control w-100" id="SupplierCollectionDesignInput" name="design_name" required>
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
    <!-- Add Supplier Collection Design Modal End -->

    <div class="dataOverview mt-3">
        <div class="table-responsive">
            <table class="table" id="projectsTable">
                <thead>
                    <tr>
                        <th style="border-top-left-radius: 6px; border-bottom-left-radius: 6px;"
                            scope="col">S/N</th>
                        <th scope="col">Supplier Collection Design</th>
                        <th scope="col">Supplier Collection</th>
                        <th scope="col">Supplier Name</th>
                        <th style="border-top-right-radius: 6px; border-bottom-right-radius: 6px;"
                            scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($supplierCollectionDesigns as $design)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $design->design_name }}</td>
                        <td>{{ $design->supplierCollection->collection_name ?? '' }}</td>
                        <td>{{ $design->supplier->name }}</td>
                        <td>
                            <div class="dropdown">
                                <i class="bi bi-three-dots-vertical" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false"></i>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item small" href="#" onclick="editSupplierCollectionDesign({{ $design->id }})">Edit</a></li>
                                    <li>
                                        <button type="button" class="dropdown-item small text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteId({{ $design->id }})">
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
    // Function to edit a Supplier Collection Design
    function editSupplierCollectionDesign(id) {
        fetch('/supplierCollectionDesigns/'+id+'/edit')
            .then(response => response.json())
            .then(data => {
                // Set the design name
                document.getElementById('SupplierCollectionDesignInput').value = data.design_name;
                // Set the method field for update
                document.getElementById('methodFieldSupplierCollectionDesign').value = 'PUT';
                // Set form action for update
                document.querySelector('#supplierCollectionDesignForm').action = `/supplierCollectionDesigns/${id}`;
                
                // Set the selected supplier
                document.getElementById('supplierId').value = data.supplier_id;

                // Trigger supplier change to populate the supplier collection dropdown
                updateSupplierCollections(data.supplier_id, data.supplier_collection_id);

                // Update modal title
                document.querySelector('#addSupplierCollectionDesignModalLabel').textContent = 'Edit Supplier Collection Design';
            });

        // Show modal
        new bootstrap.Modal(document.getElementById('addSupplierCollectionDesignModal')).show();
    }

    // Function to update the supplier collection dropdown dynamically based on supplier
    function updateSupplierCollections(supplierId, selectedCollectionId) {
        // Fetch supplier collections based on the selected supplier
        fetch(`/suppliers/`+supplierId+`/collections`)
            .then(response => response.json())
            .then(data => {
                const collectionSelect = document.getElementById('supplierCollectionSelect');
                collectionSelect.innerHTML = ''; // Clear existing options

                // Add a default option
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Select Supplier Collection';
                collectionSelect.appendChild(defaultOption);

                // Populate the supplier collections dropdown
                data.forEach(collection => {
                    const option = document.createElement('option');
                    option.value = collection.id;
                    option.textContent = collection.collection_name;

                    // Set the selected collection
                    if (collection.id === selectedCollectionId) {
                        option.selected = true;
                    }

                    collectionSelect.appendChild(option);
                });
            });
    }

    // jQuery for form validation
    $(document).ready(function() {
        // Form validation
        $("#supplierCollectionDesignForm").validate({
            rules: {
                design_name: {
                    required: true,
                    maxlength: 255
                },
                supplier_id: {
                    required: true
                },
                supplier_collection_id: {
                    required: true
                }
            },
            messages: {
                design_name: {
                    required: "Please enter the design name",
                    maxlength: "Design name must not exceed 255 characters"
                },
                supplier_id: {
                    required: "Please select a supplier"
                },
                supplier_collection_id: {
                    required: "Please select a supplier collection"
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

        // Reset form when modal is closed
        $('#addSupplierCollectionDesignModal').on('hidden.bs.modal', function () {
            $('#supplierCollectionDesignForm')[0].reset();
            $('#supplierCollectionDesignForm').validate().resetForm();
        });

        // Reset validation when modal is shown
        $('#addSupplierCollectionDesignModal').on('show.bs.modal', function () {
            $('#supplierCollectionDesignForm').validate().resetForm();
        });
    });

    // For delete confirmation
    let deleteId = null;

    function setDeleteId(id) {
        deleteId = id;
    }

    document.getElementById('confirmDelete').addEventListener('click', function () {
        if (deleteId) {
            const form = document.createElement('form');
            form.action = `/supplierCollectionDesigns/${deleteId}`;
            form.method = 'POST';
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });

    // jQuery for handling supplier change to populate collections dynamically
    $(document).ready(function() {
    $('#supplierId').change(function() {
        var supplierId = $(this).val();

        // Clear previous options
        $('#supplierCollectionSelect').empty();
        $('#supplierCollectionSelect').append('<option value="" disabled selected>Select Supplier Collection</option>');

        if (supplierId) {
            // AJAX request to get the collections for the selected supplier
            $.ajax({
                url: '/supplier-collection/' + supplierId, // Ensure this URL matches the route
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.length === 0) {
                        $('#supplierCollectionSelect').append('<option value="" disabled>No collections found</option>');
                    } else {
                        // Populate the supplier collection dropdown
                        $.each(data, function(key, value) {
                            $('#supplierCollectionSelect').append('<option value="' + value.id + '">' + value.collection_name + '</option>');
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText); // Log the error message
                    alert('Error retrieving collections');
                }
            });
        }
    });
});

</script>

@endsection
