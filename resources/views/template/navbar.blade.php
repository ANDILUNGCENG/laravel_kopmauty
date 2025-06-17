<nav class="navbar navbar-expand-lg navbar-light bg-primary"
    style="flex-shrink: 0; padding-top: 0.2rem; padding-bottom: 0.2rem;">
    <div class="container-fluid">
        <div class="layout-menu-toggle navbar-nav align-items-center me-2 me-xl-0 d-flex d-xl-none">
            <a class="nav-item nav-link px-0" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>

        <div class="navbar-nav align-items-center flex-grow-1 d-flex">
            <div class="nav-item d-flex align-items-center w-100">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('kasir') }}" class="btn btn-warning">
                        <i class="bx bx-cart-alt"></i>
                    </a>
                    <a href="{{ route('laporan.transaksi.penjualan') }}" class="btn btn-success">
                        <i class="bx bx-dollar-circle"></i>
                    </a>
                </div>
            </div>
        </div>

        <ul class="navbar-nav flex-row align-items-center ms-auto position-relative">
            <!-- Stok Rendah Dummy -->
            <li class="nav-item navbar-dropdown dropdown me-3">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar position-relative">
                        <i class="menu-icon tf-icons bx bx-package text-light fs-2"></i>
                        @if ($lowStockProducts->isNotEmpty())
                            <span
                                class="badge bg-warning rounded-circle position-absolute bottom-0 pt-1 start-100 translate-middle">
                                {{ $lowStockProducts->count() }}
                            </span>
                        @endif
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" style="z-index: 1000; min-width: 250px;">
                    @if ($lowStockProducts->isEmpty())
                        <li>
                            <a class="dropdown-item text-center text-muted" href="#">
                                <i class="bx bx-check-circle text-success me-1"></i> Semua stok aman
                            </a>
                        </li>
                    @else
                        <li class="dropdown-header text-center fw-bold text-dark">
                            <i class="bx bx-error-circle text-danger me-1"></i> Stok Rendah
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        
                        @foreach ($lowStockProducts as $product)
                            <li>
                                
                    @hasanyrole('admin|kepalatoko')
                                <a class="dropdown-item d-flex align-items-center"
                                    href="{{ route('produk', ['search' => $product->barcode]) }}">
                                    <div class="me-2">
                                        <i class="bx bx-package text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-medium d-block">{{ $product->nama_produk }}</span>
                                        <small class="text-danger">Sisa stok: {{ $product->total_stok }}</small>
                                    </div>
                                </a>
                                @else
                                <a class="dropdown-item d-flex align-items-center"
                                    href="#">
                                    <div class="me-2">
                                        <i class="bx bx-package text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-medium d-block">{{ $product->nama_produk }}</span>
                                        <small class="text-danger">Sisa stok: {{ $product->total_stok }}</small>
                                    </div>
                                </a>
                                @endrole

                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </li>

            <!-- User Profile -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('assets/img/user.png') }}" alt="Avatar"
                            class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end mt-2" style="min-width: 200px;">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('assets/img/user.png') }}" alt="Avatar"
                                            class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-medium d-block">{{ Auth::user()->name }}</span>
                                    <small class="text-muted">{{ Auth::user()->getRoleNames()->first() }}
                                    </small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Log Out</span>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>

                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
