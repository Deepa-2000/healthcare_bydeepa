@extends('admin.layouts.app')

@section('title', 'Add Doctor')

@section('content')
<div class="dataOverviewSection mt-3 mb-3">
    <form id="doctorForm" method="POST">
        @csrf
        <div class="dataOverview mt-3">
            <h6 class="m-0">Add New Doctor</h6>
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
                        <label for="specialization" class="form-label mb-1">specialization</label>
                        <input type="text" class="form-control w-100" id="specialization" name="specialization">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-1 w-100">
                        <label for="contact" class="form-label mb-1">Contact</label>
                        <input type="text" class="form-control w-100" id="contact" name="contact">
                    </div>
                </div>
            </div>

            <div class="mt-3 d-flex gap-3 mb-4">
                <button type="submit" class="btn primary-btn">Add Doctor</button>
                <button type="reset" class="btn secondary-btn">Cancel</button>
            </div>
        </div>
    </form>
</div>

@endsection

@section('script')

<script>
    $(document).ready(function() {
        var token = localStorage.getItem("token");

        if (!token) {
            alert("Unauthorized! Please login first.");
            window.location.href = "/login";
            return;
        }

        $("#doctorForm").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "/api/doctor_create",
                type: "POST",
                contentType: "application/json",
                headers: {
                    "Authorization": "Bearer " + token
                },
                data: JSON.stringify({
                    name: $("#name").val(),
                    email: $("#email").val(),
                    specialization: $("#specialization").val(),
                    contact: $("#contact").val()
                }),
                success: function(response) {
                    // localStorage.setItem("token", response.token);
                    if(response.status == true){
                        alert(response.message);
                        window.location = "/doctor_index";
                    }else{
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert("Login failed: " + xhr.responseJSON.message);
                }
            });
        });
    });
</script>

@endsection