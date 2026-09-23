<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add new string column for status and the source column
        Schema::table('absensis', function (Blueprint $table) {
            $table->string('status_new', 50)->default('alpa')->after('status');
            $table->string('source', 50)->default('peserta')->nullable()->after('keterangan');
        });

        // 2. Copy data from old enum to new string column
        DB::table('absensis')->update(['status_new' => DB::raw('status')]);

        // 3. Drop old enum column
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        // 4. Rename new column to 'status'
        Schema::table('absensis', function (Blueprint $table) {
            $table->renameColumn('status_new', 'status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn('source');
            // Reverting back to enum is tricky without data loss if there are new values.
            // We just leave it as string in rollback, or you can manually restore it if needed.
        });
    }
};
