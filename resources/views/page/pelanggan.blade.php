@extends('template.main')
@section('title', $title ?? 'Web Kasir')

@section('content')
    <header class="bg-white shadow">
        <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8" style="height: 55px; display: flex; align-items: center;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pelanggan</li>
                </ol>
            </nav>
        </div>
    </header>
    <div class="mx-auto max-w-full px-3 py-3 sm:px-4 lg:px-6">
        <div class="row g-2">
            <div class="col-12 mb-2 order-0">
                <div class="card">
                    <div class="d-flex justify-content-between align-items-center p-3">
                        <h5 class="mb-0">Daftar Pelanggan</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah
                            Pelanggan</button>
                    </div>
                    <div class="container">
                        <div class="card-datatable table-responsive">
                            <table class="datatables table border-top table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama</th>
                                        <th>No</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pelanggans as $p)
                                        <tr>
                                            <td>{{ $p->nama }}</td>
                                            <td>{{ $p->no }}</td>
                                            <td>{{ $p->ket }}</td>
                                            <td>
                                                    <button class="btn btn-sm btn-warning btn-edit"
                                                        data-id="{{ $p->id }}" data-nama="{{ $p->nama }}"
                                                        data-no="{{ $p->no }}" data-ket="{{ $p->ket }}"
                                                        data-bs-toggle="modal" data-bs-target="#modalEdit">Edit</button>

                                                    <button class="btn btn-sm btn-danger btn-delete"
                                                        data-id="{{ $p->id }}" data-nama="{{ $p->nama }}"
                                                        data-bs-toggle="modal" data-bs-target="#modalHapus">Hapus</button>
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
                        <form class="modal-content" method="POST" action="{{ route('pelanggan.store') }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Pelanggan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Nama</label>
                                    <input type="text" name="nama" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>No</label>
                                    <input type="number" name="no" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Keterangan</label>
                                    <textarea name="ket" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-success">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEdit" tabindex="-1">
                    <div class="modal-dialog">
                        <form class="modal-content" method="POST" id="formEdit">
                            @csrf @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Pelanggan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Nama</label>
                                    <input type="text" name="nama" id="editNama" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>No</label>
                                    <input type="number" name="no" id="editNilai" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Keterangan</label>
                                    <textarea name="ket" id="editKet" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal Hapus -->
                <div class="modal fade" id="modalHapus" tabindex="-1">
                    <div class="modal-dialog">
                        <form class="modal-content" method="POST" id="formHapus">
                            @csrf @method('DELETE')
                            <div class="modal-header">
                                <h5 class="modal-title">Hapus Pelanggan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Yakin ingin menghapus <strong id="hapusNama"></strong>?</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger">Hapus</button>
                            </div>
                        </form>
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
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('editNama').value = this.dataset.nama;
                document.getElementById('editNilai').value = this.dataset.no;
                document.getElementById('editKet').value = this.dataset.ket;
                document.getElementById('formEdit').action = '/pelanggan/' + this.dataset.id;
            });
        });

        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('hapusNama').textContent = this.dataset.nama;
                document.getElementById('formHapus').action = '/pelanggan/' + this.dataset.id;
            });
        });
    </script>
@endsection
