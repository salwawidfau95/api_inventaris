<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id'); // FK ke transaksi
            $table->unsignedBigInteger('product_id'); // FK ke produk
            $table->integer('quantity'); // jumlah beli
            $table->integer('price'); // harga satuan saat transaksi
            $table->integer('subtotal'); // quantity x price
            $table->timestamps();

            // Relasi
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_items');
    }
};