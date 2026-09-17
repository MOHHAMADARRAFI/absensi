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

        return view('admin.dashboard', compact(
            'totalPeserta', 'hadirHariIni', 'izinHariIni', 'sakitHariIni',
            'absensiHariIni'
        ));
    }

    public function peserta()
    {
        $peserta = User::where('role', 'peserta')->orderBy('name')->get();
        return view('admin.peserta', compact('peserta'));
    }

    public function editPeserta($id)
    {
        $peserta = User::where('role', 'peserta')->findOrFail($id);
        return view('admin.peserta_edit', compact('peserta'));
    }

    public function updatePeserta(Request $request, $id)
    {
        $peserta = User::where('role', 'peserta')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'nis_nim' => 'nullable|string|max:255',
            'sekolah_universitas' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'divisi' => 'nullable|string|max:255',
            'tgl_mulai' => 'nullable|date',
            'tgl_selesai' => 'nullable|date|after_or_equal:tgl_mulai',
        ]);

        $peserta->update([
            'name' => $request->name,
            'nis_nim' => $request->nis_nim,
            'sekolah_universitas' => $request->sekolah_universitas,
            'jurusan' => $request->jurusan,
            'divisi' => $request->divisi,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
        ]);

        return redirect()->route('admin.peserta')->with('success', 'Data peserta berhasil diperbarui.');
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

    private function getLaporanData($tipeFilter, $filterValue)
    {
        $peserta = User::where('role', 'peserta')->orderBy('name')->get();
        $data = [];

        foreach ($peserta as $p) {
            $query = Absensi::where('user_id', $p->id);

            if ($tipeFilter == 'hari') {
                $query->whereDate('tanggal', $filterValue);
            } elseif ($tipeFilter == 'minggu') {
                if (preg_match('/^(\d{4})-W(\d{2})$/', $filterValue, $matches)) {
                    $year = $matches[1];
                    $week = $matches[2];
                    $startOfWeek = \Carbon\Carbon::now()->setISODate($year, $week)->startOfWeek()->format('Y-m-d');
                    $endOfWeek = \Carbon\Carbon::now()->setISODate($year, $week)->endOfWeek()->format('Y-m-d');
                    $query->whereBetween('tanggal', [$startOfWeek, $endOfWeek]);
                }
            } else {
                // default bulan
                $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$filterValue]);
            }

            $absensi = $query->get();

            $data[] = [
                'peserta' => $p,
                'hadir' => $absensi->where('status', 'hadir')->count(),
                'izin' => $absensi->where('status', 'izin')->count(),
                'sakit' => $absensi->where('status', 'sakit')->count(),
                'alpa' => $absensi->where('status', 'alpa')->count(),
                'total' => $absensi->count(),
            ];
        }
        return $data;
    }

    public function laporan(Request $request)
    {
        $tipeFilter = $request->input('tipe_filter', 'bulan');
        $filterValue = $request->input('filter_value', now()->format('Y-m'));

        $data = $this->getLaporanData($tipeFilter, $filterValue);
        
        return view('admin.laporan', compact('data', 'tipeFilter', 'filterValue'));
    }

    public function cetakLaporan(Request $request)
    {
        $tipeFilter = $request->input('tipe_filter', 'bulan');
        $filterValue = $request->input('filter_value', now()->format('Y-m'));

        $data = $this->getLaporanData($tipeFilter, $filterValue);

        return view('admin.laporan_cetak', compact('data', 'tipeFilter', 'filterValue'));
    }
}
