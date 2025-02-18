<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('admin/CSS/designSystem.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/CSS/login.css') }}">
</head>

<body class="white-bg">

    <section class="loginScreen">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 d-flex justify-content-center align-items-start">
                    <div class="login-section">
                        <img class="logo" src="{{ asset('images/logo.svg') }}" alt="">
                        <div class="section-title mb-4 flex-column align-items-start p-0">
                            <p class="m-0 small">Welcome back!!</p>
                            <h4 class="fw-bold">Please Sign In</h4>
                            @if(Session::has('success'))
                            <div id="smessage" class="success-message" style="color: green; font-weight: bold;">
                                <strong>Success!</strong> {{ Session::get('success') }}
                            </div>
                            @endif
                        </div>
                        <form id="loginForm" method="POST">
                            @csrf
                            <div class="mb-2">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control w-100 @error('email') is-invalid @enderror" id="email" name="email"
                                    value="{{ old('email') }}" aria-describedby="emailHelp" placeholder="Enter Email">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control w-100 @error('password') is-invalid @enderror" id="password" name="password"
                                    placeholder="Enter Password">
                                <button class="btn bg-transparent border-0 toggle-password" type="button" data-target="password">
                                    <i class="bi bi-eye-slash"></i>
                                </button>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="rememberMe">
                                    <label class="form-check-label" for="rememberMe">Remember me</label>
                                </div>
                                <a href="" class="red-link small text-decoration-underline">I forgot my password</a>
                            </div>

                            <button type="submit" class="primary-btn w-100 mt-4">Sign In</button>
                        </form>

                    </div>
                </div>
                <div class="col-md-6 p-0">
                    <img src="{{ asset('admin/images/loginScreenImage.jpg') }}" class="w-100 img-fluid" alt="">
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"
        integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script src="{{ asset('admin/JS/main.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#loginForm").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "/api/login",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({
                        email: $("#email").val(),
                        password: $("#password").val()
                    }),
                    success: function(response) {
                        alert("Login successful!");
                        localStorage.setItem("token", response.token);
                        if (response.user.role === "admin") {
                            window.location = "/admin_panel"; 
                        } else {
                            window.location.href = "auth/login";
                        }
                    },
                    error: function(xhr) {
                        alert("Login failed: " + xhr.responseJSON.message);
                    }
                });
            });


            $('.toggle-password').on('click', function() {
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

            if ($('#smessage').length) {
                setTimeout(function() {
                    $('#smessage').fadeOut('slow', function() {
                        $(this).remove();
                    });
                }, 6000);
            }
        });
    </script>

</body>

</html>