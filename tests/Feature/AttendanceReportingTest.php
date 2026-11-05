<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceReportingTest extends TestCase
{
    use RefreshDatabase;

    public function test_pre_launch_system_alpa_is_excluded_from_attendance_reports(): void
    {
        $user = User::factory()->create();

        Absensi::create([
            'user_id' => $user->id,
            'tanggal' => '2026-09-30',
            'status' => 'alpa',
            'source' => 'system',
        ]);
        Absensi::create([
            'user_id' => $user->id,
            'tanggal' => '2026-09-30',
            'status' => 'hadir',
            'source' => 'peserta',
        ]);
        Absensi::create([
            'user_id' => $user->id,
            'tanggal' => '2026-10-01',
            'status' => 'alpa',
            'source' => 'system',
        ]);

        $this->assertSame(
            ['2026-09-30', '2026-10-01'],
            Absensi::where('user_id', $user->id)
                ->excludePreLaunchAlpa()
                ->orderBy('tanggal')
                ->pluck('tanggal')
                ->map(fn ($tanggal) => Carbon::parse($tanggal)->format('Y-m-d'))
                ->all()
        );
    }

    public function test_alpa_synchronization_does_not_create_records_before_system_start(): void
    {
        Carbon::setTestNow('2026-10-02 16:00:00');

        try {
            $user = User::factory()->create([
                'tgl_mulai' => '2026-09-09',
                'tgl_selesai' => '2026-10-02',
            ]);

            $user->syncAlpaRecords();

            $this->assertSame(
                ['2026-10-01', '2026-10-02'],
                Absensi::where('user_id', $user->id)
                    ->orderBy('tanggal')
                    ->pluck('tanggal')
                    ->map(fn ($tanggal) => Carbon::parse($tanggal)->format('Y-m-d'))
                    ->all()
            );
        } finally {
            Carbon::setTestNow();
        }
    }
}