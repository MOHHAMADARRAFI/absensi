<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Pengajuan;

class AdminController extends Controller
{
    public function index()
    {
        $totalPeserta = User::where('role', 'peserta')->count();
        $hadirHariIni = Absensi::whereDate('tanggal', now()->format('Y-m-d'))
            ->where('status', 'hadir')->count();
        $izinHariIni = Absensi::whereDate('tanggal', now()->format('Y-m-d'))
            ->where('status', 'izin')->count();
        $sakitHariIni = Absensi::whereDate('tanggal', now()->format('Y-m-d'))
            ->where('status', 'sakit')->count();

        $absensiHariIni = Absensi::with('user')
            ->whereDate('tanggal', now()->format('Y-m-d'))
            ->get();

        return view('admin.dashboard', compact(
            'totalPeserta', 'hadirHariIni', 'izinHariIni', 'sakitHariIni', 'absensiHariIni'
        ));
    }

    public function peserta()
    {
        $peserta = User::where('role', 'peserta')->get();
        return view('admin.peserta', compact('peserta'));
    }

    public function pengajuan()
    {
        $pengajuan = Pengajuan::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.pengajuan', compact('pengajuan'));
    }

    public function laporan()
    {
        return view('admin.laporan');
    }
}
