<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::all();
        return view('page.pelanggan', compact('pelanggans'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'no' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('failed', implode('<br>', $validator->errors()->all()));
        }
        $pelanggan = Pelanggan::create($request->only('nama', 'no', 'ket'));

        // Cek apakah parameter pelanggan=true ada
        if ($request->has('pelanggan') && $request->pelanggan == true) {
            // Ambil parameter yang sudah ada
            $params = $request->only(['id_pelanggan', 'id_pajak', 'page']);

            // Pastikan id_pelanggan selalu update dengan yang benar
            $params['id_pelanggan'] = $pelanggan->id;

            // Cek apakah ada input hidden id_pajak dan masukkan ke param
            if ($request->has('id_pajak')) {
                $params['id_pajak'] = $request->id_pajak;
            }

            return redirect()->route('kasir', $params);
        }

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'no' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('failed', implode('<br>', $validator->errors()->all()));
        }

        $pelanggan->update($request->only('nama', 'no', 'ket'));
        return redirect()->back()->with('success', 'Data berhasil diperbarui');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
