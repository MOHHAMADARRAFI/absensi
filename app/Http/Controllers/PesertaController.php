<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use App\Models\Pengaturan;
use App\Models\Pengajuan;

class PesertaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $absensiHariIni = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', now()->format('Y-m-d'))
            ->first();

        $pengaturan = Pengaturan::first();

        return view('peserta.dashboard', compact('user', 'absensiHariIni', 'pengaturan'));
    }

    public function absenForm(Request $request)
    {
        $type = $request->query('type', 'masuk');
        $pengaturan = Pengaturan::first();
        return view('peserta.absen', compact('type', 'pengaturan'));
    }

    public function absenMasuk(Request $request)
    {
        $user = Auth::user();
        $pengaturan = Pengaturan::first();
        
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'jarak' => 'required|numeric',
            'foto' => 'required',
        ]);

        if ($request->jarak > ($pengaturan->radius_meter ?? 100)) {
            return back()->with('error', 'Anda berada di luar area absensi!');
        }

        $absensi = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', now()->format('Y-m-d'))
            ->first();

        if ($absensi && $absensi->jam_masuk) {
            return back()->with('error', 'Anda sudah melakukan absen masuk hari ini.');
        }

        // Save Base64 Image
        $imageParts = explode(";base64,", $request->foto);
        $imageTypeAux = explode("image/", $imageParts[0]);
        $imageType = $imageTypeAux[1];
        $imageBase64 = base64_decode($imageParts[1]);
        $fileName = 'absen/' . $user->id . '_masuk_' . time() . '.' . $imageType;
        \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $imageBase64);

        if (!$absensi) {
            $absensi = new Absensi();
            $absensi->user_id = $user->id;
            $absensi->tanggal = now()->format('Y-m-d');
        }

        $absensi->jam_masuk = now()->format('H:i:s');
        $absensi->status = 'hadir';
        $absensi->lat_masuk = $request->latitude;
        $absensi->long_masuk = $request->longitude;
        $absensi->jarak_masuk = $request->jarak;
        $absensi->foto_masuk = $fileName;
        $absensi->save();

        return back()->with('success', 'Absen masuk berhasil disimpan!');
    }

    public function absenPulang(Request $request)
    {
        $user = Auth::user();
        $pengaturan = Pengaturan::first();
        
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'jarak' => 'required|numeric',
            'foto' => 'required',
        ]);

        if ($request->jarak > ($pengaturan->radius_meter ?? 100)) {
            return back()->with('error', 'Anda berada di luar area absensi!');
        }

        $absensi = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', now()->format('Y-m-d'))
            ->first();

        if (!$absensi || !$absensi->jam_masuk) {
            return back()->with('error', 'Anda belum melakukan absen masuk.');
        }

        if ($absensi->jam_pulang) {
            return back()->with('error', 'Anda sudah melakukan absen pulang hari ini.');
        }

        // Save Base64 Image
        $imageParts = explode(";base64,", $request->foto);
        $imageTypeAux = explode("image/", $imageParts[0]);
        $imageType = $imageTypeAux[1];
        $imageBase64 = base64_decode($imageParts[1]);
        $fileName = 'absen/' . $user->id . '_pulang_' . time() . '.' . $imageType;
        \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $imageBase64);

        $absensi->jam_pulang = now()->format('H:i:s');
        $absensi->lat_pulang = $request->latitude;
        $absensi->long_pulang = $request->longitude;
        $absensi->jarak_pulang = $request->jarak;
        $absensi->foto_pulang = $fileName;
        $absensi->save();

        return back()->with('success', 'Absen pulang berhasil disimpan!');
    }

    public function izinSakitForm()
    {
        return view('peserta.izin_sakit');
    }

    public function submitIzinSakit(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:izin,sakit',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|max:255',
            'bukti_dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $fileName = null;
        if ($request->hasFile('bukti_dokumen')) {
            $file = $request->file('bukti_dokumen');
            $fileName = 'pengajuan/' . Auth::id() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public', $fileName);
        }

        Pengajuan::create([
            'user_id' => Auth::id(),
            'jenis' => $request->jenis,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan' => $request->alasan,
            'keterangan' => $request->keterangan,
            'bukti_dokumen' => $fileName,
            'status' => 'menunggu',
        ]);

        return redirect()->route('peserta.dashboard')->with('success', 'Pengajuan berhasil dikirim dan menunggu persetujuan.');
    }

    public function riwayat(Request $request)
    {
        $riwayat = Absensi::where('user_id', Auth::id())
            ->orderBy('tanggal', 'desc')
            ->get();
        return view('peserta.riwayat', compact('riwayat'));
    }
}
