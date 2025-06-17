<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title')</title>

    <meta name="description" content="" />

    <!-- manifest -->
    {{-- <link rel="manifest" href="{{ asset('manifest.json') }}"> --}}

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />

    <!-- Library Ekspor data Table Button -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css"> -->


    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css" rel="stylesheet">

    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}"> --}}

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Helpers -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <style>
        .content-footer {
            position: fixed;
            right: 0;
            bottom: 0;
            left: 16.25rem;
            z-index: 1030;
        }
    </style>

    @yield('css')
</head>

<body class="h-full">
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Sidebar -->

            @if (!request()->is(['login', 'kasir', 'edit-kasir*']))
                @include('template.sidebar')
            @endif
            <!-- / Sidebar -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                @if (!request()->is(['login', 'kasir', 'edit-kasir*']))
                    @include('template.navbar')
                @endif
                @if (request()->is(['edit-kasir*', 'kasir']))
                    @include('template.header_kasir')
                @endif
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <div class="content-body">

                        @yield('content')
                    </div>

                    <!-- Footer -->
                    @if (!request()->is(['login', 'kasir', 'edit-kasir*']))
                        <footer class="content-footer footer bg-footer-theme ">
                            <div
                                class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                                <div class="mb-2 mb-md-0">
                                    © UTY
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>
                                    , MADE WITH ANDI KURNIAWAN
                                    <a href="#" target="_blank" class="footer-link fw-medium">2025</a>
                                </div>
                            </div>
                        </footer>
                    @endif;
                    <!-- / Footer -->
                </div>
                <!-- / Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
        <!-- / Layout container -->

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    {{-- <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script> --}}

    <!-- endbuild -->

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        // Menginisialisasi tooltip di halaman
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
    @if ($message = Session::get('failed'))
        <div class="toast-container position-fixed top-0 end-0 p-3 mt-2 container-notif" style="z-index: 9999999;">
            <div id="liveToast" class="toast align-items-center text-bg-danger border-0 show" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>Failed !</strong>
                        <p class="mb-1 mt-2 ">{{ $message }}</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    @if ($message = Session::get('success'))
        <div class="toast-container position-fixed top-0 end-0 p-3 mt-2 container-notif" style="z-index: 9999999;">
            <div id="liveToast" class="toast align-items-center text-bg-success border-0 show" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>Success !</strong>
                        <p class="mb-1 mt-2 ">{{ $message }}</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto text-dark"
                        data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toastElement = document.querySelector('.toast');
            if (toastElement) {
                setTimeout(function() {
                    toastElement.classList.add('fade-out-toast');

                    setTimeout(function() {
                        var toast = new bootstrap.Toast(toastElement);
                        toast.hide();
                    }, 10000);
                }, 10000);
            }
        });

        function showToast(message = 'Terjadi kesalahan.', type = 'danger', timeout = 10000) {
            let container = document.querySelector('.toast-container');
            if (!container) {
                container = document.createElement('div');
                container.className = 'toast-container position-fixed top-0 end-0 p-3 mt-2';
                container.style.zIndex = 9999999;
                document.body.appendChild(container);
            }

            const toastEl = document.createElement('div');
            toastEl.className = `toast align-items-center text-bg-${type} border-0 fade`;
            toastEl.setAttribute('role', 'alert');
            toastEl.setAttribute('aria-live', 'assertive');
            toastEl.setAttribute('aria-atomic', 'true');

            toastEl.innerHTML = `
                <div class="d-flex">
                <div class="toast-body">
                    <strong>Failed!</strong>
                    <p class="mb-1 mt-2">${message}</p>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;

            container.appendChild(toastEl);

            const toast = new bootstrap.Toast(toastEl, {
                delay: timeout,
                autohide: true
            });
            toast.show();

            toastEl.addEventListener('hidden.bs.toast', () => {
                toastEl.remove();
            });
        }
    </script>
    @stack('scripts')

    @yield('js')
</body>

</html>
