<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        @page {
            size: auto;
            margin: 0;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #1e293b;
            line-height: 1.5;
            margin: 0;
            padding: 15mm 12mm;
            font-size: 13px;
            background: #fff;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
        }
        .header p {
            margin: 4px 0 0;
            font-size: 13px;
            color: #475569;
        }
        .info-section {
            margin-bottom: 14px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            border: none !important;
            margin-bottom: 8px;
        }
        .info-table td {
            border: none !important;
            padding: 3px 0;
            font-size: 13px;
            vertical-align: top;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 12px;
        }
        table.data-table, table.data-table th, table.data-table td {
            border: 1px solid #334155;
        }
        table.data-table th, table.data-table td {
            padding: 7px 8px;
            text-align: left;
            vertical-align: top;
        }
        table.data-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: center;
            color: #0f172a;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .section-title {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            margin: 14px 0 8px 0;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
        }

        .status-badge {
            font-weight: 600;
            display: inline-block;
        }
        .status-hadir { color: #047857; }
        .status-terlambat { color: #b45309; }
        .status-izin { color: #0369a1; }
        .status-sakit { color: #b91c1c; }
        .status-alpa { color: #dc2626; }
        
        .footer {
            margin-top: 25px;
            width: 100%;
            display: flex;
            justify-content: flex-end;
            page-break-inside: avoid;
        }
        .signature-box {
            text-align: right;
            min-width: 220px;
        }
        .signature-box p { margin: 2px 0; }
        .signature-space {
            margin-top: 55px;
        }
        
        .page-break {
            page-break-after: always;
            clear: both;
        }
        .page-break:last-child {
            page-break-after: auto;
        }
        tr {
            page-break-inside: avoid;
        }
        @media print {
            body { 
                padding: 15mm 12mm; 
                margin: 0;
            }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; text-align: center; display: flex; justify-content: center; gap: 10px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 15px; cursor: pointer; background: #059669; color: white; border: none; border-radius: 6px; font-weight: 600;">
            Cetak Dokumen
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 15px; cursor: pointer; background: #64748b; color: white; border: none; border-radius: 6px; font-weight: 600;">
            Tutup
        </button>
    </div>

    @if((isset($pesertaId) && $pesertaId !== 'semua') || request('mode') === 'semua_kegiatan')
        @forelse($data as $i => $item)
        <div class="page-break">
            <div class="header">
                <h1>Laporan Kehadiran & Kegiatan Peserta PKL</h1>
                <p>Sistem Informasi Absensi PKL Kecamatan Cikampek</p>
            </div>

            <div class="info-section">
                <table class="info-table">
                    <tr>
                        <td style="width: 160px;"><strong>Nama Peserta</strong></td>
                        <td>: {{ $item['peserta']->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>NIS/NIM</strong></td>
                        <td>: {{ $item['peserta']->nis_nim ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Divisi</strong></td>
                        <td>: {{ $item['peserta']->divisi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Periode Laporan</strong></td>
                        <td>: 
                            @if($tipeFilter == 'hari')
                                {{ \Carbon\Carbon::parse($filterValue)->locale('id')->isoFormat('D MMMM YYYY') }}
                            @elseif($tipeFilter == 'minggu')
                                Minggu {{ substr($filterValue, -2) }}, Tahun {{ substr($filterValue, 0, 4) }}
                            @else
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $filterValue)->locale('id')->isoFormat('MMMM YYYY') }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Dicetak</strong></td>
                        <td>: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB</td>
                    </tr>
                </table>
            </div>

            <div class="section-title">Ringkasan Kehadiran</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 16%;">Hadir</th>
                        <th style="width: 16%;">Terlambat</th>
                        <th style="width: 16%;">Izin</th>
                        <th style="width: 16%;">Sakit</th>
                        <th style="width: 16%;">Alpa</th>
                        <th style="width: 20%;">% Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $jumlahHadir = $item['hadir'] + $item['terlambat'];
                        $persen = $item['total'] > 0
                            ? round(($jumlahHadir / $item['total']) * 100)
                            : 0;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $item['hadir'] }}</td>
                        <td class="text-center">{{ $item['terlambat'] }}</td>
                        <td class="text-center">{{ $item['izin'] }}</td>
                        <td class="text-center">{{ $item['sakit'] }}</td>
                        <td class="text-center">{{ $item['alpa'] + $item['tidak_hadir'] }}</td>
                        <td class="text-center font-bold">{{ $persen }}%</td>
                    </tr>
                </tbody>
            </table>

            <div class="section-title">Rincian Kehadiran & Deskripsi Kegiatan</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 20%;">Hari, Tanggal</th>
                        <th style="width: 11%;">Jam Masuk</th>
                        <th style="width: 11%;">Jam Pulang</th>
                        <th style="width: 11%;">Status</th>
                        <th style="width: 42%;">Deskripsi Kegiatan / Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($item['detail_absensi'] as $idx => $r)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($r->tanggal)->locale('id')->isoFormat('dddd, D MMM Y') }}</td>
                        <td class="text-center">{{ $r->jam_masuk ? date('H:i', strtotime($r->jam_masuk)) . ' WIB' : '-' }}</td>
                        <td class="text-center">{{ $r->jam_pulang ? date('H:i', strtotime($r->jam_pulang)) . ' WIB' : '-' }}</td>
                        <td class="text-center">
                            @if($r->status == 'hadir')
                                <span class="status-badge status-hadir">Hadir</span>
                            @elseif($r->status == 'terlambat')
                                <span class="status-badge status-terlambat">Terlambat</span>
                            @elseif($r->status == 'izin')
                                <span class="status-badge status-izin">Izin</span>
                            @elseif($r->status == 'sakit')
                                <span class="status-badge status-sakit">Sakit</span>
                            @elseif($r->status == 'alpa' || $r->status == 'tidak_hadir')
                                <span class="status-badge status-alpa">Alpa</span>
                            @else
                                <span class="status-badge">{{ ucfirst($r->status) }}</span>
                            @endif
                        </td>
                        <td style="white-space: pre-wrap; line-height: 1.4;">{{ $r->keterangan ?: '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 15px; color: #64748b;">
                            Tidak ada riwayat kehadiran dan catatan kegiatan pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="footer">
                <div class="signature-box">
                    <p>Cikampek, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                    <p style="margin-top: 5px;">Mentor,</p>
                    <div class="signature-space">
                        <p><strong>_______________________</strong></p>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="header">
            <h1>Laporan Kehadiran & Kegiatan Peserta PKL</h1>
            <p>Sistem Informasi Absensi PKL Kecamatan Cikampek</p>
        </div>
        <div class="text-center" style="padding: 50px;">
            <p>Tidak ada data peserta.</p>
        </div>
        @endforelse
    @else
        <div class="header">
            <h1>Laporan Rekapitulasi Kehadiran Peserta PKL</h1>
            <p>Sistem Informasi Absensi PKL Kecamatan Cikampek</p>
        </div>

        <div class="info-section">
            <table class="info-table">
                <tr>
                    <td style="width: 150px;"><strong>Periode Laporan</strong></td>
                    <td>: 
                        @if($tipeFilter == 'hari')
                            {{ \Carbon\Carbon::parse($filterValue)->locale('id')->isoFormat('D MMMM YYYY') }}
                        @elseif($tipeFilter == 'minggu')
                            Minggu {{ substr($filterValue, -2) }}, Tahun {{ substr($filterValue, 0, 4) }}
                        @else
                            {{ \Carbon\Carbon::createFromFormat('Y-m', $filterValue)->locale('id')->isoFormat('MMMM YYYY') }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>Tanggal Dicetak</strong></td>
                    <td>: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB</td>
                </tr>
                <tr>
                    <td><strong>Total Peserta</strong></td>
                    <td>: {{ count($data) }} Peserta</td>
                </tr>
            </table>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Nama Peserta</th>
                    <th style="width: 16%;">Divisi</th>
                    <th style="width: 9%;">Hadir</th>
                    <th style="width: 9%;">Terlambat</th>
                    <th style="width: 9%;">Izin</th>
                    <th style="width: 9%;">Sakit</th>
                    <th style="width: 9%;">Alpa</th>
                    <th style="width: 9%;">% Hadir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $i => $item)
                @php
                    $jumlahHadir = $item['hadir'] + $item['terlambat'];
                    $persen = $item['total'] > 0
                        ? round(($jumlahHadir / $item['total']) * 100)
                        : 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>
                        <strong>{{ $item['peserta']->name }}</strong><br>
                        <span style="font-size: 11px; color: #555;">{{ $item['peserta']->nis_nim }}</span>
                    </td>
                    <td>{{ $item['peserta']->divisi ?? '-' }}</td>
                    <td class="text-center">{{ $item['hadir'] }}</td>
                    <td class="text-center">{{ $item['terlambat'] }}</td>
                    <td class="text-center">{{ $item['izin'] }}</td>
                    <td class="text-center">{{ $item['sakit'] }}</td>
                    <td class="text-center">{{ $item['alpa'] + $item['tidak_hadir'] }}</td>
                    <td class="text-center font-bold">{{ $persen }}%</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 20px;">Tidak ada data pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <div class="signature-box">
                <p>Cikampek, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                <p style="margin-top: 5px;">Mentor,</p>
                <div class="signature-space">
                    <p><strong>_______________________</strong></p>
                </div>
            </div>
        </div>
    @endif

</body>
</html>
