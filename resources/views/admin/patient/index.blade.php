@extends('admin.layouts.app')

@section('title', 'Patient List')

@section('content')

<div class="dataOverviewSection mt-3">
    <div class="section-title">
        <h6 class="fw-bold m-0">All Patient<span class="fw-normal text-muted"></span></h6>
        <a href="{{ url('patient_create') }}" class="primary-btn addBtn">+ Add
            Patient</a>
    </div>
    <div class="dataOverview mt-3">
        <div class="d-flex align-items-center justify-content-end mb-3">
            <a class="secondary-btn me-2 addBtn" href=""><i class="bi bi-cloud-arrow-down me-2"></i>
                Export Data</a>
            <a class="secondary-btn addBtn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                aria-controls="offcanvasRight"><i class="bi bi-filter me-2"></i> Filter</a>
        </div>
        <div class="table-responsive">
            <table class="table" id="doctorTable">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>DOB</th>
                        <th>Gender</th>
                        <th>Blood Group</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="patientBody">

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Filter Sidebar -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header border-bottom">
        <h6 class="offcanvas-title" id="FilterSidebarLabel">Add Filters</h6>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <form id="filter_form" action="" method="get">
    <div class="offcanvas-body">
       
    </div>

    <div class="offcanvas-footer">
        <div class="d-flex justify-content-start p-3 border-top">
            <button type="button" class="secondary-btn me-2 addBtn" id="clearBtn" name="clearBtn" onclick="clearForm()" data-bs-dismiss="offcanvas">Clear</button>
            <button type="submit" class="primary-btn addBtn">Apply</button>
        </div>
    </div>
</form>
</div>
<!-- Filter Sidebar end -->


<!-- delete modal start -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="deleteUserForm" method="POST"
                action="" autocomplete="off">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteUserModalLabel">Delete Product</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this product?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="secondary-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="primary-btn">Delete</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- delete modal end -->

@endsection
@section('script')
<script>
    function clearForm() {
        document.getElementById("filter_form").reset();
    }
    // Function to handle form submission
    document.getElementById("filter_form").addEventListener("submit", function(event) {
        event.preventDefault();  // Prevent default form submission

        // Construct the URL dynamically based on selected filters
        const form = new FormData(this);
        const params = new URLSearchParams();

        form.forEach((value, key) => {
            if (value && value !== 'Select' && value !== '') {
                params.append(key, value);
            }
        });

        window.location.href = url;
    });

    function clearForm() {
        document.getElementById("filter_form").reset();
    }
</script>
<script>
    function openDeleteModal(productId) {
        // Update the form action dynamically
        const form = document.getElementById('deleteUserForm');
        form.action = actionUrl;

        // Show the modal (assuming you're using Bootstrap's modal)
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
        deleteModal.show();
    }
</script>
<script>
    $(document).ready(function() {
        
        var token = localStorage.getItem("token");

        if (!token) {
            alert("Unauthorized! Please login first.");
            window.location.href = "/login";
            return;
        }

        $.ajax({
            url: "/api/patients",
            type: "GET",
            headers: {
                "Authorization": "Bearer " + token
            },
            success: function(response) {
                let tdData = 'Data Not Available';
                $.each(response.data,function(index, value){
                    let action = `<td><a href='{{url('patient_edit/`+value.id+`')}}' class='btn btn-warning'>Edit</a> | <a href='javascript::void(0)' onclick='deletePatient(`+value.id+`)' class='btn btn-danger'>Delete</a></td>`
                    tdData += `<tr>
                                <td>` + (index + 1) + `</td>
                                <td>`+value.user.name+`</td>
                                <td>`+value.user.email+`</td>
                                <td>`+value.dob+`</td>
                                <td>`+value.gender+`</td>
                                <td>`+value.blood_group+`</td>
                                <td>`+value.contact+`</td>
                                <td>`+value.address+`</td>
                                `+action+`
                            </tr>`;

                });

                console.log(tdData);
                $("#patientBody").append(tdData);
            },
            error: function(xhr) {
                alert("Access Denied! Redirecting to login.");
                window.location.href = "/login";
            }
        });

        


        $(".dt-responsive").dataTable({
            responsive: true,
            columnDefs: [{
                responsivePriority: 1,
                targets: 0
            }, {
                responsivePriority: 2,
                targets: -1
            }]
        });
        $(".dt-responsive1").dataTable({
            responsive: true,
            columnDefs: [{
                responsivePriority: 1,
                targets: 0
            }, {
                responsivePriority: 2,
                targets: -1
            }]
        });
    });

    function deletePatient(id){  
            var token = localStorage.getItem("token");

            if (!token) {
                alert("Unauthorized! Please login first.");
                window.location.href = "/login";
                return;
            }
        
            $.ajax({
                url: "/api/patient_delete/" + id,
                type: "POST",
                headers: {
                    "Authorization": "Bearer " + token
                },
                success: function(response) {
                    // console.log(response.patient.name);
                    if (response.status == 'true') {
                        alert(response.message);
                    }

                },
                error: function(xhr) {
                    alert("Access Denied! Redirecting to login.");
                    window.location.href = "/login";
                }
            });
        }

</script>
@endsection