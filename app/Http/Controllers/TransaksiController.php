<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Keranjang;
use App\Models\Pajak;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
// 1 = pembelian, 2 = penjualan, 3 = stok_opname
class TransaksiController extends Controller
{
    public function indexPenjualan(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $transaksis = Transaksi::with('detailTransaksis.produk')
            ->where('jenis', 2)
            ->when(auth()->user()->hasRole('kasir'), function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59'
                ]);
            })

            ->get();

        return view('page.penjualan', compact('transaksis'));
    }


    public function indexPembelian(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $transaksis = Transaksi::with('detailTransaksis.produk')
            ->where('jenis', 1)
            ->when(auth()->user()->hasRole('kasir'), function ($query) {
                $query->where('user_id', auth()->id());
            })

            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59'
                ]);
            })
            ->get();

        return view('page.pembelian', compact('transaksis'));
    }


    public function createPembelian()
    {
        $produks = Produk::with('kategori')->get();
        $pajaks = Pajak::all();
        $pembayarans = Pembayaran::all();
        return view('create.pembelian', compact('pajaks', 'produks', 'pembayarans'));
    }
    public function storePembelian(Request $request) //update-stok 
    {
        $validator = Validator::make($request->all(), [
            'produk_id' => 'required|array',
            'produk_id.*' => 'exists:produks,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1',
            'pembayaran_id' => 'required|exists:pembayarans,id',
            'bayar' => 'required|numeric|min:0',
            'kembalian' => 'required|numeric|min:0',
            'bukti' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('failed', implode('<br>', $validator->errors()->all()));
        }

        $userId = auth()->id();
        $bukti = null;

        if ($request->hasFile('bukti')) {
            $bukti = $request->file('bukti')->store('bukti', 'public');
        }

        $produkIds = $request->produk_id;
        $jumlahs = $request->jumlah;

        // Ambil data produk dari DB
        $produk = Produk::whereIn('id', $produkIds)->get();

        $grandTotal = 0;
        foreach ($produk as $key => $item) {
            $index = array_search($item->id, $produkIds); // cari indeks yg sesuai
            $jumlah = (int)$jumlahs[$index];
            $subTotal = $item->harga_beli * $jumlah;
            $grandTotal += $subTotal;
        }

        // Tambah pajak jika ada
        $pajakAmount = 0;
        if ($request->filled('pajak_id')) {
            $pajak = Pajak::find($request->pajak_id);
            if ($pajak) {
                $pajakAmount = ($pajak->nilai / 100) * $grandTotal;
                $grandTotal += $pajakAmount;
            }
        }

        // Simpan transaksi
        $transaksi = Transaksi::create([
            'user_id' => $userId,
            'pelanggan_id' => 1,
            'pembayaran_id' => $request->pembayaran_id,
            'pajak_id' => $request->pajak_id ?? null,
            'total' => $grandTotal,
            'bayar' => $request->bayar,
            'kembalian' => $request->bayar - $grandTotal,
            'jenis' => 1,
            'bukti' => $bukti,
            'tanggal' => now(),
        ]);

        // Simpan detail
        foreach ($produk as $item) {
            $index = array_search($item->id, $produkIds);
            $jumlah = (int)$jumlahs[$index];
            $subTotal = $item->harga_beli * $jumlah;

            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'user_id' => $userId,
                'produk_id' => $item->id,
                'harga' => $item->harga_beli,
                'jumlah' => $jumlah,
                'total' => $subTotal,
            ]);

            // Update stok produk (karena ini pembelian, stok ditambah)
            $item->stok += $jumlah;
            $item->save();
        }

        return redirect()->route('transaksi.create.pembelian')
            ->with('success', 'Transaksi berhasil disimpan');
    }

    public function editPembelian($id, Request $request)
    {
        $transaksi = Transaksi::findOrFail($id);
        $produks = Produk::with('kategori')->get();
        $pajaks = Pajak::all();
        $pembayarans = Pembayaran::all();
        return view('edit-pembelian.pembelian', compact('pajaks', 'produks', 'pembayarans', 'transaksi'));
    }

    public function updatePembelian(Request $request, $id) //update-stok 
    {
        $transaksi = Transaksi::findOrFail($id);

        $totalSemua = 0;
        //Kurangi stok berdasarkan data lama sebelum hapus detail
        foreach ($transaksi->detailTransaksis as $detailLama) {
            $produk = Produk::find($detailLama->produk_id);
            if ($produk) {
                $produk->stok -= $detailLama->jumlah;
                $produk->save();
            }
        }

        $transaksi->detailTransaksis()->delete();

        // Tambahkan detail transaksi baru dan hitung total
        foreach ($request->produk_id as $i => $pid) {
            $produk = Produk::find($pid);
            $jumlah = $request->jumlah[$i];
            $harga = $produk->harga_beli;
            $total = $harga * $jumlah;

            $transaksi->detailTransaksis()->create([
                'user_id' => $transaksi->user_id,
                'produk_id' => $pid,
                'jumlah' => $jumlah,
                'harga' => $harga,
                'total' => $total,
            ]);
            // Tambah stok sesuai pembelian baru
            if ($produk) {
                $produk->stok += $jumlah;
                $produk->save();
            }

            $totalSemua += $total;
        }

        // Tambah pajak jika ada
        $pajakAmount = 0;
        if ($request->filled('pajak_id')) {
            $pajak = Pajak::find($request->pajak_id);
            if ($pajak) {
                $pajakAmount = ($pajak->nilai / 100) * $totalSemua;
                $totalSemua += $pajakAmount;
            }
        }
        // Update transaksi
        $transaksi->update([
            'pajak_id' => $request->pajak_id,
            'pembayaran_id' => $request->pembayaran_id,
            'bayar' => $request->bayar,
            'total' => $totalSemua,
            'kembalian' => $request->kembalian,
        ]);

        // Simpan bukti jika ada, dan hapus file lama jika ada
        if ($request->hasFile('bukti')) {
            if ($transaksi->bukti && Storage::exists('public/bukti/' . $transaksi->bukti)) {
                Storage::delete('public/bukti/' . $transaksi->bukti);
            }

            $filename = time() . '.' . $request->bukti->extension();
            $request->bukti->storeAs('public/bukti', $filename);
            $transaksi->update(['bukti' => $filename]);
        }
        if ($request->pembayaran_id == 1) {
            if ($transaksi->bukti && Storage::exists('public/bukti/' . $transaksi->bukti)) {
                Storage::delete('public/bukti/' . $transaksi->bukti);
            }
            $transaksi->update(['bukti' => null]);
        }

        return redirect()->route('transaksi.pembelian')->with('success', 'Pembelian berhasil diperbarui!');
    }

    public function laporanPenjualan(Request $request)
    {
        // Ambil filter tanggal jika ada
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Query transaksi sesuai jenis dan filter tanggal
        $transaksis = Transaksi::with('detailTransaksis.produk')
            ->where('jenis', 2)
            ->when(auth()->user()->hasRole('kasir'), function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59'
                ]);
            })
            ->get();

        // Ambil data total penjualan per bulan sesuai filter
        $monthlySales = Transaksi::selectRaw("MONTH(tanggal) as bulan, SUM(total) as total")
            ->where('jenis', 2)
            ->when(auth()->user()->hasRole('kasir'), function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59'
                ]);
            })
            ->groupBy(DB::raw("MONTH(tanggal)"))
            ->orderBy(DB::raw("MONTH(tanggal)"))
            ->pluck('total', 'bulan')
            ->toArray();

        // Ubah nomor bulan ke nama bulan
        $namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $categories = [];
        $salesChart = [];

        foreach ($monthlySales as $bulan => $total) {
            $categories[] = $namaBulan[$bulan - 1]; // nama bulan
            $salesChart[] = (int)$total;            // jumlah penjualan
        }

        return view('page.laporan_penjualan', [
            'transaksis' => $transaksis,
            'getsaleschart' => $salesChart,
            'categories' => $categories,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
    public function laporanPembelian(Request $request)
    {
        // Ambil filter tanggal jika ada
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Query transaksi sesuai jenis dan filter tanggal
        $transaksis = Transaksi::with('detailTransaksis.produk')
            ->where('jenis', 1)
            ->when(auth()->user()->hasRole('kasir'), function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59'
                ]);
            })
            ->get();

        // Ambil data total penjualan per bulan sesuai filter
        $monthlySales = Transaksi::selectRaw("MONTH(tanggal) as bulan, SUM(total) as total")
            ->where('jenis', 1)
            ->when(auth()->user()->hasRole('kasir'), function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59'
                ]);
            })
            ->groupBy(DB::raw("MONTH(tanggal)"))
            ->orderBy(DB::raw("MONTH(tanggal)"))
            ->pluck('total', 'bulan')
            ->toArray();

        // Ubah nomor bulan ke nama bulan
        $namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $categories = [];
        $salesChart = [];

        foreach ($monthlySales as $bulan => $total) {
            $categories[] = $namaBulan[$bulan - 1]; // nama bulan
            $salesChart[] = (int)$total;            // jumlah penjualan
        }

        return view('page.laporan_pembelian', [
            'transaksis' => $transaksis,
            'getsaleschart' => $salesChart,
            'categories' => $categories,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
    public function laporanStok(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Ambil semua produk
        $produks = Produk::all();

        // Ambil semua transaksi dengan relasi detailTransaksis dan produk
        $transaksis = Transaksi::with('detailTransaksis', 'detailTransaksis.produk')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            })
            ->get();

        // Siapkan array laporan
        $laporanStok = [];

        // Loop setiap produk
        foreach ($produks as $produk) {
            // Hitung total pembelian
            $totalPembelian = $transaksis->where('jenis', 1)->flatMap(function ($transaksi) {
                return $transaksi->detailTransaksis;
            })->where('produk_id', $produk->id)->sum('jumlah');

            // Hitung total penjualan
            $totalPenjualan = $transaksis->where('jenis', 2)->flatMap(function ($transaksi) {
                return $transaksi->detailTransaksis;
            })->where('produk_id', $produk->id)->sum('jumlah');

            // Hitung total penyesuaian
            $totalPenyesuaian = $transaksis->where('jenis', 3)->flatMap(function ($transaksi) {
                return $transaksi->detailTransaksis;
            })->where('produk_id', $produk->id)->sum('jumlah');

            // Stok akhir
            $stokAkhir = $produk->stok + $totalPembelian - $totalPenjualan + $totalPenyesuaian;

            // Simpan ke laporan
            $laporanStok[] = [
                'nama_produk' => $produk->nama,
                'stok' => $produk->stok,
                'pembelian' => $totalPembelian,
                'penjualan' => $totalPenjualan,
                'penyesuaian' => $totalPenyesuaian,
                'stok_akhir' => $stokAkhir,
            ];
        }

        // Tampilkan hasil laporan
        return view('page.laporan_stok', compact('laporanStok'));
    }

    public function laporanLabaRugi(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Ambil semua transaksi dengan relasi produk di detail
        $transaksisPembelian = Transaksi::with('detailTransaksis.produk')
            ->get();

        $totalPembelianPajak = $transaksisPembelian->where('jenis', 1)->whereNotNull('pajak_id')->sum(function ($detail) {
            return $detail->total;
        });

        $totalPembelianNonPajak = $transaksisPembelian->where('jenis', 1)->whereNull('pajak_id')->sum(function ($detail) {
            return $detail->total;
        });
        $totalPajakPembelian = $transaksisPembelian
            ->where('jenis', 1)
            ->whereNotNull('pajak_id')
            ->sum(function ($transaksi) {
                $pajak = Pajak::find($transaksi->pajak_id);
                if ($pajak && $pajak->nilai > 0) {
                    return ($pajak->nilai / (100 + $pajak->nilai)) * $transaksi->total;
                }
                return 0;
            });

        $totalPembelian =  $totalPembelianPajak + $totalPembelianNonPajak;
        $totalPembelianBersih =  $totalPembelian - $totalPajakPembelian;


        $transaksis = Transaksi::with('detailTransaksis.produk')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59'
                ]);
            })
            ->get();

        // Total Penjualan (jenis = 2)
        $totalLabaPenjualanPajak = $transaksis->where('jenis', 2)->whereNotNull('pajak_id')->flatMap(function ($transaksi) {
            return $transaksi->detailTransaksis;
        })->sum(function ($detail) {
            return $detail->jumlah * (($detail->produk->harga_jual - $detail->produk->harga_beli) ?? 0);
        });

        $totalLabaPenjualanNonPajak = $transaksis->where('jenis', 2)->whereNull('pajak_id')->flatMap(function ($transaksi) {
            return $transaksi->detailTransaksis;
        })->sum(function ($detail) {
            return $detail->jumlah * (($detail->produk->harga_jual - $detail->produk->harga_beli) ?? 0);
        });

        $totalPenjualanPajak = $transaksis->where('jenis', 2)->whereNotNull('pajak_id')->sum(function ($detail) {
            return $detail->total;
        });

        $totalPenjualanNonPajak = $transaksis->where('jenis', 2)->whereNull('pajak_id')->sum(function ($detail) {
            return $detail->total;
        });

        $totalPajakPenjualan = $transaksis
            ->where('jenis', 2)
            ->whereNotNull('pajak_id')
            ->sum(function ($transaksi) {
                $pajak = Pajak::find($transaksi->pajak_id);
                if ($pajak && $pajak->nilai > 0) {
                    return ($pajak->nilai / (100 + $pajak->nilai)) * $transaksi->total;
                }
                return 0;
            });


        $totalPenjualan = $totalPenjualanPajak + $totalPenjualanNonPajak;
        $totalPenjualanBersih =  $totalPenjualan - $totalPajakPenjualan;
        $totalLabaPenjualan = $totalLabaPenjualanNonPajak + $totalLabaPenjualanPajak;
        $totalSelisihPajak = $totalPajakPenjualan - $totalPajakPembelian;

        // Hitung laba
        $laba = ($totalPenjualan - $totalPajakPenjualan) - $totalPembelian;
        $produkList = Produk::all();

        $totalPotensiPenjualan = $produkList->sum(function ($produk) {
            return $produk->stok * $produk->harga_jual;
        });

        $totalPotensiPembelian = $produkList->sum(function ($produk) {
            return $produk->stok * $produk->harga_beli;
        });

        $potensiLaba = ($totalPotensiPenjualan - $totalPotensiPembelian) + $totalLabaPenjualan;

        return view('page.laporan_laba_rugi', compact('totalPenjualan', 'totalPembelian', 'laba', 'totalPenjualanPajak', 'totalSelisihPajak', 'totalPembelianPajak', 'totalPajakPenjualan', 'totalPajakPembelian', 'totalPembelianBersih', 'totalPenjualanNonPajak', 'totalPenjualanBersih', 'totalPembelianNonPajak', 'potensiLaba', 'totalLabaPenjualan'));
    }


    public function kasir()
    {
        $pelanggans = Pelanggan::all();
        $pembayarans = Pembayaran::all();
        $pajaks = Pajak::all();
        $produks = Produk::paginate(15);
        $produks_all = Produk::all();
        $userId = auth()->id();
        $keranjangs = Keranjang::with('produk')->where('user_id', $userId)->get();

        $totalItems = $keranjangs->sum('jumlah'); // atau ->count() jika itu maksudnya jumlah baris
        $totalCart = $keranjangs->sum(function ($item) {
            return $item->jumlah * $item->produk->harga_jual;
        });
        $nilaiPajak = 0;
        $totalPayment = $totalCart;
        return view('page.kasir', compact('produks', 'pajaks', 'pelanggans', 'produks_all', 'keranjangs', 'totalItems', 'totalCart', 'pembayarans', 'nilaiPajak', 'totalPayment'));
    }

    public function refreshKeranjang(Request $request)
    {
        $userId = auth()->id();
        $keranjangs = Keranjang::with('produk')->where('user_id', $userId)->get();

        $totalItems = $keranjangs->sum('jumlah');
        $totalCart = $keranjangs->sum(function ($item) {
            return $item->jumlah * $item->produk->harga_jual;
        });

        // Ambil nilai pajak berdasarkan ID yang dikirim dari frontend
        $pajakId = $request->input('pajak_id');
        $pajak = Pajak::find($pajakId);
        $nilaiPajak = $pajak ? $pajak->nilai : 0;
        if ($nilaiPajak > 0) {
            $totalPayment = $totalCart + ($totalCart * ($nilaiPajak / 100));
        } else {
            $totalPayment = $totalCart + ($totalCart * ($nilaiPajak / 100));
        }

        return response()->json([
            'html' => view('partials.keranjang', compact('keranjangs'))->render(),
            'totalItems' => $totalItems,
            'totalCart' => number_format($totalCart, 0, ',', '.'),
            'nilaiPajak' => $nilaiPajak,
            'totalPayment' => number_format($totalPayment, 0, ',', '.'),
        ]);
    }

    public function editKasir($id, Request $request)
    {
        $transaksi = Transaksi::findOrFail($id);

        // Cek apakah perlu redirect dengan parameter tambahan
        $params = [];

        if ($transaksi->pajak_id && !$request->has('id_pajak')) {
            $params['id_pajak'] = $transaksi->pajak_id;
        }

        if ($transaksi->pelanggan_id && !$request->has('id_pelanggan')) {
            $params['id_pelanggan'] = $transaksi->pelanggan_id;
        }

        // Kalau ada params yang harus ditambahkan, redirect
        if (!empty($params)) {
            return redirect()->route('kasir.edit', ['id' => $id] + $params);
        }

        // Data lainnya
        $pelanggans = Pelanggan::all();
        $pembayarans = Pembayaran::all();
        $pajaks = Pajak::all();
        $produks = Produk::paginate(12);
        $produks_all = Produk::all();
        $userId = auth()->id();
        $keranjangs = DetailTransaksi::with('produk')->where('transaksi_id', $id)->get();

        $totalItems = $keranjangs->sum('jumlah');
        $totalCart = $keranjangs->sum(function ($item) {
            return $item->jumlah * $item->produk->harga_jual;
        });
        $nilaiPajak = 0;
        $totalPayment = $totalCart;

        return view('edit-kasir.kasir', compact(
            'produks',
            'pajaks',
            'pelanggans',
            'produks_all',
            'keranjangs',
            'totalItems',
            'totalCart',
            'pembayarans',
            'nilaiPajak',
            'totalPayment',
            'transaksi'
        ));
    }

    public function refreshTransaksi(Request $request, $id)
    {
        $userId = auth()->id();
        $keranjangs = DetailTransaksi::with('produk')->where('transaksi_id', $id)->get();

        $totalItems = $keranjangs->sum('jumlah');
        $totalCart = $keranjangs->sum(function ($item) {
            return $item->jumlah * $item->produk->harga_jual;
        });

        // Ambil nilai pajak berdasarkan ID yang dikirim dari frontend

        $transaksi = Transaksi::find($id);
        if ($transaksi->pajak_id) {
            $pajak = Pajak::find($transaksi->pajak_id);
        } else {
            $pajak = null;
        }
        $nilaiPajak = $pajak ? $pajak->nilai : 0;
        if ($nilaiPajak > 0) {
            $totalPayment = $totalCart + ($totalCart * ($nilaiPajak / 100));
        } else {
            $totalPayment = $totalCart + ($totalCart * ($nilaiPajak / 100));
        }

        return response()->json([
            'html' => view('partials.keranjang', compact('keranjangs'))->render(),
            'totalItems' => $totalItems,
            'totalCart' => number_format($totalCart, 0, ',', '.'),
            'nilaiPajak' => $nilaiPajak,
            'totalPayment' => number_format($totalPayment, 0, ',', '.'),
        ]);
    }

    public function storeTransaksiBarcode(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'barcode' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('failed', implode('<br>', $validator->errors()->all()));
        }

        $barcode = $request->barcode;
        $produk = Produk::where('barcode', $barcode)->first();
        // dd($produk);

        if (!$produk) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        if ($produk->stok <= 0) {
            return response()->json(['success' => false, 'message' => 'Gagal menambahkan barang, jumlah melebihi stok tersedia'], 400);
        }

        $userId = auth()->id();
        $keranjang = Keranjang::where('user_id', $userId)
            ->where('produk_id', $produk->id)
            ->first();

        if ($keranjang) {
            if ($produk->stok < 1) {
                return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi untuk menambah produk'], 400);
            }

            $keranjang->jumlah += 1;
            $keranjang->total = $keranjang->jumlah * $produk->harga_jual;
            $keranjang->save();
        } else {
            Keranjang::create([
                'user_id' => $userId,
                'produk_id' => $produk->id,
                'harga' => $produk->harga_jual,
                'jumlah' => 1,
                'total' => $produk->harga_jual,
            ]);
        }

        // Kurangi stok produk setelah pengecekan
        $produk->stok -= 1;
        $produk->save();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'sale_price' => $produk->harga_jual
        ]);
    }
    public function updateTransaksiBarcode(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'barcode' => 'required',
            'transaksi_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('failed', implode('<br>', $validator->errors()->all()));
        }

        $transaksiId = $request->transaksi_id;

        $barcode = $request->barcode;
        $produk = Produk::where('barcode', $barcode)->first();
        // dd($produk);

        if (!$produk) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        if ($produk->stok <= 0) {
            return response()->json(['success' => false, 'message' => 'Gagal menambahkan barang, jumlah melebihi stok tersedia'], 400);
        }

        $userId = auth()->id();
        $keranjang = DetailTransaksi::where('user_id', $userId)
            ->where('produk_id', $produk->id)
            ->where('transaksi_id', $transaksiId)
            ->first();

        if ($keranjang) {
            if ($produk->stok < 1) {
                return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi untuk menambah produk'], 400);
            }

            $keranjang->jumlah += 1;
            $keranjang->total = $keranjang->jumlah * $produk->harga_jual;
            $keranjang->save();
        } else {
            DetailTransaksi::create([
                'transaksi_id' => $transaksiId,
                'user_id' => $userId,
                'produk_id' => $produk->id,
                'harga' => $produk->harga_jual,
                'jumlah' => 1,
                'total' => $produk->harga_jual,
            ]);
        }

        // Kurangi stok produk setelah pengecekan
        $produk->stok -= 1;
        $produk->save();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'sale_price' => $produk->harga_jual
        ]);
    }
    public function storeTransaksi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pelanggan_id' => 'required',
            'pembayaran_id' => 'required',
            'total' => 'required',
            'bayar' => 'required',
            'kembalian' => 'required',
            'bukti' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('failed', implode('<br>', $validator->errors()->all()));
        }

        $userId = auth()->id();
        $bukti = null;

        if ($request->hasFile('bukti')) {
            $bukti = $request->file('bukti')->store('bukti', 'public');
        }

        $keranjangs = Keranjang::where('user_id', $userId)->get();
        $totalKeranjang = $keranjangs->sum('total');
        // Tambah pajak jika ada
        $pajakAmount = 0;
        if ($request->filled('pajak_id')) {
            $pajak = Pajak::find($request->pajak_id);
            if ($pajak) {
                $pajakAmount = ($pajak->nilai / 100) * $totalKeranjang;
                $totalKeranjang += $pajakAmount;
            }
        }

        // Buat transaksi baru
        $transaksi = Transaksi::create([
            'user_id' => $userId,
            'pelanggan_id' => $request->pelanggan_id,
            'pembayaran_id' => $request->pembayaran_id,
            'pajak_id' => $request->pajak_id ?? null,
            'total' => $totalKeranjang,
            'bayar' => $request->bayar,
            'kembalian' => $request->kembalian,
            'jenis' => 2,
            'bukti' => $bukti,
            'tanggal' => now(),
        ]);

        // Simpan ke detail_transaksis
        foreach ($keranjangs as $item) {
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'user_id' => $item->user_id,
                'produk_id' => $item->produk_id,
                'harga' => $item->harga,
                'jumlah' => $item->jumlah,
                'total' => $item->total,
                'ket' => $item->ket ?? null,
            ]);
        }

        // Hapus isi keranjang
        Keranjang::where('user_id', $userId)->delete();
        return redirect()->route('kasir')
            ->with('nota_id', $transaksi->id)
            ->with('success', 'Transaksi berhasil disimpan');
    }

    public function updateTransaksi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaksi_id' => 'required',
            'pelanggan_id' => 'required',
            'pembayaran_id' => 'required',
            'total' => 'required',
            'bayar' => 'required',
            'kembalian' => 'required',
            'bukti' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('failed', implode('<br>', $validator->errors()->all()));
        }

        $idTransaksi = $request->transaksi_id;
        $transaksi = Transaksi::find($idTransaksi);

        if (!$transaksi) {
            return redirect()->back()->with('failed', 'Transaksi tidak ditemukan');
        }

        // Ambil total dari detail transaksi
        $detailTransaksi = DetailTransaksi::where('transaksi_id', $idTransaksi)->get();
        $totalTransaksi = $detailTransaksi->sum('total');
        // Tambah pajak jika ada
        $pajakAmount = 0;
        if ($request->filled('pajak_id')) {
            $pajak = Pajak::find($request->pajak_id);
            if ($pajak) {
                $pajakAmount = ($pajak->nilai / 100) * $totalTransaksi;
                $totalTransaksi += $pajakAmount;
            }
        }

        // Cek dan simpan file baru, hapus file lama jika perlu
        if ($request->hasFile('bukti')) {
            if ($transaksi->bukti && Storage::disk('public')->exists($transaksi->bukti)) {
                Storage::disk('public')->delete($transaksi->bukti);
            }

            $bukti = $request->file('bukti')->store('bukti', 'public');
            $transaksi->bukti = $bukti;
        }

        // Update data transaksi
        $transaksi->pelanggan_id = $request->pelanggan_id;
        $transaksi->pembayaran_id = $request->pembayaran_id;
        $transaksi->pajak_id = $request->pajak_id;
        $transaksi->total = $totalTransaksi;
        $transaksi->bayar = $request->bayar;
        $transaksi->kembalian = $request->kembalian;

        $transaksi->save();

        return redirect()->route('transaksi.penjualan')->with('success', 'Transaksi berhasil disimpan');
    }

    public function storeProdukTransaksi($idProduk, $idTransaksi) //update-stok 
    {
        $produk = Produk::find($idProduk);
        if (!$produk) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }
        $transaksi = Transaksi::find($idTransaksi);

        $userId = auth()->id();
        $detaiTransaksi = DetailTransaksi::where('user_id', $userId)
            ->where('produk_id', $produk->id)
            ->where('transaksi_id', $idTransaksi)
            ->first();

        if ($detaiTransaksi) {
            $detaiTransaksi->jumlah += 1;
            $detaiTransaksi->total = $detaiTransaksi->jumlah * $produk->harga_jual;
            $detaiTransaksi->save();
        } else {
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'user_id' => $userId,
                'produk_id' => $produk->id,
                'harga' => $produk->harga_jual,
                'jumlah' => 1,
                'total' => $produk->harga_jual,
            ]);
        }

        // Kurangi stok produk langsung
        $produk->stok -= 1;
        $produk->save();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'sale_price' => $produk->harga_jual
        ]);
    }

    public function updateProdukTransaksi($id, Request $request) //update-stok 
    {
        $detailTransaksi = DetailTransaksi::find($id);

        if (!$detailTransaksi) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        $newQty = $request->input('qty');
        $oldQty = $detailTransaksi->jumlah;
        $selisih = $newQty - $oldQty;
        $produk = $detailTransaksi->produk;

        if ($newQty <= 0) {
            return response()->json(['success' => false, 'message' => 'Jumlah produk tidak valid'], 400);
        }

        if ($newQty > ($detailTransaksi->produk->stok + $oldQty)) {
            return response()->json(['success' => false, 'message' => 'Jumlah melebihi stok yang tersedia']);
        }

        // Update stok produk
        if ($selisih > 0) {
            $produk->stok -= $selisih;
        } elseif ($selisih < 0) {
            $produk->stok += abs($selisih);
        }
        $produk->save();

        $detailTransaksi->jumlah = $newQty;
        $detailTransaksi->harga = $detailTransaksi->produk->harga_jual;
        $detailTransaksi->total = $detailTransaksi->produk->harga_jual * $newQty;

        $detailTransaksi->save();

        $keranjangs = DetailTransaksi::with('produk')->get();

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil diperbarui',
            'keranjangs' => view('partials.keranjang', compact('keranjangs'))->render() // Mem-parse tampilan keranjang
        ]);
    }

    public function destroyProdukTransaksi($id) //update-stok 
    {
        $detailTransaksi = DetailTransaksi::with('produk')->find($id);
        $detailTransaksi->delete();

        // Tambahkan stok kembali
        $produk = $detailTransaksi->produk;
        $produk->stok += $detailTransaksi->jumlah;
        $produk->save();

        return response()->json(['success' => true, 'message' => 'Produk berhasil dihapus dari detailTransaksi']);
    }

    public function destroyAllDetailTransaksi($id) //update-stok 
    {
        $transaksi = Transaksi::with('detailTransaksis')->findOrFail($id);

        foreach ($transaksi->detailTransaksis as $detail) {
            if ($detail->produk) {
                $detail->produk->stok += $detail->jumlah;
                $detail->produk->save();
            }
        }


        $transaksi->detailTransaksis()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus dari transaksi'
        ]);
    }

    public function storeKeranjang($id) // update-stok
    {
        $produk = Produk::find($id);
        if (!$produk) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        if ($produk->stok <= 0) {
            return response()->json(['success' => false, 'message' => 'Stok produk habis'], 400);
        }

        $userId = auth()->id();
        $keranjang = Keranjang::where('user_id', $userId)
            ->where('produk_id', $produk->id)
            ->first();

        if ($keranjang) {
            if ($produk->stok < 1) {
                return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi untuk menambah produk'], 400);
            }

            $keranjang->jumlah += 1;
            $keranjang->total = $keranjang->jumlah * $produk->harga_jual;
            $keranjang->save();
        } else {
            Keranjang::create([
                'user_id' => $userId,
                'produk_id' => $produk->id,
                'harga' => $produk->harga_jual,
                'jumlah' => 1,
                'total' => $produk->harga_jual,
            ]);
        }

        // Kurangi stok produk setelah pengecekan
        $produk->stok -= 1;
        $produk->save();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'sale_price' => $produk->harga_jual
        ]);
    }


    public function updateKeranjang($id, Request $request) // update-stok 
    {
        $keranjang = Keranjang::find($id);

        if (!$keranjang) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        $newQty = (int) $request->input('qty');
        $oldQty = $keranjang->jumlah;
        $selisih = $newQty - $oldQty;
        $produk = $keranjang->produk;

        if ($newQty <= 0) {
            return response()->json(['success' => false, 'message' => 'Jumlah produk tidak valid'], 400);
        }

        // Jika menambah jumlah, pastikan stok cukup
        if ($selisih > 0 && $produk->stok < $selisih) {
            return response()->json(['success' => false, 'message' => 'Jumlah melebihi stok yang tersedia']);
        }

        // Update stok produk
        if ($selisih > 0) {
            $produk->stok -= $selisih;
        } elseif ($selisih < 0) {
            $produk->stok += abs($selisih);
        }
        $produk->save();

        // Update keranjang
        $keranjang->jumlah = $newQty;
        $keranjang->harga = $produk->harga_jual;
        $keranjang->total = $produk->harga_jual * $newQty;
        $keranjang->save();

        $keranjangs = Keranjang::with('produk')->get();

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil diperbarui',
            'keranjangs' => view('partials.keranjang', compact('keranjangs'))->render()
        ]);
    }

    public function destroyAllKeranjang() //update-stok 
    {
        $userId = auth()->id();
        $keranjangs = Keranjang::with('produk')->where('user_id', $userId)->get();

        foreach ($keranjangs as $keranjang) {
            if ($keranjang->produk) {
                $keranjang->produk->stok += $keranjang->jumlah;
                $keranjang->produk->save();
            }
        }

        Keranjang::where('user_id', $userId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Semua produk berhasil dihapus dari keranjang dan stok dikembalikan'
        ]);
    }


    public function destroyKeranjang($id) //update-stok 
    {
        $keranjang = Keranjang::with('produk')->find($id);

        if (!$keranjang) {
            return response()->json(['success' => false, 'message' => 'Data keranjang tidak ditemukan'], 404);
        }

        // Tambahkan stok kembali
        $produk = $keranjang->produk;
        $produk->stok += $keranjang->jumlah;
        $produk->save();

        $keranjang->delete();

        return response()->json(['success' => true, 'message' => 'Produk berhasil dihapus dari keranjang']);
    }


    public function destroyTransaksi($id) // update-stok 
    {
        $transaksi = Transaksi::with('detailTransaksis')->findOrFail($id);

        // Update stok sebelum menghapus detail
        foreach ($transaksi->detailTransaksis as $detail) {
            $produk = Produk::find($detail->produk_id);

            if ($produk) {
                if ($transaksi->jenis == 1) {
                    $stok_awal = $produk->stok;
                    if ($stok_awal - $detail->jumlah < 0) {
                        return redirect()->back()->withInput()->with(
                            'failed',
                            'Pembelian tidak bisa dihapus, stok sekarang (' . $stok_awal .
                                ') kurang dari jumlah yang akan dikurangi (' . $detail->jumlah .
                                '), stok sudah dijual.'
                        );
                    }
                    // Jika pembelian → stok dikurangi
                    $produk->stok -= $detail->jumlah;
                } elseif ($transaksi->jenis == 2) {
                    // Jika penjualan → stok dikembalikan (ditambah)
                    $produk->stok += $detail->jumlah;
                }
                $produk->save();
            }
        }

        // Hapus detail dan transaksi
        $transaksi->detailTransaksis()->delete();
        $transaksi->delete();

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus');
    }


    public function cetakNota($id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'user', 'pembayaran', 'detailTransaksis'])->findOrFail($id);
        // dd( $transaksi);
        return view('partials.nota', compact('transaksi'));
    }
    public function cetakPdfPenjualan(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $transaksis = Transaksi::with('detailTransaksis.produk')
            ->where('jenis', 2)
            ->when(auth()->user()->hasRole('kasir'), function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59'
                ]);
            })
            ->get();

        $pdf = Pdf::loadView('pdf.penjualan', [
            'transaksis' => $transaksis,
            'startDate' => $startDate,
            'endDate' => $endDate
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-transaksi.pdf');
    }
    public function cetakPdfPembelian(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $transaksis = Transaksi::with('detailTransaksis.produk')
            ->where('jenis', 1)
            ->when(auth()->user()->hasRole('kasir'), function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59'
                ]);
            })
            ->get();

        $pdf = Pdf::loadView('pdf.pembelian', [
            'transaksis' => $transaksis,
            'startDate' => $startDate,
            'endDate' => $endDate
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-transaksi.pdf');
    }
    public function cetakPdfStok(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $produks = Produk::all();

        // Ambil semua transaksi dengan relasi detailTransaksis dan produk
        $transaksis = Transaksi::with('detailTransaksis', 'detailTransaksis.produk')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            })
            ->get();

        // Siapkan array laporan
        $laporanStok = [];

        // Loop setiap produk
        foreach ($produks as $produk) {
            // Hitung total pembelian
            $totalPembelian = $transaksis->where('jenis', 1)->flatMap(function ($transaksi) {
                return $transaksi->detailTransaksis;
            })->where('produk_id', $produk->id)->sum('jumlah');

            // Hitung total penjualan
            $totalPenjualan = $transaksis->where('jenis', 2)->flatMap(function ($transaksi) {
                return $transaksi->detailTransaksis;
            })->where('produk_id', $produk->id)->sum('jumlah');

            // Hitung total penyesuaian
            $totalPenyesuaian = $transaksis->where('jenis', 3)->flatMap(function ($transaksi) {
                return $transaksi->detailTransaksis;
            })->where('produk_id', $produk->id)->sum('jumlah');

            // Stok akhir
            $stokAkhir = $produk->stok + $totalPembelian - $totalPenjualan + $totalPenyesuaian;

            // Simpan ke laporan
            $laporanStok[] = [
                'nama_produk' => $produk->nama,
                'stok' => $produk->stok,
                'pembelian' => $totalPembelian,
                'penjualan' => $totalPenjualan,
                'penyesuaian' => $totalPenyesuaian,
                'stok_akhir' => $stokAkhir,
            ];
        }

        $pdf = Pdf::loadView('pdf.stok', [
            'laporanStok' => $laporanStok,
            'startDate' => $startDate,
            'endDate' => $endDate
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-transaksi.pdf');
    }
    public function cetakPdfLabaRugi(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        // Ambil semua transaksi dengan relasi produk di detail
        $transaksisPembelian = Transaksi::with('detailTransaksis.produk')
            ->get();

        $totalPembelianPajak = $transaksisPembelian->where('jenis', 1)->whereNotNull('pajak_id')->sum(function ($detail) {
            return $detail->total;
        });

        $totalPembelianNonPajak = $transaksisPembelian->where('jenis', 1)->whereNull('pajak_id')->sum(function ($detail) {
            return $detail->total;
        });
        $totalPajakPembelian = $transaksisPembelian
            ->where('jenis', 1)
            ->whereNotNull('pajak_id')
            ->sum(function ($transaksi) {
                $pajak = Pajak::find($transaksi->pajak_id);
                if ($pajak && $pajak->nilai > 0) {
                    return ($pajak->nilai / (100 + $pajak->nilai)) * $transaksi->total;
                }
                return 0;
            });

        $totalPembelian =  $totalPembelianPajak + $totalPembelianNonPajak;
        $totalPembelianBersih =  $totalPembelian - $totalPajakPembelian;


        $transaksis = Transaksi::with('detailTransaksis.produk')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59'
                ]);
            })
            ->get();

        // Total Penjualan (jenis = 2)
        $totalLabaPenjualanPajak = $transaksis->where('jenis', 2)->whereNotNull('pajak_id')->flatMap(function ($transaksi) {
            return $transaksi->detailTransaksis;
        })->sum(function ($detail) {
            return $detail->jumlah * (($detail->produk->harga_jual - $detail->produk->harga_beli) ?? 0);
        });

        $totalLabaPenjualanNonPajak = $transaksis->where('jenis', 2)->whereNull('pajak_id')->flatMap(function ($transaksi) {
            return $transaksi->detailTransaksis;
        })->sum(function ($detail) {
            return $detail->jumlah * (($detail->produk->harga_jual - $detail->produk->harga_beli) ?? 0);
        });

        $totalPenjualanPajak = $transaksis->where('jenis', 2)->whereNotNull('pajak_id')->sum(function ($detail) {
            return $detail->total;
        });

        $totalPenjualanNonPajak = $transaksis->where('jenis', 2)->whereNull('pajak_id')->sum(function ($detail) {
            return $detail->total;
        });

        $totalPajakPenjualan = $transaksis
            ->where('jenis', 2)
            ->whereNotNull('pajak_id')
            ->sum(function ($transaksi) {
                $pajak = Pajak::find($transaksi->pajak_id);
                if ($pajak && $pajak->nilai > 0) {
                    return ($pajak->nilai / (100 + $pajak->nilai)) * $transaksi->total;
                }
                return 0;
            });


        $totalPenjualan = $totalPenjualanPajak + $totalPenjualanNonPajak;
        $totalPenjualanBersih =  $totalPenjualan - $totalPajakPenjualan;
        $totalLabaPenjualan = $totalLabaPenjualanNonPajak + $totalLabaPenjualanPajak;
        $totalSelisihPajak = $totalPajakPenjualan - $totalPajakPembelian;

        // Hitung laba
        $laba = ($totalPenjualan - $totalPajakPenjualan) - $totalPembelian;
        $produkList = Produk::all();

        $totalPotensiPenjualan = $produkList->sum(function ($produk) {
            return $produk->stok * $produk->harga_jual;
        });

        $totalPotensiPembelian = $produkList->sum(function ($produk) {
            return $produk->stok * $produk->harga_beli;
        });

        $potensiLaba = ($totalPotensiPenjualan - $totalPotensiPembelian) + $totalLabaPenjualan;


        $pdf = Pdf::loadView('pdf.laba-rugi', [
            'totalPenjualan' => $totalPenjualan,
            'totalPembelian' => $totalPembelian,
            'laba' => $laba,
            'totalPenjualanPajak' => $totalPenjualanPajak,
            'totalSelisihPajak' => $totalSelisihPajak,
            'totalPembelianPajak' => $totalPembelianPajak,
            'totalPajakPenjualan' => $totalPajakPenjualan,
            'totalPajakPembelian' => $totalPajakPembelian,
            'totalPembelianBersih' => $totalPembelianBersih,
            'totalPenjualanNonPajak' => $totalPenjualanNonPajak,
            'totalPenjualanBersih' => $totalPenjualanBersih,
            'totalPembelianNonPajak' => $totalPembelianNonPajak,
            'potensiLaba' => $potensiLaba,
            'totalLabaPenjualan' => $totalLabaPenjualan,
            'startDate' => $startDate,
            'endDate' => $endDate
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-laba-rugi.pdf');
    }
}
