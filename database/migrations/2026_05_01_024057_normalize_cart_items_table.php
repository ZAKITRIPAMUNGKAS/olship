<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            // Rename price_snapshot to price
            if (Schema::hasColumn('cart_items', 'price_snapshot')) {
                $table->renameColumn('price_snapshot', 'price');
            }
            
            // Add options JSON if it doesn't exist
            if (!Schema::hasColumn('cart_items', 'options')) {
                $table->json('options')->nullable()->after('quantity');
            }

            // Drop variant_option_id as we use options JSON for simplicity in this phase
            if (Schema::hasColumn('cart_items', 'variant_option_id')) {
                $table->dropForeign(['variant_option_id']);
                $table->dropColumn('variant_option_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            if (Schema::hasColumn('cart_items', 'price')) {
                $table->renameColumn('price', 'price_snapshot');
            }
            $table->dropColumn('options');
            $table->foreignId('variant_option_id')->nullable()->constrained('product_variant_options')->onDelete('cascade');
        });
    }
};
