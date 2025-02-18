@extends('admin.layouts.app')

@section('title', 'Edit Patient')

@section('content')
<div class="dataOverviewSection mt-3 mb-3">
    <form id="patientFormEdit" method="POST">
        @csrf
        <div class="dataOverview mt-3">
            <h6 class="m-0">Edit Patient</h6>
            <hr class="m-0 mt-2 mb-2">
            <div class="row mb-2">
                <div class="col-md-4">
                    <div class="mb-1 w-100">
                        <label for="name" class="form-label mb-1">Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control w-100" id="name" name="name" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-1 w-100">
                        <label for="email" class="form-label mb-1">Email<span class="text-danger">*</span></label>
                        <input type="email" class="form-control w-100" id="email" name="email" required>
                    </div>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <div class="mb-1 w-100">
                        <label for="dob" class="form-label mb-1">Date of birth<span class="text-danger">*</span></label>
                        <input type="date" class="form-control w-100" id="dob" name="dob">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-1 w-100">
                        <label for="gender" class="form-label mb-1">Gender<span class="text-danger">*</span></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="gender" checked>
                            <label class="form-check-label" for="gender">
                                Female
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="gender">
                            <label class="form-check-label" for="gender">
                                Male
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4">
                    <div class="mb-1 w-100">
                        <label for="blood_group" class="form-label mb-1">Blood Group<span class="text-danger">*</span></label>
                        <input type="text" class="form-control w-100" id="blood_group" name="blood_group">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-1 w-100">
                        <label for="contact" class="form-label mb-1">Contact<span class="text-danger">*</span></label>
                        <input type="text" class="form-control w-100" id="contact" name="contact">
                    </div>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    <div class="mb-1 w-100">
                        <label for="address" class="form-label mb-1">Address<span class="text-danger">*</span></label>
                        <textarea class="form-control" id="address" rows="5"></textarea>
                    </div>
                </div>
            </div>

            <div class="mt-3 d-flex gap-3 mb-4">
                <button type="submit" class="btn primary-btn">Update</button>
                <button type="reset" class="btn secondary-btn">Cancel</button>
            </div>
        </div>
    </form>
</div>

@endsection

@section('script')

<script>
    $(document).ready(function() {
        var path = window.location.pathname;

        // Split the path by "/"
        var segments = path.split('/');

        // Get the last segment (last parameter)
        var lastParam = segments[segments.length - 1];

        console.log(lastParam); // Output: 2


        var token = localStorage.getItem("token");

        if (!token) {
            alert("Unauthorized! Please login first.");
            window.location.href = "/login";
            return;
        }

        $("#patientFormEdit").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "/api/patient_update/"+lastParam,
                type: "POST",
                contentType: "application/json",
                headers: {
                    "Authorization": "Bearer " + token
                },
                data: JSON.stringify({
                    name: $("#name").val(),
                    email: $("#email").val(),
                    dob: $("#dob").val(),
                    gender: $("#gender").val(),
                    blood_group: $("#blood_group").val(),
                    contact: $("#contact").val(),
                    address: $("#address").val()
                }),
                success: function(response) {
                    localStorage.setItem("token", response.token);
                    if (response.status == true) {
                        alert(response.message);
                        window.location = "/patient_index";
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert("Login failed: " + xhr.responseJSON.message);
                }
            });
        });

        
        $.ajax({
            url: "/api/patient_show/" + lastParam,
            type: "GET",
            headers: {
                "Authorization": "Bearer " + token
            },
            success: function(response) {
                if (response.data != '') {
                    $('#name').val(response.patient.name);
                    $('#email').val(response.patient.email);
                    $("#dob").val(response.patient.dob);
                    $("#gender").val(response.patient.gender);
                    $("#blood_group").val(response.patient.blood_group);
                    $("#contact").val(response.patient.contact);
                    $("#address").val(response.patient.address);
                }

            },
            error: function(xhr) {
                alert("Access Denied! Redirecting to login.");
                window.location.href = "/login";
            }
        });
    });
</script>

@endsection