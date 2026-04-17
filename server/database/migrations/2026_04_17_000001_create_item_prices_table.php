<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_id')->constrained('organizations')->cascadeOnDelete();
            $table->string('name');
            $table->string('base_unit')->default('');
            $table->decimal('qty_per_box', 15, 4)->default(0);
            $table->decimal('purchase_price', 15, 4)->default(0);
            $table->decimal('disc1', 8, 4)->nullable()->default(0);
            $table->decimal('disc2', 8, 4)->nullable()->default(0);
            $table->decimal('disc3', 8, 4)->nullable()->default(0);
            $table->decimal('handling_cost', 15, 4)->nullable()->default(0);
            $table->decimal('additional_cost_base_unit', 15, 4)->nullable()->default(0);
            $table->decimal('additional_cost_box', 15, 4)->nullable()->default(0);
            $table->decimal('cost_price_base_unit', 15, 4)->default(0);
            $table->decimal('cost_price_box', 15, 4)->default(0);
            $table->decimal('rounding_base_unit', 15, 4)->default(0);
            $table->decimal('rounding_box', 15, 4)->default(0);
            $table->decimal('profit_base_unit', 8, 4)->default(0);
            $table->decimal('profit_box', 8, 4)->default(0);
            $table->decimal('selling_price_base_unit', 15, 4)->default(0);
            $table->decimal('selling_price_box', 15, 4)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_prices');
    }
};
