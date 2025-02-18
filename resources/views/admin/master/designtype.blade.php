@extends('admin.layouts.app')
@section('title', 'Manage Design Type')
@section('content')
<div class="dataOverviewSection mt-3">
    <div class="section-title">
        <h6 class="fw-bold m-0">All Design Type <span class="fw-normal text-muted">({{ $designTypes->count() }})</span></h6>
        <a href="#" class="primary-btn addBtn" data-bs-toggle="modal" data-bs-target="#addDesignTypeModal">+
            Add
            Design Type</a>
    </div>
    <!-- Add Design Type Modal Start -->
    <div class="modal fade" id="addDesignTypeModal" tabindex="-1" aria-labelledby="addDesignTypeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addDesignTypeModalLabel">Add Design Type</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('design-types.store') }}" method="POST" id="designTypeForm">
                    @csrf
                    <input type="hidden" name="_method" value="POST" id="methodFieldDesignType">
                    <div class="modal-body">
                        <div class="mb-1 w-100">
                            <label for="DesignTypeInput" class="form-label mb-1">Design Type <span class="text-danger">*</span></label>
                            <input type="text" class="form-control w-100" id="DesignTypeInput" name="design_type" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="secondary-btn addBtn"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="primary-btn addBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Design Type Modal End -->

    <div class="dataOverview mt-3">
        <div class="table-responsive">
            <table class="table" id="projectsTable">
                <thead>
                    <tr>
                        <th style="border-top-left-radius: 6px; border-bottom-left-radius: 6px;"
                            scope="col">S/N</th>
                        <th scope="col">Design Type</th>
                        <th style="border-top-right-radius: 6px; border-bottom-right-radius: 6px;"
                            scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($designTypes as $designType)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $designType->design_type }}</td>
                        <td>
                            <div class="dropdown">
                                <i class="bi bi-three-dots-vertical" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false"></i>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item small" href="#" onclick="editDesignType({{ $designType->id }})">Edit</a></li>
                                    <li>
                                        <button type="button" class="dropdown-item small text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteId({{ $designType->id }})">
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
    function editDesignType(id) {
        fetch(`/design-types/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('DesignTypeInput').value = data.design_type;
                document.getElementById('methodFieldDesignType').value = 'PUT'; // For updating
                document.querySelector('#designTypeForm').action = `/design-types/${id}`; // Set form action

                // Update modal title
                document.querySelector('#addDesignTypeModalLabel').textContent = 'Edit Design Type';
            });

        // Show modal
        new bootstrap.Modal(document.getElementById('addDesignTypeModal')).show();
    }

    $(document).ready(function() {
        $("#designTypeForm").validate({
            rules: {
                design_type: {
                    required: true,
                    maxlength: 255
                }
            },
            messages: {
                design_type: {
                    required: "Please enter the design type",
                    maxlength: "Design type must not exceed 255 characters"
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

        $('#addDesignTypeModal').on('hidden.bs.modal', function () {
            $('#designTypeForm')[0].reset();
            $('#designTypeForm').validate().resetForm();
        });

        $('#addDesignTypeModal').on('show.bs.modal', function () {
            $('#designTypeForm').validate().resetForm();
        });
    });

    let deleteId = null;

    function setDeleteId(id) {
        deleteId = id;
    }

    document.getElementById('confirmDelete').addEventListener('click', function () {
        if (deleteId) {
            const form = document.createElement('form');
            form.action = `/design-types/${deleteId}`;
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
