@extends('admin.layouts.app')

@section('title', 'Doctor Details')

@section('content')
<div class="card">
    <h5 class="card-header text-center">
        Doctor Details
    </h5>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>Name</th>
                        <td id="name"></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td id="email"></td>
                    </tr>
                    <tr>
                        <th>Specialization</th>
                        <td id="specialization"></td>
                    </tr>
                    <tr>
                        <th>Contact</th>
                        <td id="contact"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-center">
            <a href="{{url('doctor_index')}}" class="btn btn-secondary">Back to Doctor List</a>
        </div>
    </div>
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

        var path = window.location.pathname;

        // Split the path by "/"
        var segments = path.split('/');

        // Get the last segment (last parameter)
        var lastParam = segments[segments.length - 1];

        console.log(lastParam);  // Output: 2

        $.ajax({
            url: "/api/doctor_show/"+lastParam,
            type: "GET",
            headers: {
                "Authorization": "Bearer " + token
            },
            success: function(response) {
                // console.log(response.doctor.name);
                if(response.data != ''){
                    $('#name').text(response.doctor.name);
                    $('#email').text(response.doctor.email);
                    $('#specialization').text(response.doctor.specialization);
                    $('#contact').text(response.doctor.contact);
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