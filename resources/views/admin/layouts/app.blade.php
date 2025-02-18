<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title', 'Softone Healthcare | Dashboard')</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="" />
    @yield('seo')
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    
    <link rel="stylesheet" href="{{ asset('admin/CSS/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/CSS/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/CSS/mutliselectInput.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/CSS/seachableInput.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
   
    <!-- Fancybox Links -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    @yield('css')
</head>

<body>
    <div class="container-fluid p-0">
        <div class="d-flex justify-content-start">
            @include('admin.layouts.sidebar')
            <div class="w-100">
                @include('admin.layouts.nav')
                @include('admin.layouts.message')
                @yield('content')
            </div>
        </div>
        

        <!-- Footer -->
        @include('admin.layouts.footer')
        <!-- / Footer -->
    <!-- delete modal start -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <p>Are you sure you want to delete this item?</p>
                <div class="d-flex justify-content-end">
                    <button type="button" class="secondary-btn me-2" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="confirmDelete" class="primary-btn">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- delete modal end -->

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"
        integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <!-- datatabel -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('admin/JS/datatable.js') }}"></script>

    <!-- menu script -->
    <script src="{{ asset('admin/JS/sidebar.js') }}"></script>
    <script src="{{ asset('admin/JS/menu.js') }}"></script>
    <script src="{{ asset('admin/JS/franchise.js') }}"></script>
    <script src="{{ asset('admin/JS/multiselectInput.js') }}"></script>
    <script src="{{ asset('admin/JS/searchableselect.js') }}"></script>
    <!-- <script src="{{ asset('admin/JS/createquote.js') }}"></script> -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        Fancybox.bind("[data-fancybox]", {
            // Your custom options
        });
        $('.select2').select2();
        var placeholder = "select";
        $(".mySelect").select2({
            placeholder: placeholder,
            allowClear: false,
            minimumResultsForSearch: 5
        });
    </script>
    <script>
        $(document).ready(function () {
            $('.toggle-password').on('click', function () {
                // Get the target input field ID
                const target = $(this).data('target');
                const input = $('#' + target);
                const icon = $(this).find('i');

                // Toggle the password field type
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('bi-eye-slash').addClass('bi-eye');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('bi-eye').addClass('bi-eye-slash');
                }
            });
        });
        function customformatDate(dateString) {
            const date = new Date(dateString);

            if (isNaN(date)) {
                return 'Invalid Date';
            }

            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();

            let hours = date.getHours();
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12;

            return `${day}-${month}-${year}, ${String(hours).padStart(2, '0')}:${minutes} ${ampm}`;
        }

        //console.log(customFormatDate('2025-01-06T19:40:00'));

    </script>
    @yield('script')
</body>

</html>