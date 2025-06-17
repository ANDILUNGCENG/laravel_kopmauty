@extends('template.main')
@section('title', $title ?? 'Web Kasir')

@section('content')
    <header class="bg-white shadow">
        <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8" style="height: 55px; display: flex; align-items: center;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    {{-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li> --}}
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
    </header>
    <div class="mx-auto max-w-full px-3 py-3 sm:px-4 lg:px-6">
        <div class="row g-2">
            <div class="col-12 mb-2 order-0">
                <div class="card">
                    <div class="d-flex align-items-start row">

                        <div class="col-sm-7"> {{-- sm-7 jika menggunakan gambar --}}
                            <div class="card-body">
                                <h5 class="card-title text-primary mb-3">Selamat Datang, {{ Auth::user()->name }} ! 👋
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
                            {{-- <div class="card-body d-flex justify-content-center pb-0 px-0 px-md-6">
                                <img src="{{ asset('assets/img/illustrations/man-with-laptop.png') }}"
                                    style="max-height: 175px; width: auto;" class="scaleX-n1-rtl" alt="Ilustrasi Pengguna">
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-12 order-1">
                <div class="row g-2">
                    @hasanyrole('admin|kepalatoko')
                        <div class="col-lg-6 col-md-12 mb-2 col-6">
                            <div class="card h-100 rounded-3 border-0">
                                <div class="card-body text-center">
                                    <div class="card-title d-flex align-items-start justify-content-center mb-4">
                                        <div class="avatar flex-shrink-0">
                                            <i class="bx bx-line-chart bx-lg icon-dashboard"
                                                style="color: rgba(0, 123, 255, 0.6);"></i>
                                        </div>
                                    </div>
                                    <p class="mb-1 text-muted">Total Penjualan</p>
                                    <h4 class="card-title mb-3 text-primary">Rp.
                                        {{ number_format($totalPenjualan, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12 mb-2 col-6">
                            <div class="card h-100 rounded-3 border-0">
                                <div class="card-body text-center">
                                    <div class="card-title d-flex align-items-start justify-content-center mb-4">
                                        <div class="avatar flex-shrink-0">
                                            <i class="bx bx-wallet bx-lg icon-dashboard"
                                                style="color: rgba(220, 53, 69, 0.6);"></i>
                                        </div>
                                    </div>
                                    <p class="mb-1 text-muted">Total Pembelian</p>
                                    <h4 class="card-title mb-3 text-danger">
                                        Rp.
                                        {{ number_format($totalPembelian, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                    @endrole
                    @hasanyrole('kasir')
                        <div class="col-lg-12 col-md-12 mb-2 col-12">
                            <div class="card h-100 rounded-3 border-0">
                                <div class="card-body text-center">
                                    <div class="card-title d-flex align-items-start justify-content-center mb-4">
                                        <div class="avatar flex-shrink-0">
                                            <i class="bx bx-line-chart bx-lg icon-dashboard"
                                                style="color: rgba(0, 123, 255, 0.6);"></i>
                                        </div>
                                    </div>
                                    <p class="mb-1 text-muted">Total Penjualan</p>
                                    <h4 class="card-title mb-3 text-primary">Rp.
                                        {{ number_format($totalPenjualan, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                    @endrole
                </div>
            </div>


        </div>
    </div>
@endsection
