<!-- <div class="card mx-auto h-100% overflow-hidden" style="height: calc(100vh - 130px);"> -->
<div class="card mx-auto overflow-hidden" id="cartContainer">
    <div class="card-body d-flex flex-column h-100">
        <!-- Header -->
        <div class="header mb-3">
            <div class="row align-items-center">

                <div class="col-9">
                    <div class="input-group input-group-sm"> <!-- Tambahkan kelas small di sini -->
                        <span class="input-group-text">
                            <i class="bx bx-user custom-menu-icon"></i>
                        </span>
                        <select class="form-select select2" id="pelanggan_id" style="border: 10px solid #ced4da;">
                            @foreach ($pelanggans as $pelanggan)
                                <option value="{{ $pelanggan->id }}"
                                    {{ request('id_pelanggan', 1) == $pelanggan->id ? 'selected' : '' }}>
                                    {{ $pelanggan->nama }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Pelanggan wajib dipilih.
                        </div>
                    </div>
                </div>
                <div class="col-3 text-end">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="bx bx-plus me-1"></i> Pelanggan</button>
                </div>

            </div>
        </div>

        <!-- Garis Pembatas -->
        <hr class="custom-divider">

        <!-- Table Produk -->
        <div class="table-container">

            @include('kasir.cart-items')
            {{--  --}}
        </div>
    </div>

    <!-- Footer -->
    <div class="card-footer mt-auto">
        <div class="row">
            <div class="col-6">
                <h6>Total Item: <span id="totalItems">{{ $totalItems ?? 0 }}</span></h6>
            </div>
            <div class="col-6 text-end">
                <h6>Total: Rp <span id="cartTotal">{{ number_format($totalCart ?? 0, 0, ',', '.') }}</span></h6>
            </div>
        </div>
        {{--  --}}
        <div class="row mt-3">
            <div class="col-12">
                <div class="form-group">
                    <label for="tax">Pajak (%)</label>

                    <div class="input-group">
                        <span class="input-group-text" style="background-color: #f8f9fa;">
                            <i class='bx bxs-offer' style="color: #28a745;"></i>
                        </span>
                        <select class="form-select select2" id="pajak">
                            <option value="" selected>Pilih Pajak</option>
                            @foreach ($pajaks as $p)
                                <option value="{{ $p->id }}" data-value="{{ $p->nilai }}"
                                    {{ request('id_pajak') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <h5 style="display: none;">Total Pembayaran: Rp <span
                        id="totalPayment">{{ number_format($totalPayment ?? 0, 0, ',', '.') }}</span></h5>
            </div>
        </div>
        <div class="row mt-3">
            <div class="d-flex justify-content-between">
                <div class="btn-group w-100">
                    <button id="deleteAllButton" class="btn btn-danger"
                        style="flex: 0 0 10%; font-size: 1rem; padding: 0.75rem 1.5rem;">
                        <i class="bx bx-trash"></i>
                    </button>
                    <button id="payButton" class="btn btn-primary flex-fill"
                        style="font-size: 1rem; padding: 0.75rem 1.5rem;" data-bs-toggle="modal"
                        data-bs-target="#paymentModal">
                        Bayar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Pembayaran -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm" action="{{ route('transaksi.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="paymentForm_total" class="form-label">Total Pembayaran</label>
                        <input type="hidden" name="pajak_id" class="form-control" id="transaksi_pajak_id"
                            readonly>
                        <input type="hidden" name="pelanggan_id" class="form-control" id="transaksi_pelanggan_id"
                            readonly>
                        <input type="text" class="form-control" id="paymentForm_total" name="total"
                            value="{{ $totalPayment ?? 0 }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="paymentForm_paymentMethod" class="form-label">Metode Pembayaran</label>
                        <select class="form-select" id="paymentForm_paymentMethod" name="pembayaran_id" required>
                            <option value="">Pilih Metode</option>
                            @foreach ($pembayarans as $bayar)
                                <option value="{{ $bayar->id }}"
                                    {{ request('bayar', 1) == $bayar->id ? 'selected' : '' }}>
                                    {{ $bayar->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="paymentForm_bayar" class="form-label">Jumlah Dibayar</label>
                        <input type="number" class="form-control" id="paymentForm_bayar" name="bayar" required>
                    </div>

                    <div class="mb-3">
                        <label for="paymentForm_kembalian" class="form-label">Kembalian</label>
                        <input type="text" class="form-control" id="paymentForm_kembalian" name="kembalian"
                            readonly>
                        <div id="paymentForm_warning" class="text-danger small mt-1"></div>
                    </div>

                    <div class="mb-3 d-none" id="paymentForm_buktiContainer">
                        <label for="paymentForm_bukti" class="form-label">Bukti Bayar <small
                                class="text-muted">(optional)</small></label>
                        <input type="file" class="form-control" id="paymentForm_bukti" name="bukti"
                            accept="image/*">
                        <div class="text-center mt-2 mb-2">
                            <div id="paymentForm_preview" class="text-center d-flex justify-content-center"></div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary" id="paymentForm_submit"
                                disabled>Konfirmasi Pembayaran</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>


<!-- Modal Search Product -->
<input type="hidden" id="store" value="1">
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="searchModalLabel">Cari Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <select class="form-control select2" id="productSearch" name="product"
                        placeholder="Cari produk..."></select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ route('pelanggan.store', ['pelanggan' => true]) }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nama</label>
                    <input type="hidden" name="id_pajak" class="form-control" id="pajak_pelanggan" readonly>
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


<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const beepSound = document.getElementById('beepSound');

        document.getElementById('deleteAllButton').addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin menghapus semua item dari keranjang?')) {
                fetch('{{ route('keranjang.destroyAll') }}', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.dispatchEvent(new Event('productAdded'));
                        } else {
                            alert('Gagal menghapus semua item dari keranjang');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus semua item dari keranjang');
                    });
            }
        });

    });
    document.addEventListener('DOMContentLoaded', function() {
        const totalInput = document.getElementById('paymentForm_total');
        const bayarInput = document.getElementById('paymentForm_bayar');
        const kembalianInput = document.getElementById('paymentForm_kembalian');
        const metodeSelect = document.getElementById('paymentForm_paymentMethod');
        const buktiContainer = document.getElementById('paymentForm_buktiContainer');
        const buktiInput = document.getElementById('paymentForm_bukti');
        const previewDiv = document.getElementById('paymentForm_preview');
        const warningDiv = document.getElementById('paymentForm_warning');
        const submitBtn = document.getElementById('paymentForm_submit');

        function updateKembalian() {
            const total = parseInt(totalInput.value) || 0;
            const bayar = parseInt(bayarInput.value) || 0;
            const metode = metodeSelect.value;

            // Jika metode 2 (QRIS), tampilkan bukti bayar
            if (metode === '2') {
                buktiContainer.classList.remove('d-none');

                // Set hanya sekali jika belum diisi user
                if (!bayarInput.dataset.autoset) {
                    bayarInput.value = total;
                    bayarInput.dataset.autoset = 'true';
                }

                const kembalian = bayar - total;
                kembalianInput.value = kembalian > 0 ? kembalian : 0;

                if (bayarInput.value < total) {
                    warningDiv.textContent = 'Jumlah bayar kurang dari total pembayaran.';
                    submitBtn.disabled = true;
                } else {
                    warningDiv.textContent = '';
                    submitBtn.disabled = false;
                }
            } else {
                // Untuk metode lain
                buktiContainer.classList.add('d-none');
                bayarInput.removeAttribute('data-autoset');

                const kembalian = bayar - total;
                kembalianInput.value = kembalian > 0 ? kembalian : 0;

                if (bayarInput.value < total) {
                    warningDiv.textContent = 'Jumlah bayar kurang dari total pembayaran.';
                    submitBtn.disabled = true;
                } else {
                    warningDiv.textContent = '';
                    submitBtn.disabled = false;
                }
            }
        }
        metodeSelect.addEventListener('change', updateKembalian);
        bayarInput.addEventListener('input', updateKembalian);
        bayarInput.addEventListener('change', updateKembalian);


        buktiInput.addEventListener('change', function() {
            previewDiv.innerHTML = '';
            const file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail';
                    img.style.maxWidth = '150px';
                    previewDiv.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });
    });
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const pelangganSelect = document.getElementById('pelanggan_id');
        const pelangganId = pelangganSelect.value;

        if (!pelangganId) {
            e.preventDefault(); // Hentikan submit

            // Tutup modal Bootstrap 5
            const modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
            if (modal) modal.hide();

            // Tambahkan class is-invalid untuk validasi visual
            pelangganSelect.classList.add('is-invalid');

            // Tambahkan focus ke select2
            setTimeout(() => {
                $('#pelanggan_id').select2('open'); // auto open select2
            }, 300);

            setTimeout(() => {
                alert('Pelanggan tidak boleh kosong!');
            }, 400);


            return;
        } else {
            pelangganSelect.classList.remove('is-invalid');
        }
    });
</script>
