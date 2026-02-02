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
        Schema::create('survei', function (Blueprint $table) {
            $table->id();

            // relasi ke pengunjung (opsional tapi direkomendasikan)
            $table->foreignId('pengunjung_id')
                  ->nullable()
                  ->constrained('pengunjungs')
                  ->nullOnDelete();

            // jawaban survei
            $table->string('kepuasan');
            $table->string('pelayanan');
            $table->string('fasilitas')->nullable();

            // masukan & saran (2 kotak)
            $table->text('saran')->nullable();
            $table->text('masukan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survei');
    }

    /**
     * Reverse the migrations.
     */
    
};
