<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flash_sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flash_sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('variant_option_id')->nullable()->constrained('product_variant_options')->onDelete('cascade');
            $table->enum('discount_type', ['percent', 'fixed']);
            $table->decimal('discount_value', 15, 2);
            $table->decimal('flash_price', 15, 2);
            $table->integer('quota');
            $table->integer('sold_quota')->default(0);
            $table->integer('max_per_user')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flash_sale_items');
    }
};
