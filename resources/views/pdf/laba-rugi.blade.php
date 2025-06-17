<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Laba Rugi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            position: relative;
            height: 100px;
            margin-bottom: 20px;
        }

        .logo {
            position: absolute;
            left: 0;
            top: 0;
            height: 80px;
        }

        .info {
            text-align: center;
            line-height: 1.4;
        }

        .line {
            border-top: 2px solid black;
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            box-sizing: border-box;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            box-sizing: border-box;
        }

        .text-right {
            text-align: right;
        }

        .text-end {
            text-align: right;
        }

        .text-start {
            text-align: left;
        }

        .signature {
            margin-top: 50px;
            width: 100%;
        }

        .signature .right {
            float: right;
            text-align: center;
        }

        /* Untuk menghindari float effect pada .signature */
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        .bg-secondary {
            background-color: #959ba1;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('assets/img/logo.png') }}" alt="Logo Toko" class="logo">
        <div class="info">
            <h1>Toko Kopma UTY</h1>
            <p>Jl. Glagahsari No.63, Warungboto, Umbulharjo, Kota Yogyakarta 55164</p>
            <p>Telp: 081327243827</p>
        </div>
    </div>

    <div class="line"></div>
    <h2 style="text-align: center;">Laporan Transaksi Laba Rugi</h2>
    <p><strong>Periode:</strong>
        @if ($startDate && $endDate)
            {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}
            - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
        @else
            Print Semua Data
        @endif
    </p>


    <div class="clearfix">
        <table class="table table-bordered">
            <thead class="table-secondary">
                <tr>
                    <th class="bg-secondary">Keterangan</th>
                    <th class="bg-secondary">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2"><strong>Pendapatan</strong></td>
                </tr>
                <tr>
                    <td class="text-start">Penjualan (Pajak)</td>
                    <td class="text-end">Rp {{ number_format($totalPenjualanPajak, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-start">Penjualan (Non-Pajak)</td>
                    <td class="text-end">Rp {{ number_format($totalPenjualanNonPajak, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-start">Total Penjualan (Pajak + Non-Pajak)</td>
                    <td class="fw-semibold text-end">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-start">Total Pajak</td>
                    <td class="text-end">Rp {{ number_format($totalPajakPenjualan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-start">Total Penjualan (Bersih)</td>
                    <td class="fw-semibold text-end">Rp {{ number_format($totalPenjualanBersih, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Pembelian</strong></td>
                </tr>
                <tr>
                    <td class="text-start">Pembelian (Pajak)</td>
                    <td class="text-end">Rp {{ number_format($totalPembelianPajak, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-start">Pembelian (Non-Pajak)</td>
                    <td class="text-end">Rp {{ number_format($totalPembelianNonPajak, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-start">Total Pembelian (Pajak + Non-Pajak)</td>
                    <td class="fw-semibold text-end">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-start">Total Pajak</td>
                    <td class="text-end">Rp {{ number_format($totalPajakPembelian, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-start">Total Pembelian (Bersih)</td>
                    <td class="fw-semibold text-end">Rp {{ number_format($totalPembelianBersih, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-center"><strong class="fs-5">Potensi Laba</strong> <small>(Jika semua Barang
                            Terjual)</small></td>
                    <td class="text-end"><strong class="fs-5">Rp
                            {{ number_format($potensiLaba, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td class="text-center"><strong class="fs-5">Laba Sekarang</strong> <small>(Berdasarkan Barang
                            Yang Terjual)</small></td>
                    <td class="text-end"><strong class="fs-5">Rp
                            {{ number_format($totalLabaPenjualan, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td class="text-center"><strong class="fs-5">Selisih Pajak</strong> <small>(Pajak Penjualan -
                            Pajak Pembelian)</small></td>
                    <td class="text-end"><strong class="fs-5">Rp
                            {{ number_format($totalSelisihPajak, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
            <thead class="table-secondary">
                <tr>
                    <th class="bg-secondary text-center fs-5"><strong>Laba</strong></th>
                    <th class="bg-secondary text-end fs-5"><strong>Rp {{ number_format($laba, 0, ',', '.') }}</strong>
                    </th>
                </tr>
            </thead>
        </table>

        <div class="signature">
            <div class="right">
                <p>Yogyakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <br><br><br>
                <p>( {{ auth()->user()->name }} )</p>
            </div>
        </div>
    </div>

</body>

</html>
