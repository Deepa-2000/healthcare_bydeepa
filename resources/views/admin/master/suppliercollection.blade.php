@extends('admin.layouts.app')
@section('title', 'Manage Suppliers Collection')
@section('content')
<div class="dataOverviewSection mt-3">
    <div class="section-title">
        <h6 class="fw-bold m-0">All Supplier Collection <span class="fw-normal text-muted">({{ $collections->count() }})</span></h6>
        <a href="#" class="primary-btn addBtn" data-bs-toggle="modal" data-bs-target="#addCollectionModal">+
            Add Supplier Collection</a>
    </div>

    <!-- Add Supplier Collection Modal Start -->
    <div class="modal fade" id="addCollectionModal" tabindex="-1" aria-labelledby="addCollectionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addCollectionModalLabel">Add Supplier Collection</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('supplier-collections.store') }}" method="POST" id="collectionForm">
                    @csrf
                    <input type="hidden" name="_method" value="POST" id="methodFieldCollection">
                    <div class="modal-body">
                        <div class="mb-3 w-100">
                            <label for="supplier_id" class="form-label mb-1">Supplier <span class="text-danger">*</span></label>
                            <select class="form-control w-100" id="supplier_id" name="supplier_id" required>
                                <option value="" disabled selected>Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-1 w-100">
                            <label for="CollectionInput" class="form-label mb-1">Supplier Collection <span class="text-danger">*</span></label>
                            <input type="text" class="form-control w-100" id="CollectionInput" name="collection_name" required>
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
    <!-- Add Supplier Collection Modal End -->

    <div class="dataOverview mt-3">
        <div class="table-responsive">
            <table class="table" id="projectsTable">
    <thead>
        <tr>
            <th style="border-top-left-radius: 6px; border-bottom-left-radius: 6px;" scope="col">S/N</th>
            <th scope="col">Supplier Collection</th>
            <th scope="col">Supplier Name</th> <!-- New column -->
            <th style="border-top-right-radius: 6px; border-bottom-right-radius: 6px;" scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($collections as $collection)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $collection->collection_name }}</td>
            <td>{{ $collection->supplier->name ?? 'N/A' }}</td> <!-- Display supplier name -->
            <td>
                <div class="dropdown">
                    <i class="bi bi-three-dots-vertical" type="button" data-bs-toggle="dropdown" aria-expanded="false"></i>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item small" href="#" onclick="editCollection({{ $collection->id }})">Edit</a></li>
                        <li>
                            <button type="button" class="dropdown-item small text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteId({{ $collection->id }})">
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
    // Edit collection
    function editCollection(id) {
    // Fetch the collection details using the API endpoint
    fetch(`/supplier-collections/${id}/edit`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error fetching data: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            // Populate the form fields with the fetched data
            document.getElementById('CollectionInput').value = data.collection_name;
            document.getElementById('supplier_id').value = data.supplier_id; // Pre-select supplier
            document.getElementById('methodFieldCollection').value = 'PUT'; // Set method for updating
            document.querySelector('#collectionForm').action = `/supplier-collections/${id}`; // Set form action URL

            // Update modal title dynamically
            document.querySelector('#addCollectionModalLabel').textContent = 'Edit Supplier Collection';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to fetch the collection details. Please try again later.');
        });

    // Show the modal after setting up the form
    const modal = new bootstrap.Modal(document.getElementById('addCollectionModal'));
    modal.show();
}

$(document).ready(function () {
    // Form Validation
    $("#collectionForm").validate({
        rules: {
            supplier_id: {
                required: true
            },
            collection_name: {
                required: true,
                maxlength: 255
            }
        },
        messages: {
            supplier_id: {
                required: "Please select a supplier"
            },
            collection_name: {
                required: "Please enter the collection name",
                maxlength: "Collection name must not exceed 255 characters"
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
    $('#addCollectionModal').on('hidden.bs.modal', function () {
        $('#collectionForm')[0].reset();
        $('#collectionForm').validate().resetForm();
    });

    $('#addCollectionModal').on('show.bs.modal', function () {
        $('#collectionForm').validate().resetForm();
    });
});


    // Delete collection ID
    let deleteId = null;

    function setDeleteId(id) {
        deleteId = id;
    }

    // Confirm Delete
    document.getElementById('confirmDelete').addEventListener('click', function () {
        if (deleteId) {
            const form = document.createElement('form');
            form.action = `/supplier-collections/${deleteId}`;
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
