<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 15mm 15mm 15mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #1e293b;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            font-size: 11.5px;
            background: #fff;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
        }
        .header p {
            margin: 3px 0 0;
            font-size: 12px;
            color: #475569;
        }
        .info-section {
            margin-bottom: 12px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            border: none !important;
            margin-bottom: 6px;
        }
        .info-table td {
            border: none !important;
            padding: 2px 0;
            font-size: 11.5px;
            vertical-align: top;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 11px;
            page-break-inside: auto;
            border: none !important;
        }
        table.data-table thead {
            display: table-header-group;
        }
        table.data-table thead th {
            border: 1px solid #334155;
            background-color: #f1f5f9 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            font-weight: bold;
            text-align: center;
            color: #0f172a;
            padding: 6px 6px;
        }
        table.data-table tbody td {
            border: 1px solid #334155;
            padding: 5px 6px;
            text-align: left;
            vertical-align: middle;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .section-title {
            font-size: 11.5px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0f172a;
            margin: 10px 0 5px 0;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 3px;
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

        .sheet {
            box-sizing: border-box;
            width: 100%;
            min-height: 258mm;
            position: relative;
            padding-bottom: 35mm;
            page-break-inside: avoid;
            page-break-after: always;
        }
        .sheet:last-child {
            page-break-after: auto;
        }
        .sheet-footer {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 220px;
            text-align: right;
        }
        .signature-box {
            text-align: right;
            min-width: 220px;
            font-size: 11.5px;
            float: right;
            display: inline-block;
        }
        .signature-box p {
            margin: 2px 0;
            line-height: 1.35;
        }
        .signature-space {
            height: 45px;
        }
        
        tr {
            page-break-inside: avoid;
        }

        @media print {
            body { 
                padding: 0; 
                margin: 0;
            }
            .no-print { display: none !important; }
            .sheet {
                height: 258mm !important;
                max-height: 258mm !important;
                margin: 0 !important;
                padding-bottom: 35mm !important;
                page-break-after: always !important;
                page-break-inside: avoid !important;
            }
            .sheet:last-child {
                page-break-after: auto !important;
            }
            .sheet-footer {
                position: absolute !important;
                bottom: 0 !important;
                right: 0 !important;
            }
            table.data-table thead th {
                background-color: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
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
            @php
                $allRows = collect($item['detail_absensi']);
                $totalRows = $allRows->count();
                $sheets = [];

                if ($totalRows <= 12) {
                    $sheets[] = [
                        'is_first' => true,
                        'rows' => $allRows,
                        'start_idx' => 0,
                    ];
                } else {
                    $firstChunk = $allRows->slice(0, 12);
                    $sheets[] = [
                        'is_first' => true,
                        'rows' => $firstChunk,
                        'start_idx' => 0,
                    ];

                    $remaining = $allRows->slice(12);
                    $offset = 12;
                    foreach ($remaining->chunk(20) as $chunk) {
                        $sheets[] = [
                            'is_first' => false,
                            'rows' => $chunk,
                            'start_idx' => $offset,
                        ];
                        $offset += $chunk->count();
                    }
                }
            @endphp

            @foreach($sheets as $sheetIndex => $sheet)
            <div class="sheet">
                @if($sheet['is_first'])
                    <div class="header">
                        <h1>Laporan Kehadiran & Kegiatan Peserta PKL</h1>
                        <p>Sistem Informasi Absensi PKL Kecamatan Cikampek</p>
                    </div>

                    <div class="info-section">
                        <table class="info-table">
                            <tr>
                                <td style="width: 120px;"><strong>Nama Peserta</strong></td>
                                <td style="width: 250px;">: {{ $item['peserta']->name }}</td>
                                <td style="width: 120px;"><strong>Periode Laporan</strong></td>
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
                                <td><strong>NIS/NIM</strong></td>
                                <td>: {{ $item['peserta']->nis_nim ?? '-' }}</td>
                                <td><strong>Tanggal Dicetak</strong></td>
                                <td>: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB</td>
                            </tr>
                            <tr>
                                <td><strong>Divisi</strong></td>
                                <td colspan="3">: {{ $item['peserta']->divisi ?? '-' }}</td>
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
                @else
                    <div class="section-title" style="margin-top: 0; padding-top: 0;">
                        Lanjutan Rincian Kehadiran & Deskripsi Kegiatan (Lembar {{ $sheetIndex + 1 }})
                    </div>
                @endif

                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 18%;">Hari, Tanggal</th>
                            <th style="width: 11%;">Jam Masuk</th>
                            <th style="width: 11%;">Jam Pulang</th>
                            <th style="width: 11%;">Status</th>
                            <th style="width: 44%;">Deskripsi Kegiatan / Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sheet['rows'] as $rIdx => $r)
                        <tr>
                            <td class="text-center">{{ $sheet['start_idx'] + $loop->iteration }}</td>
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
                            <td style="white-space: pre-wrap; line-height: 1.35;">{{ $r->keterangan ?: '-' }}</td>
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

                <div class="sheet-footer">
                    <div class="signature-box">
                        <p>Cikampek, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                        <p style="margin-top: 3px;">Mentor,</p>
                        <div class="signature-space"></div>
                        <p><strong>_______________________</strong></p>
                    </div>
                </div>
            </div>
            @endforeach
        @empty
            <div class="sheet">
                <div class="header">
                    <h1>Laporan Kehadiran & Kegiatan Peserta PKL</h1>
                    <p>Sistem Informasi Absensi PKL Kecamatan Cikampek</p>
                </div>
                <div class="text-center" style="padding: 50px;">
                    <p>Tidak ada data peserta.</p>
                </div>
            </div>
        @endforelse
    @else
        <div class="sheet">
            <div class="header">
                <h1>Laporan Rekapitulasi Kehadiran Peserta PKL</h1>
                <p>Sistem Informasi Absensi PKL Kecamatan Cikampek</p>
            </div>

            <div class="info-section">
                <table class="info-table">
                    <tr>
                        <td style="width: 120px;"><strong>Periode Laporan</strong></td>
                        <td style="width: 250px;">: 
                            @if($tipeFilter == 'hari')
                                {{ \Carbon\Carbon::parse($filterValue)->locale('id')->isoFormat('D MMMM YYYY') }}
                            @elseif($tipeFilter == 'minggu')
                                Minggu {{ substr($filterValue, -2) }}, Tahun {{ substr($filterValue, 0, 4) }}
                            @else
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $filterValue)->locale('id')->isoFormat('MMMM YYYY') }}
                            @endif
                        </td>
                        <td style="width: 110px;"><strong>Total Peserta</strong></td>
                        <td>: {{ count($data) }} Peserta</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Dicetak</strong></td>
                        <td colspan="3">: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB</td>
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
                            <span style="font-size: 10px; color: #555;">{{ $item['peserta']->nis_nim }}</span>
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

            <div class="sheet-footer">
                <div class="signature-box">
                    <p>Cikampek, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                    <p style="margin-top: 3px;">Mentor,</p>
                    <div class="signature-space"></div>
                    <p><strong>_______________________</strong></p>
                </div>
            </div>
        </div>
    @endif

</body>
</html>
