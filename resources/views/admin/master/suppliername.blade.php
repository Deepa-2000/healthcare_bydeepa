@extends('admin.layouts.app')
@section('title', 'Manage Supplier Name')
@section('content')
<div class="dataOverviewSection mt-3">
    <div class="section-title">
        <h6 class="fw-bold m-0">All Supplier Name <span class="fw-normal text-muted">({{ $suppliers->count() }})</span></h6>
        <a href="#" class="primary-btn addBtn" data-bs-toggle="modal" data-bs-target="#addSupplierModal">+
            Add Supplier Name</a>
    </div>

    <!-- Add Supplier Modal Start -->
    <div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addSupplierModalLabel">Add Supplier Name</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('suppliers.store') }}" method="POST" id="supplierForm">
                    @csrf
                    <input type="hidden" name="_method" value="POST" id="methodFieldSupplier">
                    <div class="modal-body">
                        <div class="mb-1 w-100">
                            <label for="SupplierNameInput" class="form-label mb-1">Supplier Name</label>
                            <input type="text" class="form-control w-100" id="SupplierNameInput" name="supplier_name" required>
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
    <!-- Add Supplier Modal End -->

    <div class="dataOverview mt-3">
        <div class="table-responsive">
            <table class="table" id="projectsTable">
                <thead>
                    <tr>
                        <th style="border-top-left-radius: 6px; border-bottom-left-radius: 6px;" scope="col">S/N</th>
                        <th scope="col">Supplier Name</th>
                        <th style="border-top-right-radius: 6px; border-bottom-right-radius: 6px;" scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $supplier)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $supplier->name }}</td>
                        <td>
                            <div class="dropdown">
                                <i class="bi bi-three-dots-vertical" type="button" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item small" href="#" onclick="editSupplier({{ $supplier->id }})">Edit</a></li>
                                    <li>
                                        <button type="button" class="dropdown-item small text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteId({{ $supplier->id }})">
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
    function editSupplier(id) {
        fetch(`/suppliers/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('SupplierNameInput').value = data.name;
                document.getElementById('methodFieldSupplier').value = 'PUT'; // For updating
                document.querySelector('#supplierForm').action = `/suppliers/${id}`; // Set form action

                // Update modal title
                document.querySelector('#addSupplierModalLabel').textContent = 'Edit Supplier Name';
            });

        // Show modal
        new bootstrap.Modal(document.getElementById('addSupplierModal')).show();
    }

    $(document).ready(function() {
        $("#supplierForm").validate({
            rules: {
                supplier_name: {
                    required: true,
                    maxlength: 255
                }
            },
            messages: {
                supplier_name: {
                    required: "Please enter the supplier name",
                    maxlength: "Supplier name must not exceed 255 characters"
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

        $('#addSupplierModal').on('hidden.bs.modal', function () {
            $('#supplierForm')[0].reset();
            $('#supplierForm').validate().resetForm();
        });

        $('#addSupplierModal').on('show.bs.modal', function () {
            $('#supplierForm').validate().resetForm();
        });
    });

    let deleteId = null;

    function setDeleteId(id) {
        deleteId = id;
    }

    document.getElementById('confirmDelete').addEventListener('click', function () {
        if (deleteId) {
            const form = document.createElement('form');
            form.action = `/suppliers/${deleteId}`;
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
