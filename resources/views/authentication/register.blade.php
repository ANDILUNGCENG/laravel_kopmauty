@extends('layout.main')

@section('content')
    <main>
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                        <div class="d-flex justify-content-center py-4">
                            <a href="{{ url('/') }}" class="logo d-flex align-items-center w-auto">
                                <img src="{{ asset('template/assets/img/logo.png') }}" alt="">
                                <span class="d-none d-lg-block fs-6">BATIK GIRILOYO</span>
                            </a>
                        </div><!-- End Logo -->

                        <div class="card mb-3">
                            <div class="card-body">

                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Registrasi</h5>
                                </div>

                                <form class="row g-3" method="POST" action="{{ route('proses-register') }}">

                                    @csrf
                                    <div class="col-12">
                                        <label for="yourName" class="form-label">Nama</label>
                                        <input type="text" name="name" class="form-control" id="yourName"
                                            value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="yourEmail" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" id="yourEmail"
                                            value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="yourPassword" class="form-label">Password</label>
                                        <div class="input-group">
                                            <input type="password" name="password" class="form-control" id="password"
                                                required>
                                            <button class="btn btn-secondary" type="button" id="togglePassword">
                                                <i class="bi bi-eye-slash"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="yourPassword" class="form-label">Konfirmasi Password</label>
                                        <div class="input-group">
                                            <input type="password" name="password_confirmation" class="form-control"
                                                id="confirm_password" required>
                                            <button class="btn btn-secondary" type="button" id="toggleConfirmPassword">
                                                <i class="bi bi-eye-slash"></i>
                                            </button>
                                        </div>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit">Buat Akun</button>
                                    </div>

                                    <div class="col-12">
                                        <p class="small mb-0">Sudah punya akun? <a href="{{ route('login') }}">Log in</a>
                                        </p>
                                    </div>
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
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                passwordInput.type = "password";
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        });
        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            let passwordInput = document.getElementById('confirm_password');
            let icon = this.querySelector('i');

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                passwordInput.type = "password";
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        });
    </script>
@endsection
