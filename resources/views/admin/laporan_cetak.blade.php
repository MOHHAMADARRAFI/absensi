<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Kehadiran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #555;
        }
        .info-section {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 14px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
            text-align: center;
        }
        .text-center { text-align: center; }
        .footer {
            margin-top: 40px;
            text-align: right;
        }
        .footer p { margin: 0; }
        .signature-area {
            margin-top: 60px;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer; background: #059669; color: white; border: none; border-radius: 5px;">Cetak Dokumen</button>
    </div>

    <div class="header">
        <h1>Laporan Kehadiran Peserta PKL</h1>
        <p>Sistem Informasi Absensi PKL Kecamatan Cikampek</p>
    </div>

    <div class="info-section">
        <table>
            <tr>
                <td style="border: none; width: 150px;"><strong>Periode Laporan</strong></td>
                <td style="border: none;">: 
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
                <td style="border: none;"><strong>Tanggal Dicetak</strong></td>
                <td style="border: none;">: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Nama Peserta</th>
                <th style="width: 15%;">Divisi</th>
                <th style="width: 10%;">Hadir</th>
                <th style="width: 10%;">Izin</th>
                <th style="width: 10%;">Sakit</th>
                <th style="width: 10%;">Alpa</th>
                <th style="width: 15%;">% Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $item)
            @php
                $persen = $item['total'] > 0
                    ? round(($item['hadir'] / $item['total']) * 100)
                    : 0;
            @endphp
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>
                    <strong>{{ $item['peserta']->name }}</strong><br>
                    <span style="font-size: 12px; color: #555;">{{ $item['peserta']->nis_nim }}</span>
                </td>
                <td>{{ $item['peserta']->divisi ?? '-' }}</td>
                <td class="text-center">{{ $item['hadir'] }}</td>
                <td class="text-center">{{ $item['izin'] }}</td>
                <td class="text-center">{{ $item['sakit'] }}</td>
                <td class="text-center">{{ $item['alpa'] }}</td>
                <td class="text-center">{{ $persen }}%</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="padding: 20px;">Tidak ada data pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Cikampek, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}</p>
        <p style="margin-top: 10px;">Admin SIAP PKL,</p>
        <div class="signature-area">
            <p>_______________________</p>
        </div>
    </div>

</body>
</html>
