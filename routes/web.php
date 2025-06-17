<?php

use App\Http\Controllers\AutentikasiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PajakController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [AutentikasiController::class, 'index'])->name('login');
Route::post('/proses-login', [AutentikasiController::class, 'prosesLogin']);
Route::post('/logout', [AutentikasiController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('pembayaran', PembayaranController::class);
    Route::resource('pajak', PajakController::class);
    Route::resource('pelanggan', PelangganController::class);

    Route::get('/produk', [ProdukController::class, 'index'])->name('produk');
    // Route::get('/produk-create', [ProdukController::class, 'create'])->name('produk.create');
    Route::post('/produk/store', [ProdukController::class, 'store'])->name('produk.store');
    Route::post('/produk/update/{produk}', [ProdukController::class, 'update'])->name('produk.update');
    Route::get('/produk/delete/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');
    Route::get('/produk-barcode', [ProdukController::class, 'barcode'])->name('produk.barcode');


    Route::get('/pembelian', [TransaksiController::class, 'createPembelian'])->name('transaksi.create.pembelian');
    Route::post('/pembelian-store', [TransaksiController::class, 'storePembelian'])->name('transaksi.store.pembelian');
    Route::get('/pembelian/edit/{id}', [TransaksiController::class, 'editPembelian'])->name('pembelian.edit');
    Route::put('/pembelian/update/{id}', [TransaksiController::class, 'updatePembelian'])->name('pembelian.update');

    Route::get('/kasir', [TransaksiController::class, 'kasir'])->name('kasir');
    Route::get('/edit-kasir/{id}', [TransaksiController::class, 'editKasir'])->name('kasir.edit');
    Route::get('/transaksi/refresh/{id}', [TransaksiController::class, 'refreshTransaksi'])->name('transaksi.refresh');
    Route::post('/tambah-produk-transaksi/{idProduk}/{idTransaksi}', [TransaksiController::class, 'storeProdukTransaksi'])->name('transaksi.produk.store');
    Route::post('/update-produk-transaksi/{id}', [TransaksiController::class, 'updateProdukTransaksi'])->name('transaksi.produk.update');
    Route::delete('/hapus-produk-transaksi/{id}', [TransaksiController::class, 'destroyProdukTransaksi'])->name('transaksi.produk.destroy');
    Route::delete('/destroyAll-detail-transaksi/{id}', [TransaksiController::class, 'destroyAllDetailTransaksi'])->name('detail.transaksi.destroyAll');

    Route::get('/transaksi-penjualan', [TransaksiController::class, 'indexPenjualan'])->name('transaksi.penjualan');
    Route::get('/transaksi-pembelian', [TransaksiController::class, 'indexPembelian'])->name('transaksi.pembelian');
    Route::delete('/transaksi-destroy/{id}', [TransaksiController::class, 'destroyTransaksi'])->name('transaksi.destroy');

    Route::get('/laporan-penjualan', [TransaksiController::class, 'laporanPenjualan'])->name('laporan.transaksi.penjualan');
    Route::get('/laporan-pembelian', [TransaksiController::class, 'laporanPembelian'])->name('laporan.transaksi.pembelian');
    Route::get('/laporan-stok', [TransaksiController::class, 'laporanStok'])->name('laporan.stok');
    Route::get('/laporan-laba-rugi', [TransaksiController::class, 'laporanLabaRugi'])->name('laporan.laba.rugi');

    Route::post('/tambah-kranjang/{id}', [TransaksiController::class, 'storeKeranjang'])->name('keranjang.store');
    Route::post('/transaksi-store', [TransaksiController::class, 'storeTransaksi'])->name('transaksi.store');
    Route::post('/transaksi-barcode-store', [TransaksiController::class, 'storeTransaksiBarcode'])->name('transaksi.barcode.store');
    Route::put('/transaksi-barcode-update', [TransaksiController::class, 'updateTransaksiBarcode'])->name('transaksi.barcode.update');
    Route::put('/transaksi-update', [TransaksiController::class, 'updateTransaksi'])->name('transaksi.update');
    Route::delete('/destroyAll-kranjang', [TransaksiController::class, 'destroyAllKeranjang'])->name('keranjang.destroyAll');
    Route::post('/update-produk-keranjang/{id}', [TransaksiController::class, 'updateKeranjang'])->name('keranjang.produk.update');
    Route::delete('/hapus-produk-keranjang/{id}', [TransaksiController::class, 'destroyKeranjang'])->name('keranjang.produk.destroy');
    Route::get('/keranjang/refresh', [TransaksiController::class, 'refreshKeranjang'])->name('keranjang.refresh');


    Route::get('/transaksi/nota/{id}', [TransaksiController::class, 'cetakNota'])->name('transaksi.nota');
    Route::get('/transaksi/pdf-penjualan/', [TransaksiController::class, 'cetakPdfPenjualan'])->name('transaksi.pdf.penjualan');
    Route::get('/transaksi/pdf-pembelian/', [TransaksiController::class, 'cetakPdfPembelian'])->name('transaksi.pdf.pembelian');
    Route::get('/transaksi/pdf-stok/', [TransaksiController::class, 'cetakPdfStok'])->name('transaksi.pdf.stok');
    Route::get('/transaksi/pdf-LabaRugi/', [TransaksiController::class, 'cetakPdfLabaRugi'])->name('transaksi.pdf.labaRugi');
});
