<?php

namespace App\Http\Controllers;

use App\Models\Pajak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PajakController extends Controller
{
    public function index()
    {
        $pajaks = Pajak::all();
        return view('page.pajak', compact('pajaks'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'nilai' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('failed', implode('<br>', $validator->errors()->all()));
        }

        Pajak::create($request->only('nama', 'nilai', 'ket'));
        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, Pajak $pajak)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'nilai' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('failed', implode('<br>', $validator->errors()->all()));
        }

        $pajak->update($request->only('nama', 'nilai', 'ket'));
        return redirect()->back()->with('success', 'Data berhasil diperbarui');
    }

    public function destroy(Pajak $pajak)
    {
        $pajak->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
