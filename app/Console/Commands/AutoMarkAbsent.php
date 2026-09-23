<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutoMarkAbsent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:auto-mark-absent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis menandai tidak hadir peserta yang belum absen hingga pukul 15:00 di hari kerja.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now('Asia/Jakarta');
        
        // Cek apakah hari ini hari kerja (Senin - Jumat)
        // isWeekday() mengembalikan true jika hari Senin s.d Jumat
        if (!$now->isWeekday()) {
            $this->info("Hari ini bukan hari kerja (Sabtu/Minggu). Command dihentikan.");
            return;
        }

        // Cek apakah sudah jam 15:00
        if ($now->format('H:i:s') < '15:00:00') {
            $this->info("Belum pukul 15:00. Command dihentikan.");
            return;
        }

        $this->info("Memulai proses auto mark absent untuk tanggal: " . $now->format('Y-m-d'));

        // Ambil semua peserta aktif
        $pesertas = User::where('role', 'peserta')->get();
        $count = 0;

        foreach ($pesertas as $peserta) {
            // Cek apakah sudah ada record absensi untuk tanggal ini, apapun statusnya
            $absensiExists = Absensi::where('user_id', $peserta->id)
                ->whereDate('tanggal', $now->format('Y-m-d'))
                ->exists();

            if (!$absensiExists) {
                // Buat record absen baru
                Absensi::create([
                    'user_id' => $peserta->id,
                    'tanggal' => $now->format('Y-m-d'),
                    'jam_masuk' => null,
                    'jam_pulang' => null,
                    'status' => 'tidak_hadir',
                    'keterangan' => 'Otomatis ditandai tidak hadir karena belum melakukan absensi sampai pukul 15:00 WIB.',
                    'source' => 'system',
                ]);
                $count++;
            }
        }

        Log::info("AutoMarkAbsent berjalan: $count peserta ditandai tidak hadir secara otomatis.");
        $this->info("Selesai. $count peserta ditandai tidak hadir.");
    }
}
