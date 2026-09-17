<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Kehadiran Pribadi</title>
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
        <h1>Laporan Kehadiran Pribadi</h1>
        <p>Sistem Informasi Absensi PKL Kecamatan Cikampek</p>
    </div>

    <div class="info-section">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; width: 150px; padding: 5px 0;"><strong>Nama Lengkap</strong></td>
                <td style="border: none; padding: 5px 0;">: {{ $data['peserta']->name }}</td>
                <td style="border: none; width: 150px; padding: 5px 0;"><strong>Periode</strong></td>
                <td style="border: none; padding: 5px 0;">: 
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
                <td style="border: none; padding: 5px 0;"><strong>NIS / NIM</strong></td>
                <td style="border: none; padding: 5px 0;">: {{ $data['peserta']->nis_nim }}</td>
                <td style="border: none; padding: 5px 0;"><strong>Dicetak Pada</strong></td>
                <td style="border: none; padding: 5px 0;">: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB</td>
            </tr>
            <tr>
                <td style="border: none; padding: 5px 0;"><strong>Divisi</strong></td>
                <td style="border: none; padding: 5px 0;">: {{ $data['peserta']->divisi ?? '-' }}</td>
                <td style="border: none; padding: 5px 0;"><strong>Total Hari</strong></td>
                <td style="border: none; padding: 5px 0;">: {{ $data['total'] }} Hari Terdata</td>
            </tr>
        </table>
    </div>

    <div style="margin-bottom: 20px; font-size: 14px;">
        <strong>Ringkasan:</strong> Hadir: {{ $data['hadir'] }} | Izin: {{ $data['izin'] }} | Sakit: {{ $data['sakit'] }} | Alpa: {{ $data['alpa'] }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Tanggal</th>
                <th style="width: 15%;">Status</th>
                <th style="width: 15%;">Jam Masuk</th>
                <th style="width: 15%;">Jam Pulang</th>
                <th style="width: 30%;">Keterangan / Laporan Kegiatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data['detail_absensi'] as $i => $r)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($r->tanggal)->locale('id')->isoFormat('DD MMM YYYY') }}</td>
                <td class="text-center" style="text-transform: capitalize;">{{ $r->status }}</td>
                <td class="text-center">{{ $r->jam_masuk ? \Carbon\Carbon::parse($r->jam_masuk)->format('H:i') : '-' }}</td>
                <td class="text-center">{{ $r->jam_pulang ? \Carbon\Carbon::parse($r->jam_pulang)->format('H:i') : '-' }}</td>
                <td>{{ $r->keterangan ?: '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px;">Tidak ada rekap absensi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Cikampek, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}</p>
        <p style="margin-top: 10px;">Peserta PKL,</p>
        <div class="signature-area">
            <p><strong>{{ $data['peserta']->name }}</strong></p>
            <p style="font-size: 12px; color: #555;">{{ $data['peserta']->nis_nim }}</p>
        </div>
    </div>

</body>
</html>
