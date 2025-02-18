@extends('admin.layouts.app')
@section('title', 'Manage Color')
@section('content')
<div class="dataOverviewSection mt-3">
    <div class="section-title">
        <h6 class="fw-bold m-0">All Color <span class="fw-normal text-muted">({{ $colors->count() }})</span></h6>
        <a href="#" class="primary-btn addBtn" data-bs-toggle="modal" data-bs-target="#addColorModal">+
            Add Color</a>
    </div>

    <!-- Add Color Modal Start -->
    <div class="modal fade" id="addColorModal" tabindex="-1" aria-labelledby="addColorModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addColorModalLabel">Add Color</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('colors.store') }}" method="POST" id="colorForm">
                    @csrf
                    <input type="hidden" name="_method" value="POST" id="methodFieldColor">
                    <div class="modal-body">
                        <div class="mb-1 w-100">
                            <label for="ColorInput" class="form-label mb-1">Color <span class="text-danger">*</span></label>
                            <input type="text" class="form-control w-100" id="ColorInput" name="color" required>
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
    <!-- Add Color Modal End -->

    <div class="dataOverview mt-3">
        <div class="table-responsive">
            <table class="table" id="projectsTable">
                <thead>
                    <tr>
                        <th style="border-top-left-radius: 6px; border-bottom-left-radius: 6px;"
                            scope="col">S/N</th>
                        <th scope="col">Color</th>
                        <th style="border-top-right-radius: 6px; border-bottom-right-radius: 6px;"
                            scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($colors as $color)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $color->color }}</td>
                        <td>
                            <div class="dropdown">
                                <i class="bi bi-three-dots-vertical" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false"></i>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item small" href="#" onclick="editColor({{ $color->id }})">Edit</a></li>
                                    <li>
                                        <button type="button" class="dropdown-item small text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteId({{ $color->id }})">
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
    function editColor(id) {
        fetch(`/colors/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('ColorInput').value = data.color;
                document.getElementById('methodFieldColor').value = 'PUT'; // For updating
                document.querySelector('#colorForm').action = `/colors/${id}`; // Set form action

                // Update modal title
                document.querySelector('#addColorModalLabel').textContent = 'Edit Color';
            });

        // Show modal
        new bootstrap.Modal(document.getElementById('addColorModal')).show();
    }

    $(document).ready(function() {
        $("#colorForm").validate({
            rules: {
                color: {
                    required: true,
                    maxlength: 255
                }
            },
            messages: {
                color: {
                    required: "Please enter the color",
                    maxlength: "Color must not exceed 255 characters"
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

        $('#addColorModal').on('hidden.bs.modal', function () {
            $('#colorForm')[0].reset();
            $('#colorForm').validate().resetForm();
        });

        $('#addColorModal').on('show.bs.modal', function () {
            $('#colorForm').validate().resetForm();
        });
    });

    let deleteId = null;

    function setDeleteId(id) {
        deleteId = id;
    }

    document.getElementById('confirmDelete').addEventListener('click', function () {
        if (deleteId) {
            const form = document.createElement('form');
            form.action = `/colors/${deleteId}`;
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
