@extends('template.main')
@section('title', $title ?? 'Web Kasir')
@section('css')
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
                    <li class="breadcrumb-item active" aria-current="page">Produk</li>
                </ol>
            </nav>
        </div>
    </header>
    <div class="mx-auto max-w-full px-1 py-3  lg:px-6">
        <div class="row g-2">
            <div class="col-12 mb-2 order-0">
                <div class="card">
                    <div class="d-flex justify-content-between align-items-center p-3">
                        <h5 class="mb-0">Daftar Produk</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah
                            Produk</button>
                    </div>
                    <div class="p-0">
                        <div class="card-datatable table-responsive">
                            <table class="datatables table border-top table-striped small">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Harga Jual</th>
                                        <th>Harga Beli</th>
                                        <th>Stok</th>
                                        <th>Barcode</th>
                                        <th>Gambar</th>
                                        <th>Keterangan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($produks as $row)
                                        <tr>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->kategori->nama }}</td>
                                            <td>Rp{{ number_format($row->harga_jual, 0, ',', '.') }}</td>
                                            <td>Rp{{ number_format($row->harga_beli, 0, ',', '.') }}</td>
                                            <td>{{ $row->stok }}</td>
                                            <td>{{ $row->barcode }}</td>
                                            <td>
                                                @if ($row->gambar)
                                                    <img src="{{ asset('storage/' . $row->gambar) }}" alt="gambar"
                                                        width="50"
                                                        class="img-thumbnail show-image bg-light p-1 rounded link"
                                                        data-src="{{ asset('storage/' . $row->gambar) }}">
                                                @endif
                                            </td>
                                            <td>{{ $row->ket }}</td>
                                            <td class="text-center">
                                                <div class="row">
                                                    <div class="col-12 mb-2">
                                                        <button type="button" class="btn btn-warning btn-sm editBtn"
                                                            data-id="{{ $row->id }}" data-nama="{{ $row->nama }}"
                                                            data-kategori_id="{{ $row->kategori_id }}"
                                                            data-harga_jual="{{ $row->harga_jual }}"
                                                            data-harga_beli="{{ $row->harga_beli }}"
                                                            data-stok="{{ $row->stok }}"
                                                            data-stok_minim="{{ $row->stok_minim }}"
                                                            data-barcode="{{ $row->barcode }}"
                                                            data-ket="{{ $row->ket }}"
                                                            data-gambar="{{ $row->gambar }}" data-bs-toggle="modal"
                                                            data-bs-target="#modalEdit">
                                                            Edit
                                                        </button>
                                                    </div>
                                                    <div class="col-12 mb-2">
                                                        <button type="button" class="btn btn-danger btn-sm hapusBtn"
                                                            data-id="{{ $row->id }}" data-nama="{{ $row->nama }}"
                                                            data-bs-toggle="modal" data-bs-target="#modalDelete">
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
                {{-- Modal Tambah --}}
                <div class="modal fade" id="modalTambah" tabindex="-1">
                    <div class="modal-dialog">
                        <form class="modal-content" method="POST" action="{{ route('produk.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Produk</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-2">
                                    <label>Kategori</label>
                                    <select name="kategori_id" class="form-control select2" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2"><label>Nama</label><input type="text" name="nama"
                                        class="form-control" required></div>
                                <div class="mb-2"><label>Harga Jual</label><input type="number" name="harga_jual"
                                        class="form-control" required></div>
                                <div class="mb-2"><label>Harga Beli</label><input type="number" name="harga_beli"
                                        class="form-control" required></div>
                                {{-- <div class="mb-2"><label>Stok</label><input type="number" name="stok"
                                        class="form-control" required></div> --}}
                                <div class="mb-2"><label>Stok Minimum</label><input type="number" name="stok_minim"
                                        class="form-control" required></div>
                                <div class="mb-2"><label>Barcode</label><input type="text" name="barcode"
                                        class="form-control" required></div>
                                <div class="mb-2">
                                    <label>Gambar</label>
                                    <input type="file" name="gambar" class="form-control" accept="image/*"
                                        onchange="previewGambar(this, '#previewTambah')" required>
                                    <img id="previewTambah" class="img-thumbnail mt-2 mx-auto d-block"
                                        style="display:none; max-height:100px; cursor:pointer;"
                                        onclick="showImageModal(this.src)">
                                </div>

                                <div class="mb-2"><label>Keterangan</label>
                                    <textarea name="ket" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Modal Edit --}}
                <div class="modal fade" id="modalEdit" tabindex="-1">
                    <div class="modal-dialog">
                        <form class="modal-content" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Produk</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-2">
                                    <label>Kategori</label>
                                    <select name="kategori_id" class="form-control select2" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2"><label>Nama</label><input type="text" name="nama"
                                        class="form-control" required></div>
                                <div class="mb-2"><label>Harga Jual</label><input type="number" name="harga_jual"
                                        class="form-control" required></div>
                                <div class="mb-2"><label>Harga Beli</label><input type="number" name="harga_beli"
                                        class="form-control" required></div>
                                {{-- <div class="mb-2"><label>Stok</label><input type="number" name="stok"
                                        class="form-control" required></div> --}}
                                <div class="mb-2"><label>Stok Minimum</label><input type="number" name="stok_minim"
                                        class="form-control" required></div>
                                <div class="mb-2"><label>Barcode</label><input type="text" name="barcode"
                                        class="form-control" required></div>
                                <div class="mb-2">
                                    <label>Gambar</label>
                                    <input type="file" name="gambar" class="form-control" accept="image/*"
                                        onchange="previewGambar(this, '#previewEdit')">
                                    <img id="previewEdit" class="img-thumbnail mt-2 mx-auto d-block"
                                        style="max-height:200px; cursor:pointer;" onclick="showImageModal(this.src)">
                                </div>

                                <div class="mb-2"><label>Keterangan</label>
                                    <textarea name="ket" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Modal Hapus --}}
                <div class="modal fade" id="modalDelete" tabindex="-1">
                    <div class="modal-dialog">
                        <form class="modal-content" method="GET">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Yakin ingin menghapus produk <strong class="nama-hapus"></strong>?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Hapus</button>
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
                <div class="modal fade" id="imagePreviewModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <img id="modalImagePreview" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
                {{--  --}}
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
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />


    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


    <script>
        $(document).ready(function() {
            var table = $('.datatables').DataTable({
                responsive: true,
                autoWidth: false,
            });

            // Ambil parameter `search` dari URL
            var searchParam = new URLSearchParams(window.location.search).get('search');

            if (searchParam) {
                table.search(searchParam).draw();
            }
            $('.select2').select2({
                placeholder: "Pilih Opsi",
                allowClear: true,
                width: '100%',
            });
        });
    </script>

    <!-- JS to handle dynamic data passing -->
    <script>
        function previewGambar(input, previewId) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $(previewId).attr('src', e.target.result).show();
                };
                reader.readAsDataURL(file);
            }
        }

        function showImageModal(src) {
            $('#modalImagePreview').attr('src', src);
            $('#imagePreviewModal').modal('show');
        }

        // Saat klik edit button
        $('.editBtn').on('click', function() {
            const id = $(this).data('id');
            const form = $('#modalEdit form');
            form.attr('action', '/produk/update/' + id);
            form.find('[name=nama]').val($(this).data('nama'));
            form.find('[name=kategori_id]')
                .val($(this).data('kategori_id'))
                .trigger('change');
            form.find('[name=harga_jual]').val($(this).data('harga_jual'));
            form.find('[name=harga_beli]').val($(this).data('harga_beli'));
            form.find('[name=stok]').val($(this).data('stok'));
            form.find('[name=stok_minim]').val($(this).data('stok_minim'));
            form.find('[name=barcode]').val($(this).data('barcode'));
            form.find('[name=ket]').val($(this).data('ket'));

            // Set gambar lama di preview
            const gambarLama = $(this).data('gambar');
            if (gambarLama) {
                $('#previewEdit').attr('src', '/storage/' + gambarLama)
                    .show(); // atau sesuaikan path penyimpanan
            } else {
                $('#previewEdit').hide();
            }
        });

        // Hapus Modal
        $('.hapusBtn').on('click', function() {
            const id = $(this).data('id');
            $('#modalDelete form').attr('action', '/produk/delete/' + id);
            $('.nama-hapus').text($(this).data('nama'));
        });

        // Gambar Modal
        $('.show-image').on('click', function() {
            const src = $(this).data('src');
            $('#imgPreview').attr('src', src);
            $('#modalImage').modal('show');
        });
    </script>
@endsection
