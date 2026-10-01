<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nis_nim',
        'role',
        'sekolah_universitas',
        'jenis_kelamin',
        'tempat_tgl_lahir',
        'jurusan',
        'no_hp',
        'divisi',
        'pembimbing',
        'tgl_mulai',
        'tgl_selesai',
        'status_aktif',
        'foto_profil',
        'face_descriptor',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Memeriksa dan membuat record 'alpa' otomatis untuk hari-hari yang terlewat.
     */
    public function syncAlpaRecords()
    {
        if (!$this->tgl_mulai) return;

        $tglMulai = \Carbon\Carbon::parse($this->tgl_mulai)->startOfDay();
        $hariIni = \Carbon\Carbon::now('Asia/Jakarta');
        
        $tglSelesai = $this->tgl_selesai ? \Carbon\Carbon::parse($this->tgl_selesai)->endOfDay() : null;
        $endLoop = $hariIni->copy();
        
        if ($tglSelesai && $hariIni->gt($tglSelesai)) {
            $endLoop = $tglSelesai->copy();
        }

        $currentDate = $tglMulai->copy();
        
        while ($currentDate->lte($endLoop)) {
            // Hanya periksa hari kerja (Senin - Jumat)
            if ($currentDate->isWeekday()) {
                $isToday = $currentDate->isSameDay($hariIni);
                $isPast15 = $hariIni->format('H:i:s') >= '15:00:00';

                // Jika hari ini tapi belum jam 15:00, lewati
                if ($isToday && !$isPast15) {
                    $currentDate->addDay();
                    continue;
                }
                
                $dateString = $currentDate->format('Y-m-d');
                
                // Cek apakah sudah ada record absensi di tanggal ini
                $exists = \App\Models\Absensi::where('user_id', $this->id)
                    ->whereDate('tanggal', $dateString)
                    ->exists();

                if (!$exists) {
                    \App\Models\Absensi::create([
                        'user_id' => $this->id,
                        'tanggal' => $dateString,
                        'jam_masuk' => null,
                        'jam_pulang' => null,
                        'status' => 'alpa',
                        'keterangan' => 'Otomatis ditandai alpa karena tidak melakukan absensi (Sinkronisasi Sistem).',
                        'source' => 'system',
                    ]);
                }
            }
            $currentDate->addDay();
        }
    }
}
