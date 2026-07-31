<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('label', 30);                      // "Rumah", "Kantor"
            $table->string('recipient_name', 100);
            $table->string('phone', 20);
            $table->unsignedSmallInteger('province_id');
            $table->unsignedSmallInteger('city_id');
            $table->string('postal_code', 10);
            $table->text('address_detail');                   // Jalan + nomor rumah
            $table->text('notes')->nullable();                // Patokan
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->index(['user_id', 'is_default']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
