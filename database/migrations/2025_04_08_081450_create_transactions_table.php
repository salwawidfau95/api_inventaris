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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique(); // e.g. TRX0001
            $table->unsignedBigInteger('user_id'); // kasir
            $table->unsignedBigInteger('member_id')->nullable(); // boleh null (non-member)
            $table->integer('total_price'); // total semua produk
            $table->integer('total_payment'); // uang yang dibayar
            $table->integer('change'); // kembalian
            $table->timestamps();

            // Relasi
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('member_id')->references('id')->on('members');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
