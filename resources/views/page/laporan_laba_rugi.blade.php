@extends('template.main')
@section('title', $title ?? 'Web Kasir')

@section('content')
    <header class="bg-white shadow">
        <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8" style="height: 55px; display: flex; align-items: center;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Laporan Laba Rugi</li>
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
                                    <a href="{{ route('laporan.laba.rugi') }}"
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
                        <h5 class="mb-0">Laporan Laba Rugi</h5>
                    </div>
                    <div class="container">
                        <div class="card-datatable table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Keterangan</th>
                                        <th>Jumlah (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-center" style="background-color : #f8f8f8;">
                                        <td colspan="2"><strong>Pendapatan</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Penjualan <small class="fst-italic text-muted">(Pajak)</small></td>
                                        <td>Rp {{ number_format($totalPenjualanPajak, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Penjualan <small class="fst-italic text-muted">(Non-Pajak)</small></td>
                                        <td>Rp {{ number_format($totalPenjualanNonPajak, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Pajak Penjualan</td>
                                        <td>Rp {{ number_format($totalPajakPenjualan, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                    <tr>
                                        <td>Total Penjualan <small class="fst-italic text-muted">(Bersih dikurangi
                                                pajak)</small></td>
                                        <td class="fw-semibold">Rp {{ number_format($totalPenjualanBersih, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Pendapatan Penjualan</strong> <small
                                                class="fst-italic text-muted">(Pajak + Non-Pajak)</small></td>
                                        <td class="fw-semibold">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</td>
                                    </tr>
                                    <td> <strong>Modal Barang </strong>
                                        <em><small class="text-muted">(Modal pembelian barang
                                                yang sudah
                                                terjual)</small></em>
                                    </td>
                                    <td class="fw-semibold">Rp -
                                        {{-- murni modal pembelian yang barangnya sudah terjual ini modal beli ya bukan keuntungan harga beli --}}
                                    </td>
                                    </tr>
                                    <tr class="text-center" style="background-color : #f8f8f8;">
                                        <td colspan="2"><strong>Pembelian</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Pembelian <small class="fst-italic text-muted">(Pajak)</small></td>
                                        <td>Rp {{ number_format($totalPembelianPajak, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Pembelian <small class="fst-italic text-muted">(Non-Pajak)</small></td>
                                        <td>Rp {{ number_format($totalPembelianNonPajak, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Pajak Pembelian</td>
                                        <td>Rp {{ number_format($totalPajakPembelian, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Pembelian <small class="fst-italic text-muted">(Bersih dikurangi
                                                pajak)</small></td>
                                        <td class="fw-semibold">Rp {{ number_format($totalPembelianBersih, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Modal Pembelian</strong> <small class="fst-italic text-muted">(Pajak +
                                                Non-Pajak)</small></td>
                                        <td class="fw-semibold">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Modal Tertahan</strong> <small class="fst-italic text-muted">(Modal
                                                pembelian
                                                barang yang belum terjual)</small> </td>
                                        <td class="fw-semibold">Rp -()
                                            {{-- murni modal pembelian yang barangnya masih di stok jadi barannya aja ya beli (harga beli) --}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><strong class="fs-5">Potensi Laba</strong> <small
                                                class="fst-italic text-muted">(Laba jika
                                                semua barang terjual)</small></td>
                                        <td class="text-start"><strong class="fs-5">Rp
                                                {{ number_format($potensiLaba, 0, ',', '.') }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><strong class="fs-5">Laba Realisasi</strong>
                                            <small class="fst-italic text-muted">(Laba barang yang sudah terjual)</small>
                                        </td>
                                        <td class="text-start"><strong class="fs-5">Rp
                                                {{ number_format($totalLabaPenjualan, 0, ',', '.') }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><strong class="fs-5">Selisih Pajak</strong> <small
                                                class="fst-italic text-muted">(Pajak
                                                penjualan dikurangi pajak pembelian)</small></td>
                                        <td class="text-start"><strong class="fs-5">Rp
                                                {{ number_format($totalSelisihPajak, 0, ',', '.') }}</strong></td>
                                    </tr>
                                </tbody>
                                <thead class="table-secondary">
                                    <tr>
                                        <th class="text-center fs-5"><strong>Posisi Laba</strong></th>
                                        <th class="text-end fs-5"><strong>Rp
                                                {{ number_format($laba, 0, ',', '.') }}</strong></th>
                                    </tr>
                                </thead>
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
            const url = `{{ route('transaksi.pdf.labaRugi') }}?start_date=${startDate}&end_date=${endDate}`;
            window.open(url, '_blank');
        });
    </script>
@endsection
