@extends('template.main')
@section('title', $title ?? 'Web Kasir')

@section('content')
    <header class="bg-white shadow">
        <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8" style="height: 55px; display: flex; align-items: center;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Kategori</li>
                </ol>
            </nav>
        </div>
    </header>
    <div class="mx-auto max-w-full px-3 py-3 sm:px-4 lg:px-6">
        <div class="row g-2">
            <div class="col-12 mb-2 order-0">
                <div class="card">
                    <div class="d-flex justify-content-between align-items-center p-3">
                        <h5 class="mb-0">Daftar Kategori</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah
                            Kategori</button>
                    </div>
                    <div class="container">
                        <div class="card-datatable table-responsive">
                            <table class="datatables table border-top table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Keterangan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kategoris as $kategori)
                                        <tr>
                                            <td>{{ $kategori->nama }}</td>
                                            <td>{{ $kategori->ket }}</td>
                                            <td class="text-center">
                                                <button class="btn btn-warning btn-sm btn-edit"
                                                    data-id="{{ $kategori->id }}" data-nama="{{ $kategori->nama }}"
                                                    data-ket="{{ $kategori->ket }}" data-bs-toggle="modal"
                                                    data-bs-target="#modalEdit">Edit</button>

                                                <button class="btn btn-danger btn-sm btn-delete"
                                                    data-id="{{ $kategori->id }}" data-nama="{{ $kategori->nama }}"
                                                    data-bs-toggle="modal" data-bs-target="#modalDelete">Hapus</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Modal Tambah -->
                <div class="modal fade" id="modalTambah" tabindex="-1">
                    <div class="modal-dialog">
                        <form class="modal-content" method="POST" action="{{ route('kategori.store') }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Kategori</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-2">
                                    <label>Nama</label>
                                    <input type="text" name="nama" class="form-control" required
                                        value="{{ old('nama') }}">
                                </div>
                                <div class="mb-2">
                                    <label>Keterangan</label>
                                    <textarea name="ket" class="form-control">{{ old('ket') }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEdit" tabindex="-1">
                    <div class="modal-dialog">
                        <form id="formEdit" class="modal-content" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Kategori</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-2">
                                    <label>Nama</label>
                                    <input type="text" name="nama" class="form-control" id="edit-nama" required>
                                </div>
                                <div class="mb-2">
                                    <label>Keterangan</label>
                                    <textarea name="ket" class="form-control" id="edit-ket"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal Delete -->
                <div class="modal fade" id="modalDelete" tabindex="-1">
                    <div class="modal-dialog">
                        <form id="formDelete" class="modal-content" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-header">
                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Yakin ingin menghapus kategori <strong id="delete-nama"></strong>?</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button class="btn btn-danger">Hapus</button>
                            </div>
                        </form>
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

    <!-- JS to handle dynamic data passing -->
    <script>
        $('.btn-edit').click(function() {
            let id = $(this).data('id');
            let nama = $(this).data('nama');
            let ket = $(this).data('ket');
            $('#edit-nama').val(nama);
            $('#edit-ket').val(ket);
            $('#formEdit').attr('action', `/kategori/${id}`);
        });

        $('.btn-delete').click(function() {
            let id = $(this).data('id');
            let nama = $(this).data('nama');
            $('#delete-nama').text(nama);
            $('#formDelete').attr('action', `/kategori/${id}`);
        });
    </script>
@endsection
