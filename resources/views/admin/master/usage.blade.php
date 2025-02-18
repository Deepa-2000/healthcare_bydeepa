@extends('admin.layouts.app')
@section('title', 'Manage Usage')
@section('content')
<div class="dataOverviewSection mt-3">
    <div class="section-title">
        <h6 class="fw-bold m-0">All Usage <span class="fw-normal text-muted">({{ $usages->count() }})</span></h6>
        <a href="#" class="primary-btn addBtn" data-bs-toggle="modal" data-bs-target="#addUsageModal">+
            Add Usage</a>
    </div>

    <!-- Add Usage Modal Start -->
    <div class="modal fade" id="addUsageModal" tabindex="-1" aria-labelledby="addUsageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addUsageModalLabel">Add Usage</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('usages.store') }}" method="POST" id="usageForm">
                    @csrf
                    <input type="hidden" name="_method" value="POST" id="methodFieldUsage">
                    <div class="modal-body">
                        <div class="mb-1 w-100">
                            <label for="UsageInput" class="form-label mb-1">Usage <span class="text-danger">*</span></label>
                            <input type="text" class="form-control w-100" id="UsageInput" name="usage" required>
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
    <!-- Add Usage Modal End -->

    <div class="dataOverview mt-3">
        <div class="table-responsive">
            <table class="table" id="projectsTable">
                <thead>
                    <tr>
                        <th style="border-top-left-radius: 6px; border-bottom-left-radius: 6px;" scope="col">S/N</th>
                        <th scope="col">Usage</th>
                        <th style="border-top-right-radius: 6px; border-bottom-right-radius: 6px;" scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usages as $usage)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $usage->usages }}</td>
                        <td>
                            <div class="dropdown">
                                <i class="bi bi-three-dots-vertical" type="button" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item small" href="#" onclick="editUsage({{ $usage->id }})">Edit</a></li>
                                    <li><button type="button" class="dropdown-item small text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteId({{ $usage->id }})">Delete</button></li>
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
    function editUsage(id) {
        fetch(`/usages/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('UsageInput').value = data.usages;
                document.getElementById('methodFieldUsage').value = 'PUT'; // For updating
                document.querySelector('#usageForm').action = `/usages/${id}`; // Set form action

                // Update modal title
                document.querySelector('#addUsageModalLabel').textContent = 'Edit Usage';
            });

        // Show modal
        new bootstrap.Modal(document.getElementById('addUsageModal')).show();
    }

    $(document).ready(function() {
        $("#usageForm").validate({
            rules: {
                usage: {
                    required: true,
                    maxlength: 255
                }
            },
            messages: {
                usage: {
                    required: "Please enter the usage",
                    maxlength: "Usage must not exceed 255 characters"
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

        $('#addUsageModal').on('hidden.bs.modal', function () {
            $('#usageForm')[0].reset();
            $('#usageForm').validate().resetForm();
        });

        $('#addUsageModal').on('show.bs.modal', function () {
            $('#usageForm').validate().resetForm();
        });
    });

    let deleteId = null;

    function setDeleteId(id) {
        deleteId = id;
    }

    document.getElementById('confirmDelete').addEventListener('click', function () {
        if (deleteId) {
            const form = document.createElement('form');
            form.action = `/usages/${deleteId}`;
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
