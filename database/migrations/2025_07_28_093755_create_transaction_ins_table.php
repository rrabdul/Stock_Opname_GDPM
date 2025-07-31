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
        Schema::create('transaction_ins', function (Blueprint $table) {
    $table->id();
    $table->string('item_code');
    $table->integer('qty_in');
    $table->string('source')->nullable(); // sumber barang
    $table->string('user_name'); // nama user yang input
    $table->timestamps(); // created_at = waktu transaksi
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_ins');
    }
};
