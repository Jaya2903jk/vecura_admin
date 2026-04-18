<?php $page = 'login'; ?>
@extends('layout.mainlayout')
@section('content')
    <!-- Start Content -->
    <div class="container-fuild position-relative z-1">
        <div class="w-100 overflow-hidden position-relative flex-wrap d-block vh-100">

            <!-- start row -->
            <div class="row justify-content-center align-items-center vh-100 overflow-auto flex-wrap py-3">
                <div class="col-lg-4 mx-auto">
                    <form id="loginForm" method="POST" class="d-flex justify-content-center align-items-center">
                        @csrf
                        <div class="d-flex flex-column justify-content-lg-center p-4 p-lg-0 pb-0 flex-fill">

                            <div class=" mx-auto mb-4 text-center">
                                <img src="{{ URL::asset('https://www.vecurawellness.com/Lassets/img/logo.webp') }}"
                                    class="img-fluid" alt="Logo">
                            </div>
                            <div class="card border-1 p-lg-3 shadow-md rounded-3 mb-4">
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <h5 class="mb-1 fs-20 fw-bold">Sign In</h5>
                                        <p class="mb-0">Please enter below details to access the dashboard</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Employee ID</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-end-0 bg-white">
                                                <i class="ti ti-mail fs-14 text-dark"></i>
                                            </span>
                                            <input type="text" value="" name="login" autoComplete="off"
                                                class="form-control border-start-0 ps-0" placeholder="Enter Employee ID"
                                                required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <div class="position-relative">
                                            <div class="pass-group input-group position-relative border rounded">
                                                <span class="input-group-text bg-white border-0">
                                                    <i class="ti ti-lock text-dark fs-14"></i>
                                                </span>
                                                <input type="password" name="password"
                                                    class="pass-input form-control ps-0 border-0"
                                                    placeholder="****************" required>
                                                <span class="input-group-text bg-white border-0">
                                                    <i class="ti toggle-password ti-eye-off text-dark fs-14"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="form-check form-check-md mb-0">
                                                <input class="form-check-input" id="remember_me" type="checkbox">
                                                <label for="remember_me" class="form-check-label mt-0 text-dark">Remember
                                                    Me</label>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <a href="{{ url('forgot-password-basic') }}" class="text-danger">Forgot
                                                Password?</a>
                                        </div>
                                    </div>
                                    <div id="alertBox" class="mb-3"></div>

                                    {{-- <div id="alertBox"></div> --}}
                                    <div class="mb-2">
                                        <button type="submit" id="loginBtn" class="btn bg-primary text-white w-100"> <span
                                                class="btn-text">Login</span>
                                            <span class="spinner-border spinner-border-sm d-none"></span></button>
                                    </div>

                                    {{-- <div class="text-center">
                                        <h6 class="fw-normal fs-14 text-dark mb-0">Don’t have an account yet?
                                            <a href="{{url('register-basic')}}" class="hover-a"> Register</a>
                                        </h6>
                                    </div> --}}
                                </div><!-- end card body -->
                            </div><!-- end card -->
                        </div>
                    </form>
                    <p class="text-dark text-center"> Copyright &copy; 2026 - Vecura </p>
                </div><!-- end col -->
            </div>
            <!-- end row -->
        </div>
    </div>
    <!-- End Content -->
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const form = document.getElementById("loginForm");
        const btn = document.getElementById("loginBtn");

        form.addEventListener("submit", async function(e) {
            e.preventDefault();

            clearAlert();
            setLoading(true);

            const formData = new FormData(form);

            try {
                const response = await fetch("{{ route('login.post') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: formData
                });

                const data = await response.json();
                if (response.ok && data.status) {
                    showSuccess("Login successful! Redirecting...");
                    // Disable everything
                    form.querySelectorAll("input, button").forEach(el => el.disabled = true);
                    // Change button text
                    btn.querySelector(".btn-text").textContent = "Redirecting...";

                    // Redirect
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1200);

                } else {
                    setLoading(false);
                    showError(data.message || "Login failed");
                }

            } catch (error) {
                console.error(error);
                setLoading(false);
                showError("Something went wrong. Please try again.");
            }
        });


        function showError(message) {
            const box = document.getElementById("alertBox");

            box.innerHTML = `
        <div class="alert alert-danger fade show">
            ${message}
        </div>
    `;

            // ⏳ Auto hide after 3 seconds
            setTimeout(() => {
                box.innerHTML = "";
            }, 3000);
        }

        // 🟢 SUCCESS ALERT
        function showSuccess(message) {
            document.getElementById("alertBox").innerHTML = `
            <div class="alert alert-success">
                ${message}
            </div>
        `;
        }

        function clearAlert() {
            document.getElementById("alertBox").innerHTML = "";
        }

        // 🔄 LOADING STATE
        function setLoading(state) {
            const spinner = btn.querySelector(".spinner-border");
            const text = btn.querySelector(".btn-text");

            if (state) {
                spinner.classList.remove("d-none");
                text.textContent = "Logging in...";
                btn.disabled = true;
            } else {
                spinner.classList.add("d-none");
                text.textContent = "Login";
                btn.disabled = false;
            }
        }

    });
</script>
