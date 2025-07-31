<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_taking_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_taking_header_id')->constrained()->onDelete('cascade');
            $table->string('item_code');
            $table->string('item_name');
            $table->integer('qty_aktual');
            $table->string('user');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_taking_details');
    }
};
