<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('item_prices', function (Blueprint $table) {
            $table->decimal('disc4', 8, 4)->nullable()->default(0)->after('disc3');
            $table->decimal('disc5', 8, 4)->nullable()->default(0)->after('disc4');
            $table->decimal('disc6', 8, 4)->nullable()->default(0)->after('disc5');
        });
    }

    public function down(): void
    {
        Schema::table('item_prices', function (Blueprint $table) {
            $table->dropColumn(['disc4', 'disc5', 'disc6']);
        });
    }
};
