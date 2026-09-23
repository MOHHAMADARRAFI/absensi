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
            ->whereIn('status', ['hadir', 'terlambat'])->count();
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
            'pembimbing' => 'nullable|string|max:255',
            'tgl_mulai' => 'nullable|date',
            'tgl_selesai' => 'nullable|date|after_or_equal:tgl_mulai',
        ]);

        $peserta->update([
            'name' => $request->name,
            'nis_nim' => $request->nis_nim,
            'sekolah_universitas' => $request->sekolah_universitas,
            'jurusan' => $request->jurusan,
            'divisi' => $request->divisi,
            'pembimbing' => $request->pembimbing,
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

    public function kehadiran(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->format('Y-m-d'));
        
        $absensi = Absensi::with('user')
            ->whereDate('tanggal', $tanggal)
            ->orderBy('jam_masuk')
            ->get();
            
        // Jika ada peserta yang belum absen dan ini hari kerja (dan sudah lewat), mungkin tidak muncul di tabel ini
        // Tetapi untuk saat ini kita tampilkan record yang ada di database.
            
        return view('admin.kehadiran', compact('absensi', 'tanggal'));
    }

    public function editKehadiran($id)
    {
        $absen = Absensi::with('user')->findOrFail($id);
        return view('admin.kehadiran_edit', compact('absen'));
    }

    public function updateKehadiran(Request $request, $id)
    {
        $absen = Absensi::findOrFail($id);

        $request->validate([
            'status' => 'required|in:hadir,terlambat,izin,sakit,tidak_hadir,alpa',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i',
            'keterangan' => 'nullable|string',
        ]);

        $absen->status = $request->status;
        
        // Append seconds to time if provided
        $absen->jam_masuk = $request->jam_masuk ? $request->jam_masuk . ':00' : null;
        $absen->jam_pulang = $request->jam_pulang ? $request->jam_pulang . ':00' : null;
        
        $absen->keterangan = $request->keterangan;
        $absen->source = 'admin';
        
        $absen->save();

        return redirect()->route('admin.kehadiran')->with('success', 'Data kehadiran berhasil diperbarui.');
    }

    private function getLaporanData($tipeFilter, $filterValue, $pesertaId = 'semua')
    {
        $queryPeserta = User::where('role', 'peserta')->orderBy('name');
        if ($pesertaId !== 'semua') {
            $queryPeserta->where('id', $pesertaId);
        }
        $peserta = $queryPeserta->get();
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
                'terlambat' => $absensi->where('status', 'terlambat')->count(),
                'izin' => $absensi->where('status', 'izin')->count(),
                'sakit' => $absensi->where('status', 'sakit')->count(),
                'tidak_hadir' => $absensi->where('status', 'tidak_hadir')->count(),
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
        $pesertaId = $request->input('peserta_id', 'semua');

        $data = $this->getLaporanData($tipeFilter, $filterValue, $pesertaId);
        $semuaPeserta = User::where('role', 'peserta')->orderBy('name')->get();
        
        return view('admin.laporan', compact('data', 'tipeFilter', 'filterValue', 'pesertaId', 'semuaPeserta'));
    }

    public function cetakLaporan(Request $request)
    {
        $tipeFilter = $request->input('tipe_filter', 'bulan');
        $filterValue = $request->input('filter_value', now()->format('Y-m'));
        $pesertaId = $request->input('peserta_id', 'semua');

        $data = $this->getLaporanData($tipeFilter, $filterValue, $pesertaId);

        return view('admin.laporan_cetak', compact('data', 'tipeFilter', 'filterValue', 'pesertaId'));
    }

    public function pengaturan()
    {
        $pengaturan = \App\Models\Pengaturan::first();
        return view('admin.pengaturan', compact('pengaturan'));
    }

    public function updatePengaturan(Request $request)
    {
        $request->validate([
            'nama_kantor' => 'required|string|max:255',
            'latitude_kantor' => 'required|numeric',
            'longitude_kantor' => 'required|numeric',
            'radius_meter' => 'required|numeric|min:1',
        ]);

        $pengaturan = \App\Models\Pengaturan::first();
        if (!$pengaturan) {
            $pengaturan = new \App\Models\Pengaturan();
        }

        $pengaturan->nama_kantor = $request->nama_kantor;
        $pengaturan->latitude_kantor = $request->latitude_kantor;
        $pengaturan->longitude_kantor = $request->longitude_kantor;
        $pengaturan->radius_meter = $request->radius_meter;
        $pengaturan->save();

        return redirect()->back()->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
        }

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->new_password)
        ]);

        return redirect()->back()->with('success', 'Password berhasil diubah.');
    }
}