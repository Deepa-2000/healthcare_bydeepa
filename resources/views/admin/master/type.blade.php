@extends('admin.layouts.app')
@section('title', 'Manage Types')
@section('content')
<div class="dataOverviewSection mt-3">
    <div class="section-title">
        <h6 class="fw-bold m-0">All Types <span class="fw-normal text-muted">({{ $types->count() }})</span></h6>
        <a href="#" class="primary-btn addBtn" data-bs-toggle="modal" data-bs-target="#addTypeModal">+
            Add Type</a>
    </div>

    <!-- Add Type Modal Start -->
    <div class="modal fade" id="addTypeModal" tabindex="-1" aria-labelledby="addTypeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addTypeModalLabel">Add Type</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('types.store') }}" method="POST" id="typeForm">
                    @csrf
                    <input type="hidden" name="_method" value="POST" id="methodFieldType">
                    <div class="modal-body">
                        <div class="mb-1 w-100">
                            <label for="ProductTypeInput" class="form-label mb-1">Type <span class="text-danger">*</span></label>
                            <input type="text" class="form-control w-100" id="ProductTypeInput" name="type" required>
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
    <!-- Add Type Modal End -->

    <div class="dataOverview mt-3">
        <div class="table-responsive">
            <table class="table" id="projectsTable">
                <thead>
                    <tr>
                        <th style="border-top-left-radius: 6px; border-bottom-left-radius: 6px;" scope="col">S/N</th>
                        <th scope="col">Type</th>
                        <th style="border-top-right-radius: 6px; border-bottom-right-radius: 6px;" scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($types as $type)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $type->type }}</td>
                        <td>
                            <div class="dropdown">
                                <i class="bi bi-three-dots-vertical" type="button" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item small" href="#" onclick="editType({{ $type->id }})">Edit</a></li>
                                    <li><button type="button" class="dropdown-item small text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteId({{ $type->id }})">Delete</button></li>
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
    function editType(id) {
        fetch(`/types/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('ProductTypeInput').value = data.type;
                document.getElementById('methodFieldType').value = 'PUT'; // For updating
                document.querySelector('#typeForm').action = `/types/${id}`; // Set form action

                // Update modal title
                document.querySelector('#addTypeModalLabel').textContent = 'Edit Type';
            });

        // Show modal
        new bootstrap.Modal(document.getElementById('addTypeModal')).show();
    }

    $(document).ready(function() {
        $("#typeForm").validate({
            rules: {
                type: {
                    required: true,
                    maxlength: 255
                }
            },
            messages: {
                type: {
                    required: "Please enter the type",
                    maxlength: "Type must not exceed 255 characters"
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

        $('#addTypeModal').on('hidden.bs.modal', function () {
            $('#typeForm')[0].reset();
            $('#typeForm').validate().resetForm();
        });

        $('#addTypeModal').on('show.bs.modal', function () {
            $('#typeForm').validate().resetForm();
        });
    });

    let deleteId = null;

    function setDeleteId(id) {
        deleteId = id;
    }

    document.getElementById('confirmDelete').addEventListener('click', function () {
        if (deleteId) {
            const form = document.createElement('form');
            form.action = `/types/${deleteId}`;
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
