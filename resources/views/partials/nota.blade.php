<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo.png') }}" />
    <style>
        @page {
            size: 80mm;
            margin: 0;
        }
        body {
            font-family: 'Arial Narrow', Arial, sans-serif;
            font-size: 8pt;
            line-height: 1.2;
            margin: 5mm;
            padding: 0;
            /* background-color: #f8f9fa; */
        }
        .container {
            width: 100%;
            /* max-width: 80mm; */
            /* padding: 0; */
            background-color: #ffffff;
        }
        .header {
            text-align: center;
            margin-bottom: 3mm;
        }
        .header h3 {
            font-size: 10pt;
            margin: 0;
            color: #343a40;
        }
        .header p {
            font-size: 7pt;
            margin: 0;
            color: #6c757d;
        }
        .info {
            margin-bottom: 2mm;
        }
        .info p {
            margin: 0.3mm 0;
            display: flex;
            justify-content: space-between;
            color: #495057;
            font-size: 7pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2mm;
            font-size: 7pt;
        }
        th, td {
            padding: 0.5mm;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .total {
            text-align: left;
        }
        .total p {
            margin: 0.3mm 0;
            display: flex;
            justify-content: space-between;
            color: #495057;
            font-size: 7pt;
        }
        .item-details {
            font-size: 7pt;
            color: #6c757d;
        }
        .store-logo {
            max-width: 12mm;
            margin-bottom: 1mm;
        }
        .divider {
            border-top: 0.1mm solid #dee2e6;
            margin: 1mm 0;
        }
        .footer {
            text-align: center;
            font-size: 7pt;
            margin-top: 3mm;
            color: #6c757d;
        }
        .total-items {
            text-align: center;
            font-weight: bold;
            margin: 1mm 0;
            color: #343a40;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <div class="header">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo Toko" class="store-logo">
            <h3>Toko Kopma UTY</h3>
            <p>Jl. Glagahsari No.63, Warungboto, Kec. Umbulharjo, Kota Yogyakarta, Daerah Istimewa Yogyakarta 55164</p>
            <p>Telp: 081327243827</p>
        </div>
        
        <div class="divider"></div>
        
        <div class="info">
            <p><span><strong>No:</strong> INV-{{ date('Ymd', strtotime($transaksi->tanggal)) }}-{{ str_pad($transaksi->id, 3, '0', STR_PAD_LEFT) }}</span></p>
            <p><span><strong>Tgl:</strong> {{ date('d/m/Y H:i', strtotime($transaksi->tanggal)) }}</span></p>
            <div class="divider"></div>
            <p><span><strong>Cust:</strong> {{ $transaksi->pelanggan->nama }}</span> <span><strong>Kasir:</strong> {{ $transaksi->user->name }}</span> </p>
        </div>
        
        <div class="divider"></div>
        
        <table>
            <tbody>
                @foreach ($transaksi->detailTransaksis as $item)
                <tr>
                    <td>
                        <strong>{{ $item->produk->nama }}</strong>
                        <br>
                        <span class="item-details">
                            {{ $item->jumlah }}  x {{ number_format($item->harga, 0, ',', '.') }}
                        </span>
                    </td>
                    <td style="text-align: right;">{{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        
        <div class="total-items">
            Total Item: {{ $transaksi->detailTransaksis->sum('jumlah') }}
        </div>
        
        <div class="divider"></div>
        
        <div class="total">
            @if(($transaksi->detailTransaksis->sum('total') - $transaksi->total) > 0)
            <p><span>Pajak</span> <span>{{ number_format($transaksi->detailTransaksis->sum('total') - $transaksi->total, 0, ',', '.') }}</span></p>
            @endif
            <p><span><strong>Total</strong></span> <span> <strong>{{ number_format($transaksi->total, 0, ',', '.') }}</strong></span></p>
            <p><span>Bayar ({{ ucfirst($transaksi->pembayaran->nama) }})</span> <span>{{ number_format($transaksi->bayar, 0, ',', '.') }}</span></p>
            @if($transaksi->kembalian > 0)
            <p><span>Kembalian</span> <span>{{ number_format($transaksi->kembalian, 0, ',', '.') }}</span></p>
            @endif
        </div>
        
        <div class="divider"></div>
        
        <div class="footer">
            <p>Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan</p>
        </div>
    </div>
</body>
</html>
