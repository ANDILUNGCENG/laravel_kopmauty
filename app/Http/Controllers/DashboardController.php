<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Jumlahkan kolom 'bayar' berdasarkan jenis
        $totalBayarJenis1 = Transaksi::where('jenis', 1)
            ->when(auth()->user()->hasRole('kasir'), function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->sum('total');

        $totalBayarJenis2 = Transaksi::where('jenis', 2)
            ->when(auth()->user()->hasRole('kasir'), function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->sum('total');

        return view('page.dashboard', [
            'title' => 'Dashboard',
            'totalPembelian' => $totalBayarJenis1,
            'totalPenjualan' => $totalBayarJenis2,
        ]);
    }
}
