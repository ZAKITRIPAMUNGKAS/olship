<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'source')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('source')->default('WMS')->after('sku')->comment('Single Source of Truth Origin');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'source')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('source');
            });
        }
    }
};
