<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pembayaran_id')->constrained('pembayarans')->onDelete('cascade');
            $table->foreignId('pajak_id')->nullable()->constrained('pajaks')->onDelete('set null');
            $table->decimal('total', 15, 2);
            $table->decimal('bayar', 15, 2);
            $table->decimal('kembalian', 15, 2);
            $table->tinyInteger('jenis'); // 1 = pembelian, 2 = penjualan, 3 = stok_opname
            $table->string('bukti')->nullable(); // Gambar bukti transaksi
            $table->dateTime('tanggal');
            $table->text('ket')->nullable(); // Keterangan transaksi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
