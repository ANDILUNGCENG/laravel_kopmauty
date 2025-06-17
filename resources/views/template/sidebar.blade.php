<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="#" class="app-brand-link">
            <span class="app-brand-logo demo">
                {{-- Logo placeholder Bootstrap --}}
                <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="height: 50px; width: auto;">

            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2" style="text-transform: capitalize !important;">Koma UTY</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        {{-- Menu Biasa --}}
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home"></i>
                <div>Dashboard</div>
            </a>
        </li>

        @role('admin')
            <li
                class="menu-item {{ request()->routeIs('users.index') || request()->routeIs('pelanggan.index') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div>Akun</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}" class="menu-link">Daftar User</a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('pelanggan.index') ? 'active' : '' }}">
                        <a href="{{ route('pelanggan.index') }}" class="menu-link">Daftar Pelanggan</a>
                    </li>
                </ul>
            </li>
            <li
                class="menu-item {{ request()->routeIs('pembayaran.index') || request()->routeIs('pajak.index') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-dollar"></i>
                    <div>Pembayaran</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('pembayaran.index') ? 'active' : '' }}">
                        <a href="{{ route('pembayaran.index') }}" class="menu-link">Daftar Pembayaran</a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('pajak.index') ? 'active' : '' }}">
                        <a href="{{ route('pajak.index') }}" class="menu-link">Daftar Pajak</a>
                    </li>
                </ul>
            </li>
            <li
                class="menu-item {{ request()->routeIs('kategori.index') || request()->routeIs('produk') || request()->routeIs('produk.barcode') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-package"></i>
                    <div>Produk</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('kategori.index') ? 'active' : '' }}">
                        <a href="{{ route('kategori.index') }}" class="menu-link">Daftar Kategori</a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('produk') ? 'active' : '' }}">
                        <a href="{{ route('produk') }}" class="menu-link">Daftar Produk</a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('produk.barcode') ? 'active' : '' }}">
                        <a href="{{ route('produk.barcode') }}" class="menu-link">Print Barcode</a>
                    </li>
                </ul>
            </li>
        @endrole
        <li
            class="menu-item {{ request()->routeIs('transaksi.penjualan') || request()->routeIs('transaksi.pembelian') || request()->routeIs('transaksi.create.pembelian') || request()->routeIs('pembelian.edit') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-money"></i>
                <div>Transaksi</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('transaksi.penjualan') ? 'active' : '' }}">
                    <a href="{{ route('transaksi.penjualan') }}" class="menu-link">Penjualan</a>
                </li>
                @hasanyrole('admin|kepalatoko')
                <li
                    class="menu-item {{ request()->routeIs('transaksi.pembelian') || request()->routeIs('transaksi.create.pembelian') || request()->routeIs('pembelian.edit') ? 'active' : '' }}">
                    <a href="{{ route('transaksi.pembelian') }}" class="menu-link">Pembelian</a>
                </li>
                
                @endrole
            </ul>
        </li>

        <li
            class="menu-item {{ request()->routeIs('laporan.transaksi.penjualan') || request()->routeIs('laporan.transaksi.pembelian') || request()->routeIs('laporan.stok') || request()->routeIs('laporan.laba.rugi') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layer"></i>
                <div>Laporan</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('laporan.transaksi.penjualan') ? 'active' : '' }}">
                    <a href="{{ route('laporan.transaksi.penjualan') }}" class="menu-link">Penjualan</a>
                </li>
                @hasanyrole('admin|kepalatoko')
                <li class="menu-item {{ request()->routeIs('laporan.transaksi.pembelian') ? 'active' : '' }}">
                    <a href="{{ route('laporan.transaksi.pembelian') }}" class="menu-link">Pembelian</a>
                </li>
                
                @endrole
                <li class="menu-item {{ request()->routeIs('laporan.stok') ? 'active' : '' }}">
                    <a href="{{ route('laporan.stok') }}" class="menu-link">Stok</a>
                </li>
                @hasanyrole('admin|kepalatoko')
                <li class="menu-item {{ request()->routeIs('laporan.laba.rugi') ? 'active' : '' }}">
                    <a href="{{ route('laporan.laba.rugi') }}" class="menu-link">Laba Rugi</a>
                </li>
                
                @endrole
            </ul>
        </li>

    </ul>
</aside>
