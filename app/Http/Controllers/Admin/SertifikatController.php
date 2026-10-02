<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Sertifikat;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class SertifikatController extends Controller
{
    private function terbilang($angka) {
        $angka = abs($angka);
        $baca = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $terbilang = "";
        
        if ($angka < 12) {
            $terbilang = " " . $baca[$angka];
        } else if ($angka < 20) {
            $terbilang = $this->terbilang($angka - 10) . " Belas";
        } else if ($angka < 100) {
            $terbilang = $this->terbilang($angka / 10) . " Puluh" . $this->terbilang($angka % 10);
        } else if ($angka < 200) {
            $terbilang = " Seratus" . $this->terbilang($angka - 100);
        } else if ($angka < 1000) {
            $terbilang = $this->terbilang($angka / 100) . " Ratus" . $this->terbilang($angka % 100);
        }
        
        return $terbilang;
    }

    public function index()
    {
        $sertifikats = Sertifikat::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.sertifikat.index', compact('sertifikats'));
    }

    public function create()
    {
        // Hanya peserta yang tidak memiliki sertifikat atau bisa lebih dari 1? Asumsi bisa pilih semua peserta.
        $pesertas = User::where('role', 'peserta')->orderBy('name')->get();
        return view('admin.sertifikat.create', compact('pesertas'));
    }

    public function getPeserta($id)
    {
        $peserta = User::findOrFail($id);
        return response()->json($peserta);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nomor_sertifikat' => 'required|string',
            'hasil_pkl' => 'required|string',
            'nilai_kerajinan' => 'required|numeric|min:0|max:100',
            'nilai_inisiatif' => 'required|numeric|min:0|max:100',
            'nilai_kerjasama' => 'required|numeric|min:0|max:100',
            'nilai_kedisiplinan' => 'required|numeric|min:0|max:100',
            'nilai_prestasi_kerja' => 'required|numeric|min:0|max:100',
            'tanggal_sertifikat' => 'required|date',
        ]);

        $jumlah_nilai = $request->nilai_kerajinan + $request->nilai_inisiatif + $request->nilai_kerjasama + $request->nilai_kedisiplinan + $request->nilai_prestasi_kerja;
        $nilai_rata_rata = $jumlah_nilai / 5;

        $sertifikat = Sertifikat::create([
            'user_id' => $request->user_id,
            'nomor_sertifikat' => $request->nomor_sertifikat,
            'hasil_pkl' => $request->hasil_pkl,
            'nilai_kerajinan' => $request->nilai_kerajinan,
            'nilai_inisiatif' => $request->nilai_inisiatif,
            'nilai_kerjasama' => $request->nilai_kerjasama,
            'nilai_kedisiplinan' => $request->nilai_kedisiplinan,
            'nilai_prestasi_kerja' => $request->nilai_prestasi_kerja,
            'jumlah_nilai' => $jumlah_nilai,
            'nilai_rata_rata' => $nilai_rata_rata,
            'tanggal_sertifikat' => $request->tanggal_sertifikat,
        ]);

        // Generate PDF
        $terbilang = function($angka) {
            return trim($this->terbilang($angka));
        };
        $pdf = Pdf::loadView('admin.sertifikat.pdf', compact('sertifikat', 'terbilang'));
        $pdf->setPaper('a4', 'landscape');
        
        // Simpan file PDF (opsional, jika ingin disimpan)
        // $path = 'sertifikat/sertifikat_' . $sertifikat->id . '.pdf';
        // Storage::put('public/' . $path, $pdf->output());
        // $sertifikat->update(['file_pdf' => $path]);

        return redirect()->route('admin.sertifikat.index')->with('success', 'Sertifikat berhasil dibuat.');
    }

    public function downloadPdf($id)
    {
        $sertifikat = Sertifikat::with('user')->findOrFail($id);
        $terbilang = function($angka) {
            return trim($this->terbilang($angka));
        };
        $pdf = Pdf::loadView('admin.sertifikat.pdf', compact('sertifikat', 'terbilang'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download('Sertifikat_PKL_' . $sertifikat->user->name . '.pdf');
    }

    public function showPdf($id)
    {
        $sertifikat = Sertifikat::with('user')->findOrFail($id);
        $terbilang = function($angka) {
            return trim($this->terbilang($angka));
        };
        $pdf = Pdf::loadView('admin.sertifikat.pdf', compact('sertifikat', 'terbilang'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('Sertifikat_PKL_' . $sertifikat->user->name . '.pdf');
    }
}
