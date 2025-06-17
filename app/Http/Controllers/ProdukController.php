<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Pajak;
use App\Models\Pembayaran;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::with('kategori')->get();
        $kategoris = Kategori::all();
        $pembayarans = Pembayaran::all();
        return view('page.produk', compact('produks', 'kategoris', 'pembayarans'));
    }

    // public function create()
    // {
    //     $produks = Produk::with('kategori')->get();
    //     $pajaks = Pajak::all();
    //     return view('create.produk', compact('produks', 'pajaks'));
    // }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori_id' => 'required',
            'nama' => 'required',
            'harga_jual' => 'required|numeric',
            'harga_beli' => 'required|numeric',
            'barcode' => 'nullable|min:13', // minimum barcode type tipe EAN13 adalah 13 digit
            // 'stok' => 'required|integer',
            'stok_minim' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('failed', implode('<br>', $validator->errors()->all()));
        }
        $data['stok'] = 0;

        $data = $request->except('gambar');
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        Produk::create($data);
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Produk $produk)
    {
        $validator = Validator::make($request->all(), [
            'kategori_id' => 'required',
            'nama' => 'required',
            'harga_jual' => 'required|numeric',
            'harga_beli' => 'required|numeric',
            // 'stok' => 'required|integer',
            'stok_minim' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('failed', implode('<br>', $validator->errors()->all()));
        }

        $data = $request->except('gambar');
        if ($request->hasFile('gambar')) {
            if ($produk->gambar) Storage::disk('public')->delete($produk->gambar);
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update($data);
        return redirect()->back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        if ($produk->gambar) Storage::disk('public')->delete($produk->gambar);
        $produk->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }

    public function barcode()
    {
        $produks = Produk::with('kategori')->get();
        $kategoris = Kategori::all();
        return view('page.barcode', compact('produks', 'kategoris'));
    }
}
