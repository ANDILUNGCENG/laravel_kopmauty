<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::all();
        return view('page.pembayaran', compact('pembayarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        Pembayaran::create($request->only('nama', 'ket'));
        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        $pembayaran->update($request->only('nama', 'ket'));
        return redirect()->back()->with('success', 'Data berhasil diperbarui');
    }

    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
