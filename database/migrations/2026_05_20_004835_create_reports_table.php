<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');     // Laporan ikut terhapus jika user dihapus
            $table->foreignId('district_id')
                  ->constrained()
                  ->onDelete('restrict');    // District tidak bisa dihapus jika masih ada laporan
            $table->string('title');
            $table->text('description');
            $table->string('image')->nullable();  // Path foto bukti kerusakan
            $table->enum('status', ['pending', 'published', 'in_progress', 'resolved', 'rejected'])
                  ->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};