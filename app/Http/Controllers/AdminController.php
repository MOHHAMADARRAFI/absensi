<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Log;

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

        // Jumlah peserta yang sudah terdaftar wajah
        $terdaftarWajah = User::where('role', 'peserta')
            ->whereNotNull('face_descriptor')
            ->count();

        $belumTerdaftarWajah = User::where('role', 'peserta')
            ->whereNull('face_descriptor')
            ->count();

        return view('admin.dashboard', compact(
            'totalPeserta', 'hadirHariIni', 'izinHariIni', 'sakitHariIni',
            'absensiHariIni', 'terdaftarWajah', 'belumTerdaftarWajah'
        ));
    }

    public function peserta()
    {
        $peserta = User::where('role', 'peserta')->orderBy('name')->get();
        return view('admin.peserta', compact('peserta'));
    }

    public function pengajuan()
    {
        $pengajuan = Pengajuan::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.pengajuan', compact('pengajuan'));
    }

    public function updatePengajuan(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
        ]);

        $pengajuan = Pengajuan::findOrFail($id);
        $pengajuan->status = $request->status;
        $pengajuan->save();

        // Jika disetujui, buat record absensi untuk tanggal yang diajukan
        if ($request->status === 'disetujui') {
            $mulai = \Carbon\Carbon::parse($pengajuan->tanggal_mulai);
            $selesai = \Carbon\Carbon::parse($pengajuan->tanggal_selesai);

            while ($mulai->lte($selesai)) {
                $existing = Absensi::where('user_id', $pengajuan->user_id)
                    ->whereDate('tanggal', $mulai->format('Y-m-d'))
                    ->first();

                if (!$existing) {
                    Absensi::create([
                        'user_id' => $pengajuan->user_id,
                        'tanggal' => $mulai->format('Y-m-d'),
                        'status' => $pengajuan->jenis,
                        'keterangan' => $pengajuan->alasan,
                    ]);
                }
                $mulai->addDay();
            }
        }

        return back()->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    public function laporan()
    {
        $peserta = User::where('role', 'peserta')->orderBy('name')->get();
        $bulan = request('bulan', now()->format('Y-m'));

        $data = [];
        foreach ($peserta as $p) {
            $absensi = Absensi::where('user_id', $p->id)
                ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                ->get();

            $data[] = [
                'peserta' => $p,
                'hadir' => $absensi->where('status', 'hadir')->count(),
                'izin' => $absensi->where('status', 'izin')->count(),
                'sakit' => $absensi->where('status', 'sakit')->count(),
                'alpa' => $absensi->where('status', 'alpa')->count(),
                'total' => $absensi->count(),
            ];
        }

        return view('admin.laporan', compact('data', 'bulan', 'peserta'));
    }

    /**
     * Tampilkan halaman registrasi wajah untuk peserta tertentu.
     * Hanya bisa diakses oleh Admin.
     */
    public function registrasiWajahForm($id)
    {
        $peserta = User::where('role', 'peserta')->findOrFail($id);
        return view('admin.registrasi_wajah', compact('peserta'));
    }

    /**
     * Simpan face descriptor dari frontend ke database.
     * Descriptor diterima sebagai JSON array 128 nilai.
     * Hanya Admin yang bisa menyimpan descriptor untuk peserta.
     */
    public function simpanWajah(Request $request, $id)
    {
        $request->validate([
            'face_descriptor' => 'required|string',
        ]);

        $peserta = User::where('role', 'peserta')->findOrFail($id);

        // Validasi format descriptor — harus berupa JSON array 128 angka
        $descriptor = json_decode($request->face_descriptor);
        if (!is_array($descriptor) || count($descriptor) !== 128) {
            return response()->json([
                'success' => false,
                'message' => 'Data wajah tidak valid. Pastikan wajah terdeteksi dengan baik.',
            ], 422);
        }

        // Validasi semua elemen adalah angka
        foreach ($descriptor as $val) {
            if (!is_numeric($val)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format data wajah tidak valid.',
                ], 422);
            }
        }

        $peserta->face_descriptor = json_encode($descriptor);
        $peserta->save();

        Log::info("Face descriptor berhasil disimpan untuk peserta: {$peserta->id} ({$peserta->name})");

        return response()->json([
            'success' => true,
            'message' => "Wajah {$peserta->name} berhasil didaftarkan.",
        ]);
    }

    /**
     * Hapus face descriptor peserta.
     */
    public function hapusWajah($id)
    {
        $peserta = User::where('role', 'peserta')->findOrFail($id);
        $peserta->face_descriptor = null;
        $peserta->save();

        Log::info("Face descriptor dihapus untuk peserta: {$peserta->id} ({$peserta->name})");

        return back()->with('success', "Data wajah {$peserta->name} berhasil dihapus.");
    }
}
