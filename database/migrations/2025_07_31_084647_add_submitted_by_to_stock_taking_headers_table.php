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
        Schema::table('stock_taking_headers', function (Blueprint $table) {
            $table->string('submitted_by')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_taking_headers', function (Blueprint $table) {
            //
        });
    }
};
