<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        \App\Models\User::create([
            'name' => 'Admin Kecamatan',
            'email' => 'admin@cikampek.go.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Peserta Dummy
        \App\Models\User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'nis_nim' => '12345678',
            'password' => bcrypt('password'),
            'role' => 'peserta',
            'sekolah_universitas' => 'SMKN 1 Cikampek',
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'no_hp' => '081234567890',
            'divisi' => 'IT Support',
            'pembimbing' => 'Bapak Ahmad',
            'tgl_mulai' => now()->format('Y-m-d'),
            'tgl_selesai' => now()->addMonths(3)->format('Y-m-d'),
            'status_aktif' => 'aktif',
        ]);

        // Pengaturan Default
        \App\Models\Pengaturan::create([
            'nama_kantor' => 'Kantor Kecamatan Cikampek',
            'latitude_kantor' => '-6.4025',
            'longitude_kantor' => '107.4589',
            'radius_meter' => 100,
        ]);
    }
}
