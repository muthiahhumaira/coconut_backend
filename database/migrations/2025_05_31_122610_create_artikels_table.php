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
        Schema::create('artikels', function (Blueprint $table) {
            $table->id(); // Ini adalah primary key dan otomatis unik
            $table->unsignedBigInteger('kategori_id'); // Hanya kolom biasa, tanpa constraint
            $table->string('penulis');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->date('tanggal_terbit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artikels');
    }
};