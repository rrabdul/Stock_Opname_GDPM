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

        Schema::create('transaction_returns', function (Blueprint $table) {
            $table->id();
            $table->string('item_code');
            $table->string('item_name');
            $table->integer('qty_return');
            $table->string('unit')->nullable();
            $table->string('source')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('user');
            $table->timestamps();
        });


        // Add foreign key constraint if needed
        Schema::table('transaction_returns', function (Blueprint $table) {
            $table->foreign('item_code')->references('item_code')->on('data_base_barang')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_returns');
    }
};
