@extends('admin.layouts.app')
@section('title', 'Manage ZipCode')
@section('content')
<div class="dataOverviewSection mt-3">
    <div class="section-title">
        <h6 class="fw-bold m-0">All Zip Code <span class="fw-normal text-muted">({{ $zipCodes->count() }})</span></h6>
        <div class="d-flex align-items-center">
            <a class="dropdown-item small me-2" href="/zipcodes.xlsx"><i class="bi bi-file-earmark-arrow-down"></i> Download Template</a>
            <form action="{{ route('zipcodes.import') }}" method="POST" enctype="multipart/form-data" id="import_form" style="display: inline-flex;">
                @csrf
                <input type="file" name="file" id="fileInput" accept=".csv, .xlsx" required hidden>
                
                <button type="button" class="secondary-btn me-2 addBtn" id="customFileBtn" >
                    <i class="bi bi-cloud-arrow-up me-2"></i>Import
                </button>
                
                <button type="submit" class="primary-btn addBtn w-100" id="uploadBtn" style="display: none;"><i class="bi bi-cloud-arrow-up me-2"></i>Upload</button>
            </form>
            
            <a href="{{ route('zipcodes.export') }}" class="secondary-btn me-2 addBtn"><i class="bi bi-cloud-arrow-down me-2"></i>Export</a>
            {{-- <a class="secondary-btn me-2 addBtn" href="#"><i class="bi bi-cloud-arrow-up me-2"></i> Import</a> --}}
            {{-- <a class="secondary-btn me-2 addBtn" href="#"><i class="bi bi-cloud-arrow-down me-2"></i> Export</a> --}}
            <a href="#" class="primary-btn addBtn w-100" data-bs-toggle="modal" data-bs-target="#addZipcodeModal">+
                Add
                Zipcode</a>
        </div>
    </div>
    <!-- Add User Modal Start -->
    <div class="modal fade" id="addZipcodeModal" tabindex="-1" aria-labelledby="addZipcodeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addZipcodeModalLabel">Add Zip Code</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('zipcodes.store') }}" method="POST" id="zipcodeForm">
                    @csrf
                    <input type="hidden" name="_method" value="POST" id="methodField"> 
                    <div class="modal-body">
                        <div class="mb-1 w-100">
                            <label for="CountryInput" class="form-label mb-1">Country <span class="text-danger">*</span></label>
                            <input type="text" class="form-control w-100" id="CountryInput" name="country" required>
                        </div>
                        <div class="mb-1 w-100">
                            <label for="StateInput" class="form-label mb-1">State <span class="text-danger">*</span></label>
                            <input type="text" class="form-control w-100" id="StateInput" name="state" required>
                        </div>
                        <div class="mb-1 w-100">
                            <label for="CityInput" class="form-label mb-1">City <span class="text-danger">*</span></label>
                            <input type="text" class="form-control w-100" id="CityInput" name="city" required>
                        </div>
                        <div class="mb-1 w-100">
                            <label for="ZIPCodeInput" class="form-label mb-1">ZIP Code <span class="text-danger">*</span></label>
                            <input type="number" class="form-control w-100" id="ZIPCodeInput" name="zip_code" required>
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
    <!-- Add User Modal End -->

        <!-- Add User Modal Start -->
    <div class="modal fade" id="editZipcodeModal" tabindex="-1" aria-labelledby="editZipcodeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editZipcodeModalLabel">Add Zip Code</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form  method="POST" id="editzipcodeForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_method" value="POST" id="editmethodField"> 
                    <div class="modal-body">
                        <div class="mb-1 w-100">
                            <label for="CountryInput" class="form-label mb-1">Country <span class="text-danger">*</span></label>
                            <input type="text" class="form-control w-100" id="editCountryInput" name="country" required>
                        </div>
                        <div class="mb-1 w-100">
                            <label for="StateInput" class="form-label mb-1">State <span class="text-danger">*</span></label>
                            <input type="text" class="form-control w-100" id="editStateInput" name="state" required>
                        </div>
                        <div class="mb-1 w-100">
                            <label for="CityInput" class="form-label mb-1">City <span class="text-danger">*</span></label>
                            <input type="text" class="form-control w-100" id="editCityInput" name="city" required>
                        </div>
                        <div class="mb-1 w-100">
                            <label for="ZIPCodeInput" class="form-label mb-1">ZIP Code <span class="text-danger">*</span></label>
                            <input type="number" class="form-control w-100" id="editZIPCodeInput" name="zip_code" required>
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
    <!-- Add User Modal End -->

    <div class="dataOverview mt-3">
        <div class="table-responsive">
            <table class="table" id="projectsTable">
                <thead>
                    <tr>
                        <th style="border-top-left-radius: 6px; border-bottom-left-radius: 6px;"
                            scope="col">S/N</th>
                        <th scope="col">Country</th>
                        <th scope="col">State</th>
                        <th scope="col">City</th>
                        <th scope="col">Zipcode</th>
                        <!-- <th scope="col">Status</th> -->
                        <th style="border-top-right-radius: 6px; border-bottom-right-radius: 6px;"
                            scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($zipCodes as $zipCode)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $zipCode->country }}</td>
                        <td>{{ $zipCode->state }}</td>
                        <td>{{ $zipCode->city }}</td>
                        <td>{{ $zipCode->zip_code }}</td>
                        <td>
                            <div class="dropdown">
                                <i class="bi bi-three-dots-vertical" type="button" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item small" href="#" onclick="editZipCode({{ $zipCode->id }})">Edit</a></li>
                                    <li>
                                        <button type="button" class="dropdown-item small text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteId({{ $zipCode->id }})">
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
    function editZipCode(id) {
        fetch(`/zipcodes/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('editCountryInput').value = data.country;
                document.getElementById('editStateInput').value = data.state;
                document.getElementById('editCityInput').value = data.city;
                document.getElementById('editZIPCodeInput').value = data.zip_code;

                document.getElementById('editmethodField').value = 'PUT'; // This sets the hidden _method field to PUT
                // Set the form action dynamically (the URL with the id of the resource)
                const form = document.querySelector('#editzipcodeForm');
                            form.action = `/zipcodes/${id}`;

                // Optionally, you can update the modal title to reflect the action
                const modalTitle = document.querySelector('#editZipcodeModalLabel');
                modalTitle.textContent = 'Edit Zip Code';
                });

        // Show modal
        new bootstrap.Modal(document.getElementById('editZipcodeModal')).show();
    }
</script>
<script>
    document.getElementById('customFileBtn').addEventListener('click', function () {
        document.getElementById('fileInput').click(); // Trigger hidden input
    });

    document.getElementById('fileInput').addEventListener('change', function () {
        if (this.files.length > 0) {
            document.getElementById('customFileBtn').innerHTML = `<i class="bi bi-file-earmark-check me-2"></i>${this.files[0].name}`;
            document.getElementById('customFileBtn').style.width = '160px';
            const importForm = document.getElementById('import_form');
        if (importForm) {
            importForm.style.marginRight = '8px';
        }
            document.getElementById('uploadBtn').style.display = 'inline-block'; // Show upload button after file selection
        }
    });
    $(document).ready(function() {
    // Initialize jQuery validation for the modal form
    $("#zipcodeForm").validate({
        rules: {
            country: {
                required: true
            },
            state: {
                required: true
            },
            city: {
                required: true
            },
            zip_code: {
                required: true,
                number: true,
                minlength: 6,
                maxlength: 6
            }
        },
        messages: {
            country: {
                required: "Please enter the country"
            },
            state: {
                required: "Please enter the state"
            },
            city: {
                required: "Please enter the city"
            },
            zip_code: {
                required: "Please enter the ZIP code",
                number: "Please enter a valid number",
                minlength: "ZIP code must be 6 digits",
                maxlength: "ZIP code must be 6 digits"
            }
        },
        errorElement: "div", // Use div to display errors
        errorPlacement: function (error, element) {
            error.addClass("form-text text-danger xsmall");
            error.insertAfter(element); // Place the error directly after the input element
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        }
    });

    // Reset validation on modal close
    $('#addZipcodeModal').on('hidden.bs.modal', function () {
        $('#zipcodeForm')[0].reset();
        $('#zipcodeForm').validate().resetForm(); // Reset validation errors
    });

    // Trigger validation when modal opens
    $('#addZipcodeModal').on('show.bs.modal', function () {
        $('#zipcodeForm').validate().resetForm(); // Reset previous validation state
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
            form.action = `/zipcodes/${deleteId}`;
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