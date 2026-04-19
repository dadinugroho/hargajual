<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('item_prices', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('org_id')
                ->constrained('item_price_categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('item_prices', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\ItemPriceCategory::class);
            $table->dropColumn('category_id');
        });
    }
};
