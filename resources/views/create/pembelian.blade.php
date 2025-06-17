@extends('template.main')
@section('title', $title ?? 'Web Kasir')

@section('css')
    <style>
        .select2-container--default .select2-selection--single {
            border: 1px solid #ced4da;
            border-radius: 0 0.375rem 0.375rem 0;
            box-shadow: none;
            height: calc(2.25rem + 2px);
            /* Sesuaikan dengan tinggi input-group */
            line-height: 2.25rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding: 0 0.75rem;
            line-height: 2.25rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(2.25rem + 2px);
            /* Sesuaikan dengan tinggi input-group */
            top: 0;
        }

        .input-group .select2-container {
            width: auto !important;
            flex: 1 1 auto;
        }
    </style>
    <style>
        .select2-container--default .select2-dropdown {
            z-index: 999999 !important;
        }
    </style>

@endsection
@section('content')
    <header class="bg-white shadow">
        <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8" style="height: 55px; display: flex; align-items: center;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('transaksi.pembelian') }}">Pembelian</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah Pembelian</li>
                </ol>
            </nav>
        </div>
    </header>
    <div class="mx-auto max-w-full px-3 py-3 sm:px-4 lg:px-6">
        <div class="row g-2">
            <div class="col-12 mb-2 order-0">
                <div class="card">
                    <!-- Tambah Pembelian Header & Modal -->
                    <div class="d-flex justify-content-between align-items-center p-3">
                        <h5 class="mb-0">Tambah Pembelian</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah
                            Produk</button>

                        <!-- Modal Tambah Produk -->
                        <div class="modal fade" id="modalTambah" tabindex="-1">
                            <div class="modal-dialog">
                                <form class="modal-content">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tambah Produk</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="produk">Produk</label>
                                            <div class="input-group">
                                                <span class="input-group-text" style="background-color: #f8f9fa;">
                                                    <i class='bx bxs-offer' style="color: #28a745;"></i>
                                                </span>
                                                <select class="form-select select2" id="produkSelect">
                                                    <option value="" selected>Pilih Produk</option>
                                                    @foreach ($produks as $p)
                                                        <option value="{{ $p->id }}"
                                                            data-harga="{{ $p->harga_beli }}"
                                                            data-nama="{{ $p->nama }}" data-stok="{{ $p->stok }}">
                                                            {{ $p->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-success" id="btnTambahProduk">Tambah</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Form Pembelian -->
                    <div class="container">
                        <form method="POST" action="{{ route('transaksi.store.pembelian') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <table class="table table-striped border-top" id="tabel-pembelian">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Produk</th>
                                                    <th>Harga</th>
                                                    <th>Jumlah</th>
                                                    <th>Total</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <label for="pajak">Pajak (%)</label>
                                        <div class="input-group">
                                            <span class="input-group-text" style="background-color: #f8f9fa;">
                                                <i class='bx bxs-offer' style="color: #28a745;"></i>
                                            </span>
                                            <select class="form-select select2" name="pajak_id" id="pajakSelect">
                                                <option value="" selected>Pilih Pajak</option>
                                                @foreach ($pajaks as $p)
                                                    <option value="{{ $p->id }}" data-value="{{ $p->nilai }}">
                                                        {{ $p->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-8 text-end">
                                        <h6 id="jumlahTotal">Jumlah: Rp 0</h6>
                                        <h5 class="fw-semibold" id="totalPajak">Total + Pajak: Rp 0</h5>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-3">
                                        <label>Pembayaran</label>
                                        <div class="input-group">
                                            <span class="input-group-text" style="background-color: #f8f9fa;">
                                                <i class="menu-icon tf-icons bx bx-money"></i>
                                            </span>
                                            <select class="form-select select2" name="pembayaran_id" id="pembayaranSelect"
                                                required>
                                                <option value="" selected>Pilih Pembayaran</option>
                                                @foreach ($pembayarans as $p)
                                                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="mb-3 d-none" id="buktiWrapper">
                                            <label>Bukti (optional)</label>
                                            <input type="file" name="bukti" id="bukti" class="form-control">
                                            <div id="previewBukti" class="mt-2"></div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <label>Bayar</label>
                                        <input type="number" name="bayar" id="bayar" class="form-control" required>
                                    </div>
                                    <div class="col-3">
                                        <label>Kembalian</label>
                                        <input type="number" name="kembalian" id="kembalian" class="form-control"
                                            readonly required>
                                        <div id="paymentForm_warning" class="text-danger small mt-1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-success" type="submit">Simpan</button>
                            </div>
                        </form>
                    </div>
                    {{--  --}}
                </div>
            </div>
        </div>
    </div>
    {{--  --}}
    <div class="toast-container position-fixed top-0 end-0 p-3 mt-2 container-notif" style="z-index: 9999999;">
        <div id="stokToast" class="toast align-items-center text-bg-danger border-0" role="alert"
            aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
            <div class="d-flex">
                <div class="toast-body">
                    <strong>Gagal!</strong>
                    <p class="mb-1 mt-2">Jumlah melebihi stok yang tersedia.</p>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <!-- jQuery (wajib) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <!-- barcode JS -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Pilih Opsi",
                allowClear: true,
                width: '100%',
            });
        });
    </script>
    <script>
        let produkList = [];

        $('#btnTambahProduk').on('click', function() {
            const selected = $('#produkSelect').find(':selected');
            const id = selected.val();
            const nama = selected.data('nama');
            const harga = parseInt(selected.data('harga'));
            const stok = parseInt(selected.data('stok'));

            if (!id) return;

            let row = produkList.find(item => item.id == id);
            if (row) {
                // if (row.jumlah < row.stok) {
                row.jumlah++;
                // } else {
                //     showStokToast();
                // alert("Jumlah melebihi stok tersedia!");
                //     return;
                // }
            } else {
                produkList.push({
                    id,
                    nama,
                    harga,
                    stok,
                    jumlah: 1
                });
            }

            updateTable();
            $('#modalTambah').modal('hide');
        });

        function updateTable() {
            let tbody = $('#tabel-pembelian tbody');
            tbody.empty();
            let total = 0;

            produkList.forEach((item, index) => {
                const subTotal = item.harga * item.jumlah;
                total += subTotal;

                tbody.append(`
                    <tr data-id="${item.id}">
                        <td>
                            <input type="hidden" name="produk_id[]" value="${item.id}">
                            ${item.nama}
                        </td>
                        <td>Rp ${item.harga.toLocaleString()}</td>
                        <td>
                            <input type="number" class="form-control jumlah-input"
                                name="jumlah[]" value="${item.jumlah}" min="1" >
                            <small class="text-muted">Stok: ${item.stok}</small>
                            <small class="text-danger notif-max"></small>
                        </td>
                        <td>
                            <input type="hidden" name="total[]" value="${subTotal}">
                            Rp ${subTotal.toLocaleString()}
                        </td>
                        <td><button type="button" class="btn btn-danger btn-sm delete-btn">-</button></td>
                    </tr>
                `);
            });

            $('#jumlahTotal').text('Jumlah: Rp ' + total.toLocaleString());
            hitungTotalPajak();
        }

        $(document).on('input', '.jumlah-input', function() {
            const tr = $(this).closest('tr');
            const id = tr.data('id');
            const jumlah = parseInt($(this).val()) || 0;
            const item = produkList.find(p => p.id == id);

            if (item) {
                // if (jumlah > item.stok) {
                //     showStokToast();
                //     $(this).val(item.stok); // batasi input
                //     item.jumlah = item.stok;
                // } else {
                item.jumlah = jumlah;
                // }

                const subTotal = item.harga * item.jumlah;
                tr.find('input[name="total[]"]').val(subTotal);
                tr.find('td:eq(3)').html(`Rp ${subTotal.toLocaleString()}`);
            }

            hitungTotalPajak();
        });

        function showStokToast() {
            const toastEl = document.getElementById('stokToast');
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }


        $(document).on('click', '.delete-btn', function() {
            const tr = $(this).closest('tr');
            const id = tr.data('id');
            produkList = produkList.filter(p => p.id != id);
            updateTable();
        });

        $('#pajakSelect').on('change', function() {
            hitungTotalPajak();
        });

        function hitungTotalPajak() {
            let total = produkList.reduce((sum, item) => sum + (item.harga * item.jumlah), 0);
            let pajak = parseFloat($('#pajakSelect').find(':selected').data('value')) || 0;
            let totalWithPajak = total + (total * pajak / 100);

            $('#jumlahTotal').text('Jumlah: Rp ' + total.toLocaleString());
            $('#totalPajak').text('Total + Pajak: Rp ' + totalWithPajak.toLocaleString());
        }

        $('#pembayaranSelect').on('change', function() {
            const value = $(this).val();
            if (value == '2') {
                $('#buktiWrapper').removeClass('d-none');
            } else {
                $('#buktiWrapper').addClass('d-none');
                $('#previewBukti').empty();
            }
        });

        $('#bukti').on('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewBukti').html(`<img src="${e.target.result}" class="img-thumbnail" width="150">`);
                };
                reader.readAsDataURL(file);
            }
        });

        $('#bayar').on('input', function() {
            const warningDiv = document.getElementById('paymentForm_warning');
            let bayar = parseInt($(this).val()) || 0;
            let total = produkList.reduce((sum, item) => sum + (item.harga * item.jumlah), 0);
            let pajak = parseFloat($('#pajakSelect').find(':selected').data('value')) || 0;
            let totalWithPajak = total + (total * pajak / 100);

            let kembalian = bayar - totalWithPajak;
            $('#kembalian').val(kembalian);

            warningDiv.textContent = kembalian < 0 ? 'Jumlah bayar kurang dari total pembayaran.' : '';
        });
    </script>

@endsection
