@extends('template.main')
@section('title', $title ?? 'Web Kasir')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/sales-transaction.css') }}" />
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
    <audio id="beepSound" src="{{ asset('audio/beep.wav') }}" preload="auto"></audio>
@endsection
@section('content')
    <div class="mx-auto max-w-full px-3 py-3 sm:px-4 lg:px-6">
        <div class="row">
            <div class="col-md-6">
                @include('kasir.cart')
            </div>
            <div class="col-md-6 product">
                @include('kasir.product')
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
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Pilih Opsi",
                allowClear: true,
                width: '100%',
            });
            $('#pajak').on('select2:select', function(e) {
                const pajakId = e.params.data.id; // Mengambil id pajak yang dipilih
                const pajakPelangganInput = document.getElementById('pajak_pelanggan');

                // Set the hidden input value to the selected pajak ID
                pajakPelangganInput.value = pajakId;

                // Update URL parameters
                const urlParams = new URLSearchParams(window.location.search);

                if (pajakId && pajakId !== '0') {
                    // If pajakId is valid, make sure it's set in the URL
                    urlParams.set('id_pajak', pajakId);
                }

                // Update the URL with the new parameters
                window.history.replaceState({}, '', `${window.location.pathname}?${urlParams.toString()}`);

                // Trigger the custom event
                const event = new Event('productAdded');
                document.dispatchEvent(event);
            });
            $('#pajak').on('select2:unselect', function(e) {
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.delete('id_pajak');
                window.history.replaceState({}, '', `${window.location.pathname}?${urlParams.toString()}`);

                const event = new Event('productAdded');
                document.dispatchEvent(event);
            });
            $('#pelanggan_id').on('change', function() {
                const pelangganSelect = document.getElementById('pelanggan_id');
                const pelangganId = this.value;

                // Set ke hidden input
                document.getElementById('transaksi_pelanggan_id').value = pelangganId;

                // Update URL param
                const urlParams = new URLSearchParams(window.location.search);

                if (pelangganId) {
                    urlParams.set('id_pelanggan', pelangganId); // Tambah/ganti param
                }
                pelangganSelect.classList.remove('is-invalid');

                // Update URL di browser tanpa reload
                window.history.replaceState({}, '', `${window.location.pathname}?${urlParams.toString()}`);

                // Trigger event
                const event = new Event('productAdded');
                document.dispatchEvent(event);
            });

        });
        document.addEventListener('DOMContentLoaded', function() {
            const event = new Event('productAdded');
            document.dispatchEvent(event);
        });

        document.addEventListener('productAdded', function() {
            const pajakId = document.getElementById('pajak').value;
            const pelangganId = document.getElementById('pelanggan_id').value;

            fetch(`/keranjang/refresh?pajak_id=${pajakId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('keranjang-container').innerHTML = data.html;
                    document.getElementById('totalItems').textContent = data.totalItems;
                    document.getElementById('cartTotal').textContent = data.totalCart;
                    document.getElementById('totalPayment').textContent = data.totalPayment;
                    document.getElementById('paymentForm_total').value = parseInt(data.totalPayment.replace(
                        /\./g, '')) || 0;
                    document.getElementById('transaksi_pelanggan_id').value = pelangganId;
                    document.getElementById('transaksi_pajak_id').value = pajakId;

                    if (data.nilaiPajak > 0) {
                        document.getElementById('totalPayment').parentElement.style.display =
                            'block'; // Show the element
                    } else {
                        document.getElementById('totalPayment').parentElement.style.display =
                            'none'; // Hide the element if no tax
                    }
                })
                .catch(error => {
                    console.error('Gagal memperbarui keranjang:', error);
                });
        });
    </script>
    @if (session('nota_id'))
        <script>
            window.open("{{ route('transaksi.nota', session('nota_id')) }}", "_blank");
        </script>
    @endif

@endsection
