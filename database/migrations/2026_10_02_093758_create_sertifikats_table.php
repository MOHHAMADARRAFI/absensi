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
        Schema::create('sertifikats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nomor_sertifikat')->nullable();
            $table->string('hasil_pkl');
            $table->integer('nilai_kerajinan');
            $table->integer('nilai_inisiatif');
            $table->integer('nilai_kerjasama');
            $table->integer('nilai_kedisiplinan');
            $table->integer('nilai_prestasi_kerja');
            $table->integer('jumlah_nilai');
            $table->decimal('nilai_rata_rata', 5, 2);
            $table->date('tanggal_sertifikat');
            $table->string('file_pdf')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikats');
    }
};
