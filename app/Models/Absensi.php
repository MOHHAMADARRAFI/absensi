<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Absensi extends Model
{
    use HasFactory;

    public const SYSTEM_START_DATE = '2026-10-01';

    protected $fillable = [
        'user_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status',
        'keterangan',
        'foto_masuk',
        'foto_pulang',
        'lat_masuk',
        'long_masuk',
        'lat_pulang',
        'long_pulang',
        'jarak_masuk',
        'jarak_pulang',
        'source',
    ];

    public function scopeExcludePreLaunchAlpa(Builder $query): Builder
    {
        return $query->where(function (Builder $query) {
            $query->whereDate('tanggal', '>=', self::SYSTEM_START_DATE)
                ->orWhere(function (Builder $query) {
                    $query->where(function (Builder $query) {
                        $query->whereNull('source')
                            ->orWhere('source', '!=', 'system');
                    })->orWhere('status', '!=', 'alpa');
                });
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
