<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('failed_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['stock_sync', 'order_push']);
            $table->json('payload');
            $table->text('error_message');
            $table->tinyInteger('attempts')->unsigned()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('failed_sync_logs');
    }
};
