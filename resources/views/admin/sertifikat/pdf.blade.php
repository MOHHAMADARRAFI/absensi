<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat PKL - {{ $sertifikat->user->name ?? '' }}</title>
    <style>
        @page {
            margin: 30px;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        .sertifikat-container {
            border: 10px solid #a3c4a8; /* Greenish border */
            padding: 10px;
            position: relative;
            height: 680px; /* Fixed height strictly for A4 Landscape */
            page-break-after: always;
            box-sizing: border-box;
        }
        .inner-border {
            border: 2px solid #a3c4a8;
            padding: 20px;
            height: 640px;
            position: relative;
            box-sizing: border-box;
        }
        .text-center {
            text-align: center;
        }
        .title {
            font-size: 30px;
            color: #947a19; /* Gold */
            letter-spacing: 2px;
            margin: 5px 0;
            font-weight: bold;
        }
        .subtitle {
            font-size: 15px;
            margin-bottom: 15px;
        }
        .content {
            font-size: 15px;
            margin-top: 15px;
            text-align: justify;
        }
        .data-table {
            margin: 10px auto;
            width: 85%;
            font-size: 15px;
        }
        .data-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .data-table .label {
            width: 150px;
        }
        .data-table .colon {
            width: 20px;
            text-align: center;
        }
        .signature {
            position: absolute;
            bottom: 20px;
            right: 40px;
            text-align: center;
            width: 300px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        /* Page 2 Styles */
        .page-2-container {
            border: 10px solid #a3c4a8;
            padding: 10px;
            position: relative;
            height: 680px;
            box-sizing: border-box;
        }
        .inner-border-2 {
            border: 2px solid #a3c4a8;
            padding: 20px;
            height: 640px;
            position: relative;
            box-sizing: border-box;
        }
        .table-nilai {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            text-align: center;
        }
        .table-nilai th, .table-nilai td {
            border: 1px solid #000;
            padding: 8px;
        }
        .table-nilai th {
            font-weight: bold;
        }
        .text-left {
            text-align: left;
        }
    </style>
</head>
<body>

    <!-- HALAMAN 1 -->
    <div class="sertifikat-container">
        <div class="inner-border">
            <div class="text-center">
                @php
                    $logoPath = public_path('img/logo-karawang.png');
                    $logoData = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : '';
                @endphp
                @if($logoData)
                    <img src="{{ $logoData }}" width="70" alt="Logo Karawang">
                @endif
                <div class="title">SERTIFIKAT</div>
                <div class="subtitle">Nomor : {{ $sertifikat->nomor_sertifikat }}</div>
            </div>

            <div class="content">
                Berdasarkan Peraturan Bupati Karawang Nomor 23 Tahun 2014 Tentang Pelimpahan sebagian urusan Pemerintahan dari Bupati Karawang Kepada Perangkat Daerah Kabupaten Karawang, dengan ini menyatakan bahwa :
            </div>

            <table class="data-table">
                <tr>
                    <td class="label">Nama</td>
                    <td class="colon">:</td>
                    <td><strong>{{ strtoupper($sertifikat->user->name ?? '-') }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Tempat/Tgl. Lahir</td>
                    <td class="colon">:</td>
                    <td>{{ strtoupper($sertifikat->user->tempat_tgl_lahir ?? '-') }}</td>
                </tr>
                <tr>
                    <td class="label">Jenis Kelamin</td>
                    <td class="colon">:</td>
                    <td>{{ strtoupper($sertifikat->user->jenis_kelamin ?? '-') }}</td>
                </tr>
                <tr>
                    <td class="label">Nama Sekolah</td>
                    <td class="colon">:</td>
                    <td>{{ strtoupper($sertifikat->user->sekolah_universitas ?? '-') }}</td>
                </tr>
                <tr>
                    <td class="label">Jurusan</td>
                    <td class="colon">:</td>
                    <td>{{ strtoupper($sertifikat->user->jurusan ?? '-') }}</td>
                </tr>
            </table>

            <div class="text-center" style="margin: 15px 0; font-weight: bold; font-size: 16px;">
                Telah Mengikuti
            </div>

            <div class="content" style="text-align: center; width: 95%; margin: 0 auto;">
                Praktek Kerja Lapangan (PKL) pada Kantor Camat Cikampek, terhitung tanggal 
                {{ $sertifikat->user && $sertifikat->user->tgl_mulai ? strtoupper(\Carbon\Carbon::parse($sertifikat->user->tgl_mulai)->translatedFormat('d F Y')) : '-' }}
                sampai dengan 
                {{ $sertifikat->user && $sertifikat->user->tgl_selesai ? strtoupper(\Carbon\Carbon::parse($sertifikat->user->tgl_selesai)->translatedFormat('d F Y')) : '-' }} 
                dengan hasil = <strong>{{ $sertifikat->hasil_pkl }}</strong>=
            </div>

            <div class="signature">
                Cikampek, {{ \Carbon\Carbon::parse($sertifikat->tanggal_sertifikat)->translatedFormat('d F Y') }}<br>
                CAMAT CIKAMPEK
                <br><br><br><br>
                <div class="signature-name">ADI FIRMANSYAH, S.H., M.M.</div>
                <div>Pembina</div>
                <div>NIP. 198406042002121001</div>
            </div>
        </div>
    </div>

    <!-- HALAMAN 2 -->
    <div class="page-2-container">
        <div class="inner-border-2">
            <div class="text-center" style="font-weight: bold; font-size: 16px; margin-bottom: 15px; margin-top: 10px;">
                MATA LATIHAN DAN PENILAIAN
            </div>

            <table class="table-nilai">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 5%;">No</th>
                        <th rowspan="2" style="width: 45%;">MATA LATIHAN DAN BIMBINGAN</th>
                        <th colspan="2" style="width: 50%;">NILAI</th>
                    </tr>
                    <tr>
                        <th>ANGKA</th>
                        <th>HURUF</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td class="text-left">Kerajinan</td>
                        <td>{{ $sertifikat->nilai_kerajinan }}</td>
                        <td><i>{{ $terbilang($sertifikat->nilai_kerajinan) }}</i></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td class="text-left">Inisiatif</td>
                        <td>{{ $sertifikat->nilai_inisiatif }}</td>
                        <td><i>{{ $terbilang($sertifikat->nilai_inisiatif) }}</i></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td class="text-left">Kerjasama</td>
                        <td>{{ $sertifikat->nilai_kerjasama }}</td>
                        <td><i>{{ $terbilang($sertifikat->nilai_kerjasama) }}</i></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td class="text-left">Kedisiplinan</td>
                        <td>{{ $sertifikat->nilai_kedisiplinan }}</td>
                        <td><i>{{ $terbilang($sertifikat->nilai_kedisiplinan) }}</i></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td class="text-left">Prestasi Kerja</td>
                        <td>{{ $sertifikat->nilai_prestasi_kerja }}</td>
                        <td><i>{{ $terbilang($sertifikat->nilai_prestasi_kerja) }}</i></td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td colspan="2">JUMLAH</td>
                        <td>{{ $sertifikat->jumlah_nilai }}</td>
                        <td><i>{{ $terbilang($sertifikat->jumlah_nilai) }}</i></td>
                    </tr>
                    <tr style="font-weight: bold;">
                        <td colspan="2">NILAI RATA-RATA</td>
                        <td>{{ number_format($sertifikat->nilai_rata_rata, 2, ',', '.') }}</td>
                        <td><i>{{ $terbilang($sertifikat->nilai_rata_rata) }}</i></td>
                    </tr>
                </tbody>
            </table>

            <div class="signature">
                Cikampek, {{ \Carbon\Carbon::parse($sertifikat->tanggal_sertifikat)->translatedFormat('d F Y') }}<br>
                CAMAT CIKAMPEK
                <br><br><br><br>
                <div class="signature-name">ADI FIRMANSYAH, S.H., M.M.</div>
                <div>Pembina</div>
                <div>NIP. 198406042002121001</div>
            </div>
        </div>
    </div>

</body>
</html>
