<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pengaturan;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // ADMIN
        // ============================================================
        User::updateOrCreate(
            ['email' => 'admin@kecamatancikampek.go.id'],
            [
                'name'                => 'Admin Kecamatan Cikampek',
                'email'               => 'admin@kecamatancikampek.go.id',
                'nis_nim'             => 'ADMIN001',
                'role'                => 'admin',
                'password'            => Hash::make('admin123'),
                'status_aktif'        => 'aktif',
            ]
        );

        // ============================================================
        // PESERTA PKL 1
        // ============================================================
        User::updateOrCreate(
            ['nis_nim' => '2024001'],
            [
                'name'                => 'Mohhamad Ar Rafi',
                'email'               => 'rafi@peserta.com',
                'nis_nim'             => '2024001',
                'role'                => 'peserta',
                'password'            => Hash::make('peserta123'),
                'sekolah_universitas' => 'SMK Negeri 1 Karawang',
                'jurusan'             => 'Teknik Komputer & Jaringan',
                'no_hp'               => '081234567890',
                'divisi'              => 'Pelayanan Umum',
                'pembimbing'          => 'Bapak Agus Santoso',
                'tgl_mulai'           => '2026-07-01',
                'tgl_selesai'         => '2026-09-30',
                'status_aktif'        => 'aktif',
            ]
        );

        // ============================================================
        // PESERTA PKL 2
        // ============================================================
        User::updateOrCreate(
            ['nis_nim' => '2024002'],
            [
                'name'                => 'Siti Nurhaliza',
                'email'               => 'siti@peserta.com',
                'nis_nim'             => '2024002',
                'role'                => 'peserta',
                'password'            => Hash::make('peserta123'),
                'sekolah_universitas' => 'SMK Negeri 2 Purwakarta',
                'jurusan'             => 'Administrasi Perkantoran',
                'no_hp'               => '082345678901',
                'divisi'              => 'Administrasi & Tata Usaha',
                'pembimbing'          => 'Ibu Dewi Rahayu',
                'tgl_mulai'           => '2026-07-01',
                'tgl_selesai'         => '2026-09-30',
                'status_aktif'        => 'aktif',
            ]
        );

        // ============================================================
        // PESERTA PKL 3
        // ============================================================
        User::updateOrCreate(
            ['nis_nim' => '2024003'],
            [
                'name'                => 'Budi Santoso',
                'email'               => 'budi@peserta.com',
                'nis_nim'             => '2024003',
                'role'                => 'peserta',
                'password'            => Hash::make('peserta123'),
                'sekolah_universitas' => 'Universitas Singaperbangsa Karawang',
                'jurusan'             => 'Sistem Informasi',
                'no_hp'               => '083456789012',
                'divisi'              => 'Keuangan & Perencanaan',
                'pembimbing'          => 'Bapak Hendra Gunawan',
                'tgl_mulai'           => '2026-08-01',
                'tgl_selesai'         => '2026-10-31',
                'status_aktif'        => 'aktif',
            ]
        );

        // ============================================================
        // PENGATURAN KANTOR (jika belum ada)
        // ============================================================
        Pengaturan::firstOrCreate(
            ['id' => 1],
            [
                'nama_kantor'      => 'Kantor Kecamatan Cikampek',
                'latitude_kantor'  => '-6.4025',
                'longitude_kantor' => '107.4589',
                'radius_meter'     => 500,
            ]
        );

        $this->command->info('✅ Seeder selesai!');
        $this->command->table(
            ['Role', 'Nama', 'Login', 'Password'],
            [
                ['Admin',   'Admin Kecamatan Cikampek', 'admin@kecamatancikampek.go.id', 'admin123'],
                ['Peserta', 'Mohhamad Ar Rafi',          '2024001',                       'peserta123'],
                ['Peserta', 'Siti Nurhaliza',             '2024002',                       'peserta123'],
                ['Peserta', 'Budi Santoso',               '2024003',                       'peserta123'],
            ]
        );
    }
}
