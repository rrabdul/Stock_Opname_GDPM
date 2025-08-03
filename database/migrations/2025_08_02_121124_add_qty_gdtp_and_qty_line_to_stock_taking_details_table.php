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
        Schema::table('stock_taking_details', function (Blueprint $table) {
            $table->integer('qty_gdtp')->nullable()->after('item_name');
            $table->integer('qty_line')->nullable()->after('qty_gdtp');
            $table->dropColumn('qty_aktual'); // jika sebelumnya sudah dipakai
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_taking_details', function (Blueprint $table) {
            //
        });
    }
};
