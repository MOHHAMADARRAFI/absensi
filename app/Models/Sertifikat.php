<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nomor_sertifikat',
        'hasil_pkl',
        'nilai_kerajinan',
        'nilai_inisiatif',
        'nilai_kerjasama',
        'nilai_kedisiplinan',
        'nilai_prestasi_kerja',
        'jumlah_nilai',
        'nilai_rata_rata',
        'tanggal_sertifikat',
        'file_pdf'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
