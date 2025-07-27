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
        Schema::table('data_base_barang', function (Blueprint $table) {
            $table->integer('Quantity')->default(0)->after('item_name');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_base_barang', function (Blueprint $table) {
            //
        });
    }
};
