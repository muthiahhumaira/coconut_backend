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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('personal_name')->nullable(); // Deskripsi bisa null
            $table->string('email')->nullable(); // Deskripsi bisa null
            $table->string('phone')->nullable(); // Path gambar, bisa null
            $table->string('message')->nullable(); // Kolom JSON untuk menyimpan spesifikasi
            $table->string('document')->nullable(); // Kolom JSON untuk menyimpan spesifikasi
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
