<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('item_prices', function (Blueprint $table) {
            $table->unsignedSmallInteger('handling_qty')->default(1)->after('handling_cost');
        });
    }

    public function down(): void
    {
        Schema::table('item_prices', function (Blueprint $table) {
            $table->dropColumn('handling_qty');
        });
    }
};
