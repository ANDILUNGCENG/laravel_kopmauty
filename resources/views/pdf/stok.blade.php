<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Produk</title>
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

        th {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            box-sizing: border-box;
        }

        td.text-center {
            text-align: center;
        }

        td {
            border: 1px solid #000;
            padding: 6px;
            box-sizing: border-box;
        }

        .text-right {
            text-align: right;
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
    <h2 style="text-align: center;">Laporan Stok Produk</h2>
    <p><strong>Periode:</strong>
        @if ($startDate && $endDate)
            {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}
            - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
        @else
            Print Semua Data
        @endif
    </p>


    <div class="clearfix">
        <table>
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Stok</th>
                    <th>Pembelian</th>
                    <th>Penjualan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporanStok as $laporan)
                    <tr>
                        <td>{{ $laporan['nama_produk'] }}</td>
                        <td class="text-right">{{ $laporan['stok'] }}</td>
                        <td class="text-right">{{ $laporan['pembelian'] }}</td>
                        <td class="text-right">{{ $laporan['penjualan'] }}</td>
                    </tr>
                @endforeach
            </tbody>
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
