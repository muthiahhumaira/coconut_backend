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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable(); // Deskripsi bisa null
            $table->text('detail_spesifikasi')->nullable(); // Deskripsi bisa null
            $table->string('gambar')->nullable(); // Path gambar, bisa null
            $table->json('spesifikasi')->nullable(); // Kolom JSON untuk menyimpan spesifikasi
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
