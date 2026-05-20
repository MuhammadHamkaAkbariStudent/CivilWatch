<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upvotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');    // Upvote ikut terhapus jika user dihapus
            $table->foreignId('report_id')
                  ->constrained()
                  ->onDelete('cascade');    // Upvote ikut terhapus jika laporan dihapus
            $table->unique(['user_id', 'report_id']); // 1 user hanya bisa upvote 1x per laporan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upvotes');
    }
};