<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Rename existing columns to match blueprint if they exist
            if (Schema::hasColumn('orders', 'courier')) {
                $table->renameColumn('courier', 'shipping_courier');
            }
            if (Schema::hasColumn('orders', 'courier_service')) {
                $table->renameColumn('courier_service', 'shipping_service');
            }
            
            // Add missing columns
            $table->string('shipping_etd', 20)->nullable()->after('shipping_service');
            
            // Normalize address fields to match blueprint names if desired, 
            // but we can also just use the existing shipping_name, etc.
            // Let's add recipient_notes as it's missing
            $table->text('recipient_notes')->nullable()->after('customer_notes');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'shipping_courier')) {
                $table->renameColumn('shipping_courier', 'courier');
            }
            if (Schema::hasColumn('orders', 'shipping_service')) {
                $table->renameColumn('shipping_service', 'courier_service');
            }
            $table->dropColumn(['shipping_etd', 'recipient_notes']);
        });
    }
};
