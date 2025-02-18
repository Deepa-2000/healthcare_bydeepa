@extends('admin.layouts.app')
@section('title', 'Manage Composition')
@section('content')
<div class="dataOverviewSection mt-3">
    <div class="section-title">
        <h6 class="fw-bold m-0">All Composition <span class="fw-normal text-muted">({{ $compositions->count() }})</span></h6>
        <a href="#" class="primary-btn addBtn" data-bs-toggle="modal" data-bs-target="#addCompositionModal">+
            Add Composition</a>
    </div>

    <!-- Add Composition Modal Start -->
    <div class="modal fade" id="addCompositionModal" tabindex="-1" aria-labelledby="addCompositionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addCompositionModalLabel">Add Composition</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('compositions.store') }}" method="POST" id="compositionForm">
                    @csrf
                    <input type="hidden" name="_method" value="POST" id="methodFieldComposition">
                    <div class="modal-body">
                        <div class="mb-1 w-100">
                            <label for="CompositionInput" class="form-label mb-1">Composition <span class="text-danger">*</span></label>
                            <input type="text" class="form-control w-100" id="CompositionInput" name="composition" required>
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
    <!-- Add Composition Modal End -->

    <div class="dataOverview mt-3">
        <div class="table-responsive">
            <table class="table" id="projectsTable">
                <thead>
                    <tr>
                        <th style="border-top-left-radius: 6px; border-bottom-left-radius: 6px;"
                            scope="col">S/N</th>
                        <th scope="col">Composition</th>
                        <th style="border-top-right-radius: 6px; border-bottom-right-radius: 6px;"
                            scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($compositions as $composition)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $composition->composition }}</td>
                        <td>
                            <div class="dropdown">
                                <i class="bi bi-three-dots-vertical" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false"></i>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item small" href="#" onclick="editComposition({{ $composition->id }})">Edit</a></li>
                                    <li>
                                        <button type="button" class="dropdown-item small text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteId({{ $composition->id }})">
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
    function editComposition(id) {
        fetch(`/compositions/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('CompositionInput').value = data.composition;
                document.getElementById('methodFieldComposition').value = 'PUT'; // For updating
                document.querySelector('#compositionForm').action = `/compositions/${id}`; // Set form action

                // Update modal title
                document.querySelector('#addCompositionModalLabel').textContent = 'Edit Composition';
            });

        // Show modal
        new bootstrap.Modal(document.getElementById('addCompositionModal')).show();
    }

    $(document).ready(function() {
        $("#compositionForm").validate({
            rules: {
                composition: {
                    required: true,
                    maxlength: 255
                }
            },
            messages: {
                composition: {
                    required: "Please enter the composition",
                    maxlength: "Composition must not exceed 255 characters"
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

        $('#addCompositionModal').on('hidden.bs.modal', function () {
            $('#compositionForm')[0].reset();
            $('#compositionForm').validate().resetForm();
        });

        $('#addCompositionModal').on('show.bs.modal', function () {
            $('#compositionForm').validate().resetForm();
        });
    });

    let deleteId = null;

    function setDeleteId(id) {
        deleteId = id;
    }

    document.getElementById('confirmDelete').addEventListener('click', function () {
        if (deleteId) {
            const form = document.createElement('form');
            form.action = `/compositions/${deleteId}`;
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
