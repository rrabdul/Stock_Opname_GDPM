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
    Schema::table('transaction_ins', function (Blueprint $table) {
        $table->string('item_name')->nullable()->after('item_code');
    });
}

public function down()
{
    Schema::table('transaction_ins', function (Blueprint $table) {
        $table->dropColumn('item_name');
    });
}

};
