@extends('template.main')
@section('title', $title ?? 'Web Kasir')

@section('content')
    <header class="bg-white shadow">
        <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8" style="height: 55px; display: flex; align-items: center;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Laporan Stok</li>
                </ol>
            </nav>
        </div>
    </header>
    <div class="mx-auto max-w-full px-3 py-3 sm:px-4 lg:px-6">
        <div class="row g-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form id="filterForm" method="GET">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                        value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="end_date" class="form-label">Tanggal Akhir</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                        value="{{ request('end_date') }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-center justify-content-around">
                                    <button type="submit" class="btn btn-primary me-2 custom-btn-color">Filter</button>
                                    <a href="{{ route('laporan.stok') }}"
                                        class="btn btn-danger me-2 custom-btn-color">Hapus</a>
                                    <button type="button" id="printPdfBtn" class="btn btn-info me-2 custom-btn-color">
                                        Print
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12">
                @if (request('start_date') || request('end_date'))
                    <div class="alert alert-info">
                        Menampilkan data dari <strong>{{ request('start_date') }}</strong> sampai
                        <strong>{{ request('end_date') }}</strong>
                    </div>
                @endif

            </div>
            <div class="col-12 mb-2 ">
                <div class="card">
                    <div class="d-flex justify-content-between align-items-center p-3">
                        <h5 class="mb-0">Laporan Stok</h5>
                    </div>
                    <div class="container">
                        <div class="card-datatable table-responsive">
                            <table class="datatables table border-top table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Produk</th>
                                        <th>Stok</th>
                                        <th>Pembelian</th>
                                        <th>Penjualan</th>
                                        {{-- <th>Penyesuaian</th> --}}
                                        {{-- <th>Stok Akhir</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($laporanStok as $laporan)
                                        <tr>
                                            <td>{{ $laporan['nama_produk'] }}</td>
                                            <td>{{ $laporan['stok'] }}</td>
                                            <td>{{ $laporan['pembelian'] }}</td>
                                            <td>{{ $laporan['penjualan'] }}</td>
                                            {{-- <td>{{ $laporan['penyesuaian'] }}</td> --}}
                                            {{-- <td>{{ $laporan['stok_akhir'] }}</td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <!-- jQuery (wajib) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.datatables').DataTable({
                responsive: true,
                autoWidth: false,
            });
        });
    </script>
    <script>
        document.getElementById('printPdfBtn').addEventListener('click', function() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            // URL akan tetap valid meskipun tanggal kosong
            const url = `{{ route('transaksi.pdf.stok') }}?start_date=${startDate}&end_date=${endDate}`;
            window.open(url, '_blank');
        });
    </script>

@endsection
