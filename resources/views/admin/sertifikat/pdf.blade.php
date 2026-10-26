<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat PKL - {{ $sertifikat->user->name ?? '' }}</title>
    <style>
        @page {
            margin: 25px;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #111;
            line-height: 1.3;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }
        
        /* Container Page 1 & 2 */
        .sertifikat-container, .page-2-container {
            border: 4px solid #14532d; /* Deep Emerald Green */
            padding: 4px;
            position: relative;
            height: 685px; /* Fits A4 Landscape */
            box-sizing: border-box;
            background: #ffffff;
        }
        .sertifikat-container {
            page-break-after: always;
        }
        
        /* Gold Accent Middle Frame */
        .outer-border-accent {
            border: 1.5px solid #d4af37; /* Royal Gold */
            padding: 4px;
            height: 671px;
            box-sizing: border-box;
            position: relative;
        }
        
        /* Inner Green Frame */
        .inner-border {
            border: 2px solid #14532d; /* Deep Emerald Green */
            padding: 15px 30px;
            height: 657px;
            position: relative;
            box-sizing: border-box;
        }

        /* Batik Corner Ornaments */
        .corner-ornament {
            position: absolute;
            width: 80px;
            height: 80px;
            z-index: 2;
            pointer-events: none;
        }
        .corner-tl { top: -2px; left: -2px; }
        .corner-tr { top: -2px; right: -2px; }
        .corner-bl { bottom: -2px; left: -2px; }
        .corner-br { bottom: -2px; right: -2px; }

        .text-center {
            text-align: center;
        }
        .title {
            font-size: 32px;
            color: #926c15; /* Rich Antique Gold */
            letter-spacing: 4px;
            margin: 4px 0 2px 0;
            font-weight: bold;
        }
        .title-divider {
            width: 220px;
            height: 1.5px;
            background: #d4af37;
            margin: 0 auto 6px auto;
        }
        .subtitle {
            font-size: 15px;
            color: #222;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }
        .content {
            font-size: 15px;
            margin-top: 8px;
            text-align: justify;
            color: #222;
        }
        .data-table {
            margin: 8px auto;
            width: 85%;
            font-size: 15px;
        }
        .data-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .data-table .label {
            width: 150px;
            color: #333;
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
            z-index: 3;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .signature-space {
            height: 42px;
        }
        
        /* Table Nilai (Page 2) */
        .table-nilai {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            text-align: center;
        }
        .table-nilai th, .table-nilai td {
            border: 1px solid #14532d;
            padding: 8px;
        }
        .table-nilai th {
            font-weight: bold;
            background-color: #f4fbf7;
            color: #14532d;
        }
        .text-left {
            text-align: left;
        }
    </style>
</head>
<body>

    @php
        $logoPath = public_path('img/logo-karawang.png');
        $logoData = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : '';

        $cornerTlPath = public_path('img/batik-corner-tl.png');
        $cornerTl = file_exists($cornerTlPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($cornerTlPath)) : '';

        $cornerTrPath = public_path('img/batik-corner-tr.png');
        $cornerTr = file_exists($cornerTrPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($cornerTrPath)) : '';

        $cornerBlPath = public_path('img/batik-corner-bl.png');
        $cornerBl = file_exists($cornerBlPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($cornerBlPath)) : '';

        $cornerBrPath = public_path('img/batik-corner-br.png');
        $cornerBr = file_exists($cornerBrPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($cornerBrPath)) : '';
    @endphp

    <!-- HALAMAN 1 -->
    <div class="sertifikat-container">
        <div class="outer-border-accent">
            <div class="inner-border">
                @if($cornerTl)
                    <img src="{{ $cornerTl }}" class="corner-ornament corner-tl" alt="Batik TL">
                    <img src="{{ $cornerTr }}" class="corner-ornament corner-tr" alt="Batik TR">
                    <img src="{{ $cornerBl }}" class="corner-ornament corner-bl" alt="Batik BL">
                    <img src="{{ $cornerBr }}" class="corner-ornament corner-br" alt="Batik BR">
                @endif

                <div class="text-center">
                    @if($logoData)
                        <img src="{{ $logoData }}" width="95" alt="Logo Karawang">
                    @endif
                    <div class="title">SERTIFIKAT</div>
                    <div class="title-divider"></div>
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

                <div class="text-center" style="margin: 10px 0; font-weight: bold; font-size: 16px;">
                    Telah Mengikuti
                </div>

                <div class="content" style="text-align: center; width: 90%; margin: 8px auto 0 auto; line-height: 1.4;">
                    Praktek Kerja Lapangan (PKL) pada Kantor Camat Cikampek, terhitung tanggal 
                    {{ $sertifikat->user && $sertifikat->user->tgl_mulai ? strtoupper(\Carbon\Carbon::parse($sertifikat->user->tgl_mulai)->translatedFormat('d F Y')) : '-' }}
                    sampai dengan 
                    {{ $sertifikat->user && $sertifikat->user->tgl_selesai ? strtoupper(\Carbon\Carbon::parse($sertifikat->user->tgl_selesai)->translatedFormat('d F Y')) : '-' }} 
                    dengan hasil = <strong>{{ $sertifikat->hasil_pkl }}</strong>=
                </div>

                <div class="signature">
                    Cikampek, {{ \Carbon\Carbon::parse($sertifikat->tanggal_sertifikat)->translatedFormat('d F Y') }}<br>
                    CAMAT CIKAMPEK
                    <div class="signature-space"></div>
                    <div class="signature-name">ADI FIRMANSYAH, S.H., M.M.</div>
                    <div>Pembina</div>
                    <div>NIP. 198406042002121001</div>
                </div>
            </div>
        </div>
    </div>

    <!-- HALAMAN 2 -->
    <div class="page-2-container">
        <div class="outer-border-accent">
            <div class="inner-border">
                @if($cornerTl)
                    <img src="{{ $cornerTl }}" class="corner-ornament corner-tl" alt="Batik TL">
                    <img src="{{ $cornerTr }}" class="corner-ornament corner-tr" alt="Batik TR">
                    <img src="{{ $cornerBl }}" class="corner-ornament corner-bl" alt="Batik BL">
                    <img src="{{ $cornerBr }}" class="corner-ornament corner-br" alt="Batik BR">
                @endif

                <div class="text-center" style="font-weight: bold; font-size: 16px; margin-bottom: 15px; margin-top: 10px; color: #14532d; letter-spacing: 1px;">
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
                    <div class="signature-space"></div>
                    <div class="signature-name">ADI FIRMANSYAH, S.H., M.M.</div>
                    <div>Pembina</div>
                    <div>NIP. 198406042002121001</div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
