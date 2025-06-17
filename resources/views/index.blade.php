<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    {{-- @vite('resources/css/app.css') --}}
    {{-- <!-- @vite('resources/js/app.js') --> --}}

    <title>@yield('title')</title>

    <meta name="description" content="" />

    <!-- manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/cepatonline.ico') }}" />

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

    @yield('css')

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Helpers -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- Data Table -->
    <!-- <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/js/ui-toasts.js') }}"></script> -->


    {{-- <style>
    .menu .app-brand.demo {
        background-color: {{ Session::get('app_brand_color') }} !important;
    }
    .bg-menu-theme {
        background-color: {{ Session::get('sidebar_color') }} !important;
    }
    .navbar.bg-primary {
        background-color: {{ Session::get('navbar_color') }} !important;
    }
    /* Gaya warna button berdasarkan sesi */
    .custom-btn-color {
        background-color: {{ Session::get('button_color') }} !important;
        color: #fff;
        border-color: {{ Session::get('button_color') }} !important;
    }

    /* Gaya warna hover button berdasarkan sesi */
    .custom-btn-color:hover, 
    .custom-btn-color.show.dropdown-toggle {
        background-color: {{ Session::get('button_hover_color') }} !important;
        color: #fff;
        border-color: {{ Session::get('button_hover_color') }} !important;
    }

    /* Gaya menu link */
    .menu-link {
        color: {{ Session::get('menu_link_color') }} !important;
    }
    
    /* Gaya hover menu link */
    .menu-link:hover {
        color: {{ Session::get('menu_link_hover_color') }} !important; 
    }

    /* Gaya untuk menu aktif */
    .bg-menu-theme .menu-item.active > .menu-link:not(.menu-toggle) {
        background-color: {{ Session::get('button_color') }} !important;
        color: #fff;
    }

  

</style> --}}


</head>

<body class="h-full">
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Sidebar -->

            @if (!request()->is('sales-transaction*'))
                @include('template.sidebar')
            @endif
            <!-- / Sidebar -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                @if (!request()->is('sales-transaction*'))
                    @include('template.navbar')
                @endif
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <div class="content-body">
                        <!-- <div class="mx-auto max-w-full px-4 py-6 sm:px-6 lg:px-8"> -->
                        <div class="mx-auto max-w-full px-3 py-3 sm:px-4 lg:px-6">
                            <div class="row g-2">
                                <div class="col-xxl-8 mb-2 order-0">
                                    <div class="card">
                                        <div class="d-flex align-items-start row">

                                            <div class="col-sm-7">
                                                <div class="card-body">
                                                    <h5 class="card-title text-primary mb-3">Selamat Datang, Johan! 👋
                                                    </h5>
                                                    <p class="mb-1">
                                                        Senang melihatmu kembali! Kami harap harimu produktif dan penuh
                                                        semangat.
                                                    </p>
                                                    <div class="mt-2 d-none">

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-5">
                                                <div class="card-body d-flex justify-content-center pb-0 px-0 px-md-6">
                                                    <img src="{{ asset('assets/img/illustrations/man-with-laptop.png') }}"
                                                        style="max-height: 175px; width: auto;" class="scaleX-n1-rtl"
                                                        alt="Ilustrasi Pengguna">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-4 col-md-4 order-1">
                                    <div class="row g-2">
                                        <div class="col-lg-6 col-md-12 mb-2 col-6">
                                            <div class="card h-100 rounded-3 border-0">
                                                <div class="card-body text-center">
                                                    <div
                                                        class="card-title d-flex align-items-start justify-content-center mb-4">
                                                        <div class="avatar flex-shrink-0">
                                                            <i class="bx bx-line-chart bx-lg icon-dashboard"
                                                                style="color: rgba(0, 123, 255, 0.6);"></i>
                                                        </div>
                                                    </div>
                                                    <p class="mb-1 text-muted">Total Penjualan</p>
                                                    <h4 class="card-title mb-3 text-primary">
                                                        {{ number_format(1, 0, ',', '.') }}</h4>
                                                    <!-- Optional: Add a percentage change indicator -->
                                                    <small class="text-success fw-medium"><i
                                                            class='bx bx-up-arrow-alt'></i> +72.80%</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12 mb-2 col-6">
                                            <div class="card h-100 rounded-3 border-0">
                                                <div class="card-body text-center">
                                                    <div
                                                        class="card-title d-flex align-items-start justify-content-center mb-4">
                                                        <div class="avatar flex-shrink-0">
                                                            <i class="bx bx-wallet bx-lg icon-dashboard"
                                                                style="color: rgba(220, 53, 69, 0.6);"></i>
                                                        </div>
                                                    </div>
                                                    <p class="mb-1 text-muted">Total Pembelian</p>
                                                    <h4 class="card-title mb-3 text-danger">
                                                        {{ number_format(1, 0, ',', '.') }}</h4>
                                                    <!-- Optional: Add a percentage change indicator -->
                                                    <small class="text-success fw-medium"><i
                                                            class='bx bx-up-arrow-alt'></i> +28.42%</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Penjualan per Bulan -->
                                <div class="col-12 col-xxl-8 order-2 order-md-3 mb-2 order-xxl-2 mb-6">
                                    <div class="card">
                                        <div class="row g-0">
                                            <div class="col-lg-12">
                                                <div
                                                    class="card-header d-flex align-items-center justify-content-between">

                                                </div>
                                                <div id="monthlySalesChart" class="px-3"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--/ Total Revenue -->
                                <div class="col-12 col-md-8 col-lg-12 col-xxl-4 mb-2 order-3 order-md-2">
                                    <div class="row g-2">
                                        <div class="col-6 mb-2">
                                            <div class="card h-100 rounded-3 border-0">
                                                <div class="card-body text-center">
                                                    <div
                                                        class="card-title d-flex align-items-start justify-content-center mb-4">
                                                        <div class="avatar flex-shrink-0">
                                                            <i class="bx bx-minus-circle bx-lg icon-dashboard"
                                                                style="color: rgba(220, 53, 69, 0.6);"></i>
                                                        </div>
                                                    </div>
                                                    <p class="mb-1 text-muted">Total Pengeluaran</p>
                                                    <h4 class="card-title mb-3 text-danger">
                                                        {{ number_format(1, 0, ',', '.') }}</h4>
                                                    <!-- Optional: Add a percentage change indicator -->
                                                    <small class="text-danger fw-medium"><i
                                                            class='bx bx-down-arrow-alt'></i> -14.82%</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <div class="card h-100 rounded-3 border-0">
                                                <div class="card-body text-center">
                                                    <div
                                                        class="card-title d-flex align-items-start justify-content-center mb-4">
                                                        <div class="avatar flex-shrink-0">
                                                            <i class="bx bx-refresh bx-lg icon-dashboard"
                                                                style="color: rgba(0, 123, 255, 0.6);"></i>
                                                        </div>
                                                    </div>
                                                    <p class="mb-1 text-muted">Retur Penjualan</p>
                                                    <h4 class="card-title mb-3 text-primary">
                                                        {{ number_format(1, 0, ',', '.') }}</h4>
                                                    <!-- Optional: Add a percentage change indicator -->
                                                    <small class="text-success fw-medium"><i
                                                            class='bx bx-up-arrow-alt'></i> +28.14%</small>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Grafik Produk Terlaris -->
                                        <div class="col-12 mb-2">
                                            <div class="card rounded-3 border-0">
                                                <div class="card-body">

                                                    <div id="bestSellingProductsChart"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <script>
                                    // Data untuk Grafik Produk Terlaris
                                    var options = {
                                        series: [44, 55, 41, 17, 15], // Data penjualan untuk 5 produk terlaris
                                        chart: {
                                            type: 'pie',
                                            height: 195,
                                        },
                                        labels: ['Produk Premium Bergaransi Toko A', 'Produk B', 'Produk C', 'Produk D', 'Produk E'], // Nama produk
                                        title: {
                                            text: '5 Produk Terlaris',
                                            align: 'center',
                                            style: {
                                                fontSize: '16px',
                                                color: '#666'
                                            }
                                        },
                                        colors: ['#FF4560', '#008FFB', '#00E396', '#775DD0', '#FEB019'], // Warna untuk setiap segmen
                                        responsive: [{
                                            breakpoint: 480,
                                            options: {
                                                chart: {
                                                    width: '100%'
                                                },
                                                legend: {
                                                    position: 'bottom'
                                                }
                                            }
                                        }]
                                    };

                                    var chart = new ApexCharts(document.querySelector("#bestSellingProductsChart"), options);
                                    chart.render();
                                </script>

                            </div>
                            @yield('content')
                        </div>
                    </div>


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
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

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

    @yield('js')
    @stack('scripts')

</body>

</html>
