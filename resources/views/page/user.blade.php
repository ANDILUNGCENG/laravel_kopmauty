@extends('template.main')
@section('title', $title ?? 'Web Kasir')

@section('content')
    <header class="bg-white shadow">
        <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8" style="height: 55px; display: flex; align-items: center;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">User</li>
                </ol>
            </nav>
        </div>
    </header>
    <div class="mx-auto max-w-full px-3 py-3 sm:px-4 lg:px-6">
        <div class="row g-2">
            <div class="col-12 mb-2 order-0">
                <div class="card">
                    <div class="d-flex justify-content-between align-items-center p-3">
                        <h5 class="mb-0">Daftar User</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah
                            User</button>
                    </div>
                    <div class="container">
                        <div class="card-datatable table-responsive">
                            <table class="datatables table border-top table-striped">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Created Date</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            @php
                                                $role = $user->roles->pluck('name')->first();
                                                $roleFormatted = match ($role) {
                                                    'kepalatoko' => 'Kepala Bidang Usaha',
                                                    'admin' => 'Admin',
                                                    'kasir' => 'Kasir',
                                                    default => ucfirst($role),
                                                };
                                            @endphp

                                            <td>{{ $roleFormatted }}</td>

                                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                            <td class="text-center">
                                                <!-- Button Edit -->
                                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modalEdit" data-id="{{ $user->id }}"
                                                    data-name="{{ $user->name }}" data-email="{{ $user->email }}"
                                                    data-role="{{ $user->roles->pluck('name')->first() }}">Edit</button>
                                                <!-- Button Delete -->
                                                @if ($user->id != 1)
                                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#modalDelete" data-id="{{ $user->id }}"
                                                        data-name="{{ $user->name }}">Hapus</button>
                                                @endif
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
                        <form class="modal-content" method="POST" action="{{ route('users.store') }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-2">
                                    <label>Nama</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="mb-2">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="mb-2">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="mb-2">
                                    <label>Role</label>
                                    <select name="role" class="form-control" required>
                                        @foreach ($roles as $role)
                                            @php
                                                $displayName = match ($role->name) {
                                                    'kepalatoko' => 'Kepala Bidang Usaha',
                                                    'admin' => 'Admin',
                                                    'kasir' => 'Kasir',
                                                    default => ucfirst($role->name),
                                                };
                                            @endphp
                                            <option value="{{ $role->name }}">{{ $displayName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEdit" tabindex="-1">
                    <div class="modal-dialog">
                        <form class="modal-content" method="POST" action="{{ route('users.update', ':id') }}"
                            id="formEdit">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-2">
                                    <label>Nama</label>
                                    <input type="text" name="name" class="form-control" id="edit-name" required>
                                </div>
                                <div class="mb-2">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" id="edit-email" required>
                                </div>
                                <div class="mb-2">
                                    <label>Password (kosongkan jika tidak diubah)</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                <div class="mb-2">
                                    <label>Role</label>
                                    <select name="role" class="form-control" id="edit-role" required>
                                        @foreach ($roles as $role)
                                            @php
                                                $displayName = match ($role->name) {
                                                    'kepalatoko' => 'Kepala Bidang Usaha',
                                                    'admin' => 'Admin',
                                                    'kasir' => 'Kasir',
                                                    default => ucfirst($role->name),
                                                };
                                            @endphp
                                            <option value="{{ $role->name }}">{{ $displayName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal Delete -->
                <div class="modal fade" id="modalDelete" tabindex="-1">
                    <div class="modal-dialog">
                        <form class="modal-content" method="POST" action="{{ route('users.destroy', ':id') }}"
                            id="formDelete">
                            @csrf
                            @method('DELETE')
                            <div class="modal-header">
                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Yakin ingin menghapus user <strong id="delete-name"></strong>?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Hapus</button>
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
        const modalEdit = document.getElementById('modalEdit');
        modalEdit.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const email = button.getAttribute('data-email');
            const role = button.getAttribute('data-role');

            // Update form action
            const form = modalEdit.querySelector('form');
            form.action = form.action.replace(':id', id);

            // Fill in the form fields
            modalEdit.querySelector('#edit-name').value = name;
            modalEdit.querySelector('#edit-email').value = email;
            modalEdit.querySelector('#edit-role').value = role;
        });

        const modalDelete = document.getElementById('modalDelete');
        modalDelete.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');

            // Update form action
            const form = modalDelete.querySelector('form');
            form.action = form.action.replace(':id', id);

            // Update the name in the confirmation text
            modalDelete.querySelector('#delete-name').textContent = name;
        });
    </script>
@endsection
