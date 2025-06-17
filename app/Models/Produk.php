<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Produk extends Model
{
    protected $fillable = [
        'kategori_id', 'nama', 'harga_jual', 'harga_beli',
        'gambar', 'stok', 'stok_minim', 'barcode', 'ket'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
    public static function getLowStockProducts()
    {
        return DB::table('produks as p')
            ->select(
                'p.nama as nama_produk',
                'p.stok as total_stok',
                'p.barcode as barcode',
                'p.stok_minim as total_stok_minim'
            )
            ->whereColumn('p.stok', '<=', 'p.stok_minim')
            ->orderBy('p.stok', 'asc')
            ->get();
    }
}
