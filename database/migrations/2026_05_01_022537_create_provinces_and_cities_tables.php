<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->unsignedSmallInteger('id')->primary(); // ID dari Raja Ongkir
            $table->string('name', 100);
            $table->timestamps();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->unsignedSmallInteger('id')->primary();
            $table->unsignedSmallInteger('province_id');
            $table->string('name', 100);
            $table->string('type', 20); // "Kota" atau "Kabupaten"
            $table->string('postal_code', 10)->nullable();
            $table->timestamps();

            $table->foreign('province_id')->references('id')->on('provinces')->cascadeOnDelete();
            $table->index(['province_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
        Schema::dropIfExists('provinces');
    }
};
