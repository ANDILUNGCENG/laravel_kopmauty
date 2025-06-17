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
                    <li class="breadcrumb-item active" aria-current="page">Barcode</li>
                </ol>
            </nav>
        </div>
    </header>
    <div class="mx-auto max-w-full px-3 py-3 sm:px-4 lg:px-6">
        <div class="flex-grow-1">
            <div class="card mb-3">
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="productSearch" class="form-label">Cari Produk</label>
                            <select name="produk" class="form-control select2" id="productSearch" required>
                                <option value="">Pilih Produk</option>
                                @foreach ($produks as $produk)
                                    <option value="{{ $produk->id }}" data-nama="{{ $produk->nama }}"
                                        data-barcode="{{ $produk->barcode }}">
                                        {{ $produk->nama }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                    </form>
                    <div id="selectedProducts"></div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Pengaturan Label Barcode</h5>
                </div>
                <div class="card-body">
                    <form id="barcodeSettingsForm">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="labelWidth" class="form-label">Lebar Label (mm)</label>
                                <input type="number" class="form-control" id="labelWidth" name="labelWidth" value="33">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="labelHeight" class="form-label">Tinggi Label (mm)</label>
                                <input type="number" class="form-control" id="labelHeight" name="labelHeight"
                                    value="15">
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="barcodeWidth" class="form-label">Lebar Barcode (mm)</label>
                                <input type="number" class="form-control" id="barcodeWidth" name="barcodeWidth"
                                    value="30">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="barcodeHeight" class="form-label">Tinggi Barcode (mm)</label>
                                <input type="number" class="form-control" id="barcodeHeight" name="barcodeHeight"
                                    value="10">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="fontSize" class="form-label">Ukuran Font Nama Produk (px)</label>
                                <input type="number" class="form-control" id="fontSize" name="fontSize" value="8">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="leftMargin" class="form-label">Margin Kiri (mm)</label>
                                <input type="number" class="form-control" id="leftMargin" name="leftMargin" value="2">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="topMargin" class="form-label">Margin Atas (mm)</label>
                                <input type="number" class="form-control" id="topMargin" name="topMargin" value="2">
                            </div>


                            <div class="col-md-4 mb-3">
                                <label for="barcodeGap" class="form-label">Jarak Garis Kode Batang (mm)</label>
                                <input type="number" class="form-control" id="barcodeGap" name="barcodeGap" value="3"
                                    min="0">
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="displayName" name="displayName"
                                        checked>
                                    <label class="form-check-label" for="displayName">
                                        Tampilkan Nama Produk
                                    </label>
                                </div>

                            </div>
                        </div>

                    </form>
                </div>
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
        $('#productSearch').on('select2:select', function(e) {
            // Ambil elemen option yang dipilih
            var el = e.params.data.element;

            // Ambil data dari atribut
            var nama = $(el).data('nama');
            var barcode = $(el).data('barcode');
            var id = $(el).val();

            // Buat tabel jika belum ada
            if ($('#selectedProducts table').length === 0) {
                var tableHtml = `
            <table class="table table-bordered product-item mb-3">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Barcode</th>
                        <th>Jumlah Barcode</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                </tbody>
            </table>
            <button type="button" class="btn btn-primary mt-3 custom-btn-color" id="printButton">
                <i class="bx bx-printer"></i> Cetak Barcode
            </button>
        `;
                $('#selectedProducts').append(tableHtml);
            }

            // Tambah baris ke tabel
            var productHtml = `
        <tr>
            <td>
                <input type="text" class="form-control" name="products[${id}][name]" value="${nama}" readonly>
            </td>
            <td>
                <input type="text" class="form-control" name="products[${id}][barcode]" value="${barcode}" readonly>
            </td>
            <td>
                <input type="number" class="form-control" name="products[${id}][jumlah_barcode]" placeholder="Jumlah Barcode" required>
            </td>
            <td>
                <button type="button" class="btn btn-danger remove-product">X</button>
            </td>
        </tr>
    `;
            $('#productTableBody').append(productHtml);
        });
        $(document).on('click', '.remove-product', function() {
            $(this).closest('tr').remove();
            if ($('#productTableBody tr').length === 0) {
                $('#selectedProducts table').remove();
                $('#printButton').remove();
            }
        });
        $(document).on('click', '#printButton', function() {
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print Barcode</title>');
            printWindow.document.write('<style>');
            printWindow.document.write(`
        @page { size: roll; margin: 0; }
        body { margin: 0; }
        .barcode-container { display: flex; flex-wrap: wrap; }
        .barcode-item {
            width: ${$('#labelWidth').val()}mm;
            height: ${$('#labelHeight').val()}mm;
            padding: 5px;
            box-sizing: border-box;
            margin-left: ${$('#leftMargin').val()}mm;
            margin-top: ${$('#topMargin').val()}mm;
        }
        .barcode-image {
            width: ${$('#barcodeWidth').val()}mm;
            height: ${$('#barcodeHeight').val()}mm;
        }
        .product-name {
            font-size: ${$('#fontSize').val()}px;
            text-align: center;
        }
    `);
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<div class="barcode-container" id="barcodeContainer">');

            var barcodePromises = [];

            $('#productTableBody tr').each(function() {
                var barcode = $(this).find('input[name$="[barcode]"]').val();
                var productName = $(this).find('input[name$="[name]"]').val();
                var quantity = parseInt($(this).find('input[name$="[jumlah_barcode]"]').val()) || 0;

                for (var i = 0; i < quantity; i++) {
                    barcodePromises.push(generateBarcode(barcode, productName));
                }
            });

            Promise.all(barcodePromises).then(function(barcodeHtmlArray) {
                printWindow.document.write(barcodeHtmlArray.join(''));
                printWindow.document.write('</div></body></html>');
                printWindow.document.close();

                // Jalankan setelah dokumen selesai diparse
                printWindow.onload = function() {
                    const images = printWindow.document.images;
                    let loaded = 0;

                    if (images.length === 0) {
                        printWindow.focus();
                        printWindow.print();
                        return;
                    }

                    for (let img of images) {
                        img.onload = img.onerror = function() {
                            loaded++;
                            if (loaded === images.length) {
                                setTimeout(function() {
                                    printWindow.focus();
                                    printWindow.print();
                                }, 300); // buffer render
                            }
                        };
                    }

                    // Fallback jika onload tidak terpanggil (maks 2 detik)
                    setTimeout(function() {
                        printWindow.focus();
                        printWindow.print();
                    }, 2000);
                };
            });
        });

        function generateBarcode(barcode, productName) {
            return new Promise(function(resolve) {
                var canvas = document.createElement('canvas');

                JsBarcode(canvas, barcode, {
                    format: 'EAN13',
                    displayValue: true,
                    width: 1 + parseFloat($('#barcodeGap').val()),
                    fontSize: 30,
                    flat: true
                });

                var nameHtml = $('#displayName').is(':checked') ? `<div class="product-name">${productName}</div>` :
                    '';

                var barcodeHtml = `
            <div class="barcode-item">
                <img class="barcode-image" src="${canvas.toDataURL('image/png')}" />
                ${nameHtml}
            </div>
        `;
                resolve(barcodeHtml);
            });
        }
    </script>
@endsection
