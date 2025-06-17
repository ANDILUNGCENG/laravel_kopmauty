@extends('template.main')
@section('title', $title ?? 'Web Kasir')

@section('content')
    <header class="bg-white shadow">
        <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8" style="height: 55px; display: flex; align-items: center;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Penjualan</li>
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
                                    <a href="{{ route('transaksi.penjualan') }}"
                                        class="btn btn-danger me-2 custom-btn-color">Hapus</a>
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
                        <h5 class="mb-0">Daftar Penjualan</h5>
                        <a href="{{ route('kasir') }}" class="btn btn-primary">Tambah
                            Penjualan</a>
                    </div>
                    <div class="container">
                        <div class="card-datatable table-responsive">
                            <table class="datatables table border-top table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Pelanggan</th>
                                        <th>Total</th>
                                        <th>Bayar</th>
                                        <th>Kembalian</th>
                                        <th>Bukti</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaksis as $key => $t)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $t->pelanggan->nama ?? '-' }}</td>
                                            <td>Rp{{ number_format($t->total, 0, ',', '.') }}</td>
                                            <td>Rp{{ number_format($t->bayar, 0, ',', '.') }}</td>
                                            <td>Rp{{ number_format($t->kembalian, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($t->bukti)
                                                    <img src="{{ asset('storage/' . $t->bukti) }}" alt="bukti"
                                                        width="50"
                                                        class="img-thumbnail show-image bg-light p-1 rounded link"
                                                        data-src="{{ asset('storage/' . $t->bukti) }}">
                                                @endif
                                            </td>
                                            <td>{{ $t->tanggal }}</td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-6 mb-2 text-center">
                                                        <a href="{{ route('transaksi.nota', $t->id) }}" target="a_blank"
                                                            class="btn btn-sm btn-info">
                                                            Nota
                                                        </a>
                                                    </div>
                                                    <div class="col-6 mb-2 text-center">
                                                        <button class="btn btn-sm btn-primary btn-detail"
                                                            data-bs-toggle="modal" data-bs-target="#modalDetail"
                                                            data-transaksi='@json($t)'
                                                            data-detail='@json($t->detailTransaksis)'>
                                                            Detail
                                                        </button>
                                                    </div>
                                                    <div class="col-6 mb-2 text-center">
                                                        <a href="{{ route('kasir.edit', $t->id) }}"
                                                            class="btn btn-sm btn-warning">
                                                            Edit
                                                        </a>
                                                    </div>
                                                    <div class="col-6 mb-2 text-center">
                                                        <button class="btn btn-sm btn-danger btn-delete"
                                                            data-id="{{ $t->id }}"
                                                            data-nama="{{ $t->pelanggan->nama ?? 'Transaksi #' . $t->id }}"
                                                            data-bs-toggle="modal" data-bs-target="#modalHapus">
                                                            Hapus
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
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
    <!-- ✅ Modal tunggal -->
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="detail-body">
                            {{-- Diisi dengan JS --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="hapusLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="formHapus" action="">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Yakin ingin menghapus transaksi <strong id="hapusNama"></strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- Modal Gambar --}}
    <div class="modal fade" id="modalImage" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0 text-center">
                <img src="" class="img-fluid rounded shadow" id="imgPreview" alt="preview">
            </div>
        </div>
    </div>
    {{--  --}}

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
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-detail').forEach(button => {
                button.addEventListener('click', function() {
                    const details = JSON.parse(this.dataset.detail);
                    const body = document.getElementById('detail-body');
                    body.innerHTML = '';

                    details.forEach(item => {
                        const row = document.createElement('tr');

                        row.innerHTML = `
                        <td>${item.produk?.nama ?? '-'}</td>
                        <td>Rp${parseInt(item.harga).toLocaleString('id-ID')}</td>
                        <td>${item.jumlah}</td>
                        <td>Rp${parseInt(item.total).toLocaleString('id-ID')}</td>
                    `;

                        body.appendChild(row);
                    });
                });
            });
            // 
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const nama = this.dataset.nama;
                    const form = document.getElementById('formHapus');
                    const span = document.getElementById('hapusNama');

                    form.action = `/transaksi-destroy/${id}`; // ganti dengan route sesuai kebutuhan
                    span.textContent = nama;
                });
            });
        });
    </script>
    <script>
        // Gambar Modal
        $('.show-image').on('click', function() {
            const src = $(this).data('src');
            $('#imgPreview').attr('src', src);
            $('#modalImage').modal('show');
        });
    </script>

@endsection
