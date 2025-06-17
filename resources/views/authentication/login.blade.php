@extends('template.main')
@section('title', $title ?? 'Web Kasir')

@section('content')
    <main>
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                        <div class="d-flex justify-content-center py-4">
                            <a href="{{ url('/') }}" class="logo d-flex align-items-center w-auto">
                                <img src="{{ asset('assets/img/logo.png') }}" class="rounded" alt="" width="40">
                                <span class="d-none d-lg-block fs-5 fw-bold ms-2">Toko Koma UTY Store</span>
                            </a>
                        </div><!-- End Logo -->

                        <div class="card mb-3">
                            <div class="card-body">

                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Login</h5>
                                </div>

                                <form action="{{ url('/proses-login') }}" class="row g-3 needs-validation" method="POST">
                                    @csrf

                                    <div class="col-12">
                                        <label for="yourUsername" class="form-label">Email</label>
                                        <div class="input-group has-validation">
                                            <input type="email" name="email" class="form-control"
                                                value="{{ old('email') }}">
                                            @error('email')
                                                <div class="invalid-feedback mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="yourPassword" class="form-label">Password</label>
                                        <div class="input-group">
                                            <input type="password" name="password" class="form-control" id="password"
                                                required>
                                            <button class="btn btn-secondary" type="button" id="togglePassword">
                                                <i class="bx bx-hide"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    {{-- <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" value="true"
                                                id="rememberMe">
                                            <label class="form-check-label" for="rememberMe">Remember me</label>
                                        </div>
                                    </div> --}}
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit">Login</button>
                                    </div>
                                    {{-- <div class="col-12">
                                        <p class="small mb-0">Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
                                        </p>
                                    </div> --}}
                                </form>

                            </div>
                        </div>

                        <div class="credits">
                            <!-- All the links in the footer should remain intact. -->
                            <!-- You can delete the links only if you purchased the pro version. -->
                            <!-- Licensing information: https://bootstrapmade.com/license/ -->
                            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                            {{-- Designed by <a href="https://diuscode.my.id/">DiusCode</a> --}}
                        </div>

                    </div>
                </div>
            </div>

        </section>


    </main>
@endsection
@section('js')
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            let passwordInput = document.getElementById('password');
            let icon = this.querySelector('i');

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            } else {
                passwordInput.type = "password";
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            }
        });
    </script>
@endsection
