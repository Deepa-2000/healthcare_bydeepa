@extends('admin.layouts.app')

@section('content')
<div class="info-tabs">
    <div class="card info-card">
        <img src="{{ asset('admin/images/tab_products.svg') }}" alt="">
        <h2 class="fw-bold m-0 mb-1" id="countdoctors"></h2>
        <p class="m-0 small">Total number of Doctors</p>
    </div>
    <div class="card info-card">
        <img src="{{ asset('admin/images/tab_franchise.svg') }}" alt="">
        <h2 class="fw-bold m-0 mb-1" id="countpatients"></h2>
        <p class="m-0 small">Total number of Patients</p>
    </div>
</div>
<div class="dataOverviewSection">
</div>

<div class="modal fade" id="assignAppointmentModal" tabindex="-1" aria-labelledby="assignAppointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignAppointmentModalLabel">Assign Franchise to Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="appointmentId" name="appointment_id">
                    <div class="mb-3">
                        <label for="franchise" class="form-label">Select Franchise</label>
                        <select id="franchise" name="franchise_id" class="form-select">
                            <option value="">Select Franchise</option>

                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Assign Franchise</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('script')
<script>
    document.getElementById('dateFilter').addEventListener('change', function() {
        let selectedDate = this.value;
        let baseUrl = "";
        if (selectedDate) {
            window.location.href = baseUrl + "?dateFilter=" + selectedDate;
        } else {
            window.location.href = baseUrl; // Redirect without the dateFilter parameter
        }
    });
</script>
<script>
    function confirmAssign(appointmentId) {
        // Open modal and set appointment ID
        $('#assignAppointmentModal').modal('show');
        $('#appointmentId').val(appointmentId);
    }
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: "{{ session('success') }}",
        confirmButtonColor: '#3085d6'
    });
    @endif
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
            url: "/api/doctors",
            type: "GET",
            headers: {
                "Authorization": "Bearer " + token
            },
            success: function(response) {
                // console.log(response.total_count);
                $("#countdoctors").text(response.total_count);
            },
            error: function(xhr) {
                alert("Access Denied! Redirecting to login.");
                window.location.href = "/login";
            }
        });

        $.ajax({
            url: "/api/patients",
            type: "GET",
            headers: {
                "Authorization": "Bearer " + token
            },
            success: function(response) {
                $("#countpatients").text(response.total_count);
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


        $("#logout-btn").click(function() {
            var token = localStorage.getItem("token"); 
            if (!token) {
                alert("You are not logged in!");
                window.location.href = "/login";
                return;
            }
            $.ajax({
                url: "/api/logout", 
                type: "POST",
                headers: {
                    "Authorization": "Bearer " + token
                },
                success: function(response) {
                    alert("Logout successful!");
                    localStorage.removeItem("token");
                    window.location.href = "/login";
                },
                error: function(xhr) {
                    alert("Logout failed. Please try again.");
                    console.error(xhr.responseText);
                }
            });
        });
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
</script>
@endsection