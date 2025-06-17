<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Penjualan</title>
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
    <h2 style="text-align: center;">Laporan Transaksi Penjualan</h2>
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
                    <th>#</th>
                    <th>Pelanggan</th>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Subtotal</th>
                    <th>Pajak</th>
                    <th>Bayar</th>
                    <th>Kembalian</th>
                    <th>Tanggal</th>
                </tr>
            </thead>

            <tbody>
                @php $totalPenjualan = 0; @endphp
                @foreach ($transaksis as $i => $trx)
                    @php
                        $rowspan = $trx->detailTransaksis->count();
                        $first = true;
                        $subtotal = $trx->pajak ? $trx->total / (1 + $trx->pajak->nilai / 100) : $trx->total;
                        $pajakAmount = $trx->total - $subtotal;
                        $totalPenjualan += $trx->total;
                    @endphp

                    @foreach ($trx->detailTransaksis as $detail)
                        <tr>
                            @if ($first)
                                <td rowspan="{{ $rowspan }}">{{ $i + 1 }}</td>
                                <td rowspan="{{ $rowspan }}">{{ $trx->pelanggan->nama ?? '-' }}</td>
                            @endif

                            <td>{{ $detail->produk->nama ?? '-' }}</td>
                            <td>Rp{{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td>{{ $detail->jumlah }}</td>
                            <td>Rp{{ number_format($detail->total, 0, ',', '.') }}</td>

                            @if ($first)
                                <td rowspan="{{ $rowspan }}">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                                <td rowspan="{{ $rowspan }}">
                                    {{ $trx->pajak ? $trx->pajak->nama : '-' }}<br>
                                    (Rp{{ number_format($pajakAmount, 0, ',', '.') }})
                                </td>
                                <td rowspan="{{ $rowspan }}">Rp{{ number_format($trx->bayar, 0, ',', '.') }}</td>
                                <td rowspan="{{ $rowspan }}">Rp{{ number_format($trx->kembalian, 0, ',', '.') }}
                                </td>
                                <td rowspan="{{ $rowspan }}">
                                    {{ \Carbon\Carbon::parse($trx->tanggal)->format('d/m/Y') }}</td>
                                @php $first = false; @endphp
                            @endif
                        </tr>
                    @endforeach
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <th colspan="5">Total Penjualan</th>
                    <th colspan="6" class="text-right">Rp{{ number_format($totalPenjualan, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
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
