<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')
                  ->constrained()
                  ->onDelete('cascade');  // Progress ikut terhapus jika laporan dihapus
            $table->text('note');         // Catatan perkembangan dari petugas
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_updates');
    }
};