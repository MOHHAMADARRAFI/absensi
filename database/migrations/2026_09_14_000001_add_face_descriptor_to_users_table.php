<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom face_descriptor ke tabel users
     * untuk menyimpan 128 nilai numerik hasil face recognition.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('face_descriptor')->nullable()
                ->comment('JSON array 128 nilai face descriptor dari face-api.js');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('face_descriptor');
        });
    }
};
