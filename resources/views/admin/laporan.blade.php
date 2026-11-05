@extends('layouts.app')

@section('title', 'Laporan Presensi - Admin SIAP PKL')

@section('content')
<div class="student-dashboard">
    {{-- Sidebar --}}
    @include('admin.partials.sidebar')

    {{-- Main Content --}}
    <div class="student-main">
        <div class="student-topbar">
            <div style="display: flex; align-items: center;">
                <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                    <i class="ph ph-list"></i>
                </button>
                <div>
                    <div class="student-eyebrow">Manajemen</div>
                    <h1 style="font-size: 1.25rem; font-weight: 800; color: #1E293B; letter-spacing: -0.02em;">Laporan Presensi</h1>
                </div>
            </div>
            <div class="student-date">
                <i class="ph ph-calendar-blank"></i>
                <span class="font-semibold">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
            </div>
        </div>

        <div class="student-content">

            {{-- Filter Laporan --}}
            <div class="card mb-4" style="padding: 1.25rem 1.5rem;">
                <form method="GET" action="{{ route('admin.laporan') }}" onsubmit="updateActualValue()"
                    style="display: flex; align-items: flex-end; gap: 1rem; flex-wrap: wrap;">
                    
                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.85rem; color: #475569; margin-bottom: 0.5rem;">Peserta</label>
                        <select name="peserta_id" id="pesertaId" class="form-control" style="width: auto; min-width: 180px; padding: 0.5rem 0.875rem;">
                            <option value="semua" {{ $pesertaId == 'semua' ? 'selected' : '' }}>Semua Peserta</option>
                            @foreach($semuaPeserta as $p)
                                <option value="{{ $p->id }}" {{ $pesertaId == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.85rem; color: #475569; margin-bottom: 0.5rem;">Tipe Filter</label>
                        <select name="tipe_filter" id="tipeFilter" class="form-control" style="width: auto; min-width: 150px; padding: 0.5rem 0.875rem;" onchange="changeFilterInput()">
                            <option value="hari" {{ $tipeFilter == 'hari' ? 'selected' : '' }}>Per Hari</option>
                            <option value="minggu" {{ $tipeFilter == 'minggu' ? 'selected' : '' }}>Per Minggu</option>
                            <option value="bulan" {{ $tipeFilter == 'bulan' ? 'selected' : '' }}>Per Bulan</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.85rem; color: #475569; margin-bottom: 0.5rem;">Periode</label>
                        <input type="date" name="filter_value_hari" id="filterHari" onchange="updateActualValue()" value="{{ $tipeFilter == 'hari' ? $filterValue : now()->format('Y-m-d') }}" class="form-control" style="display: {{ $tipeFilter == 'hari' ? 'block' : 'none' }}; width: auto; min-width: 180px; padding: 0.5rem 0.875rem;">
                        <input type="week" name="filter_value_minggu" id="filterMinggu" onchange="updateActualValue()" value="{{ $tipeFilter == 'minggu' ? $filterValue : now()->format('Y-\WW') }}" class="form-control" style="display: {{ $tipeFilter == 'minggu' ? 'block' : 'none' }}; width: auto; min-width: 180px; padding: 0.5rem 0.875rem;">
                        <input type="month" name="filter_value_bulan" id="filterBulan" onchange="updateActualValue()" value="{{ $tipeFilter == 'bulan' ? $filterValue : now()->format('Y-m') }}" class="form-control" style="display: {{ $tipeFilter == 'bulan' ? 'block' : 'none' }}; width: auto; min-width: 180px; padding: 0.5rem 0.875rem;">
                        
                        <!-- Hidden input to hold the actual submitted value -->
                        <input type="hidden" name="filter_value" id="actualFilterValue" value="{{ $filterValue }}">
                    </div>

                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.875rem;">
                            <i class="ph ph-funnel"></i> Tampilkan
                        </button>
                        <button type="button" onclick="cetakLaporan()" class="btn btn-outline" style="padding: 0.5rem 1.25rem; font-size: 0.875rem; border-color: #059669; color: #059669;">
                            <i class="ph ph-printer"></i> Cetak PDF
                        </button>
                        @if($pesertaId == 'semua')
                        <button type="button" onclick="cetakLaporan('semua_kegiatan')" class="btn btn-outline" style="padding: 0.5rem 1.25rem; font-size: 0.875rem; border-color: #1D4ED8; color: #1D4ED8;" title="Cetak laporan lengkap beserta kegiatan seluruh siswa">
                            <i class="ph ph-file-text"></i> Cetak Lengkap Semua
                        </button>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Tabel Rekap Laporan --}}
            <div class="card" style="padding: 0; overflow: hidden;">
                <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center;">
                    <h3 class="font-bold" style="font-size: 1rem;">
                        Rekap Kehadiran — 
                        @if($tipeFilter == 'hari')
                            {{ \Carbon\Carbon::parse($filterValue)->locale('id')->isoFormat('D MMMM YYYY') }}
                        @elseif($tipeFilter == 'minggu')
                            Minggu {{ substr($filterValue, -2) }}, Tahun {{ substr($filterValue, 0, 4) }}
                        @else
                            {{ \Carbon\Carbon::createFromFormat('Y-m', $filterValue)->locale('id')->isoFormat('MMMM YYYY') }}
                        @endif
                    </h3>
                    <span class="badge badge-info">{{ count($data) }} peserta</span>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Peserta</th>
                                <th>Divisi</th>
                                <th style="text-align: center; color: #059669;">Hadir</th>
                                <th style="text-align: center; color: #d97706;">Terlambat</th>
                                <th style="text-align: center; color: #0284c7;">Izin</th>
                                <th style="text-align: center; color: #DC2626;">Sakit</th>
                                <th style="text-align: center; color: #e11d48;">Alpa</th>
                                <th style="text-align: center;">% Kehadiran</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $i => $item)
                            @php
                                $jumlahHadir = $item['hadir'] + $item['terlambat'];
                                $persen = $item['total'] > 0
                                    ? round(($jumlahHadir / $item['total']) * 100)
                                    : 0;
                                $warnaBar = $persen >= 80 ? '#22c55e' : ($persen >= 60 ? '#f59e0b' : '#ef4444');
                            @endphp
                            <tr>
                                <td style="color: #94A3B8; font-size: 0.8rem;">{{ $i + 1 }}</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <div style="width: 30px; height: 30px; border-radius: 50%; background: linear-gradient(135deg, #1D4ED8, #3B82F6); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; flex-shrink: 0;">
                                            {{ substr($item['peserta']->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold" style="font-size: 0.875rem;">{{ $item['peserta']->name }}</div>
                                            <div class="text-secondary" style="font-size: 0.72rem;">{{ $item['peserta']->nis_nim }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size: 0.82rem; color: #475569;">{{ $item['peserta']->divisi ?? '-' }}</td>
                                <td style="text-align: center;">
                                    <span style="font-weight: 700; color: #059669; font-size: 1rem;">{{ $item['hadir'] }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <span style="font-weight: 700; color: #d97706; font-size: 1rem;">{{ $item['terlambat'] }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <span style="font-weight: 700; color: #0284c7; font-size: 1rem;">{{ $item['izin'] }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <span style="font-weight: 700; color: #DC2626; font-size: 1rem;">{{ $item['sakit'] }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <span style="font-weight: 700; color: #e11d48; font-size: 1rem;">{{ $item['tidak_hadir'] + $item['alpa'] }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; justify-content: center;">
                                        <div style="width: 60px; height: 6px; background: #E2E8F0; border-radius: 3px; overflow: hidden;">
                                            <div style="height: 100%; width: {{ $persen }}%; background: {{ $warnaBar }}; border-radius: 3px;"></div>
                                        </div>
                                        <span style="font-weight: 700; font-size: 0.82rem; color: {{ $warnaBar }};">{{ $persen }}%</span>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <a href="{{ route('admin.laporan.cetak', ['tipe_filter' => $tipeFilter, 'filter_value' => $filterValue, 'peserta_id' => $item['peserta']->id]) }}" 
                                       target="_blank" 
                                       class="btn btn-sm" 
                                       style="padding: 0.35rem 0.75rem; font-size: 0.75rem; background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; border-radius: 6px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem;"
                                       title="Cetak Laporan Lengkap dengan Kegiatan">
                                        <i class="ph ph-printer"></i> Cetak Kegiatan
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-secondary" style="padding: 2.5rem;">
                                    <i class="ph ph-chart-bar" style="font-size: 2rem; display: block; margin-bottom: 0.5rem; opacity: 0.3;"></i>
                                    Tidak ada data peserta untuk ditampilkan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Detail Rincian Harian & Laporan Kegiatan Siswa jika filter per peserta --}}
            @if($pesertaId !== 'semua' && count($data) > 0)
            <div class="card mt-4" style="padding: 0; overflow: hidden;">
                <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center; background: #F8FAFC;">
                    <div>
                        <h3 class="font-bold" style="font-size: 1rem; color: #1E293B; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="ph ph-notebook" style="color: #059669; font-size: 1.2rem;"></i> 
                            Rincian Kehadiran & Laporan Kegiatan: {{ $data[0]['peserta']->name }}
                        </h3>
                        <p style="margin: 0.25rem 0 0; font-size: 0.8rem; color: #64748B;">
                            Periode: 
                            @if($tipeFilter == 'hari')
                                {{ \Carbon\Carbon::parse($filterValue)->locale('id')->isoFormat('D MMMM YYYY') }}
                            @elseif($tipeFilter == 'minggu')
                                Minggu {{ substr($filterValue, -2) }}, Tahun {{ substr($filterValue, 0, 4) }}
                            @else
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $filterValue)->locale('id')->isoFormat('MMMM YYYY') }}
                            @endif
                        </p>
                    </div>
                    <a href="{{ route('admin.laporan.cetak', ['tipe_filter' => $tipeFilter, 'filter_value' => $filterValue, 'peserta_id' => $data[0]['peserta']->id]) }}" 
                       target="_blank" 
                       class="btn btn-sm btn-primary" 
                       style="padding: 0.45rem 1rem; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.4rem; background: #059669; border: none;">
                        <i class="ph ph-printer"></i> Cetak Laporan Kegiatan
                    </a>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th style="width: 170px;">Hari, Tanggal</th>
                                <th style="width: 110px; text-align: center;">Status</th>
                                <th style="width: 110px; text-align: center;">Jam Masuk</th>
                                <th style="width: 110px; text-align: center;">Jam Pulang</th>
                                <th>Deskripsi Kegiatan / Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data[0]['detail_absensi'] as $idx => $r)
                            <tr>
                                <td style="color: #94A3B8; font-size: 0.8rem;">{{ $idx + 1 }}</td>
                                <td style="font-weight: 600; font-size: 0.85rem; color: #1E293B;">
                                    {{ \Carbon\Carbon::parse($r->tanggal)->locale('id')->isoFormat('dddd, DD MMM Y') }}
                                </td>
                                <td style="text-align: center;">
                                    @if($r->status == 'hadir')
                                        <span class="badge badge-success">Hadir</span>
                                    @elseif($r->status == 'terlambat')
                                        <span class="badge badge-warning">Terlambat</span>
                                    @elseif($r->status == 'izin')
                                        <span class="badge badge-info">Izin</span>
                                    @elseif($r->status == 'sakit')
                                        <span class="badge badge-danger">Sakit</span>
                                    @elseif($r->status == 'alpa' || $r->status == 'tidak_hadir')
                                        <span class="badge" style="background:#FEE2E2; color:#EF4444; border: 1px solid #FCA5A5;">Alpa</span>
                                    @else
                                        <span class="badge">{{ ucfirst($r->status) }}</span>
                                    @endif
                                </td>
                                <td style="text-align: center; color: #64748B; font-size: 0.85rem;">
                                    {{ $r->jam_masuk ? date('H:i', strtotime($r->jam_masuk)) . ' WIB' : '--:--' }}
                                </td>
                                <td style="text-align: center; color: #64748B; font-size: 0.85rem;">
                                    {{ $r->jam_pulang ? date('H:i', strtotime($r->jam_pulang)) . ' WIB' : '--:--' }}
                                </td>
                                <td style="font-size: 0.85rem; color: #334155; line-height: 1.5; white-space: pre-wrap;">
                                    {{ $r->keterangan ?: '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary" style="padding: 2.5rem;">
                                    <i class="ph ph-calendar-blank" style="font-size: 2rem; display: block; margin-bottom: 0.5rem; opacity: 0.3;"></i>
                                    Tidak ada data kehadiran dan laporan kegiatan pada periode ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

<script>
function changeFilterInput() {
    var type = document.getElementById('tipeFilter').value;
    document.getElementById('filterHari').style.display = type === 'hari' ? 'block' : 'none';
    document.getElementById('filterMinggu').style.display = type === 'minggu' ? 'block' : 'none';
    document.getElementById('filterBulan').style.display = type === 'bulan' ? 'block' : 'none';
    updateActualValue();
}

function updateActualValue() {
    var type = document.getElementById('tipeFilter').value;
    var actual = document.getElementById('actualFilterValue');
    if (type === 'hari') {
        actual.value = document.getElementById('filterHari').value;
    } else if (type === 'minggu') {
        actual.value = document.getElementById('filterMinggu').value;
    } else {
        actual.value = document.getElementById('filterBulan').value;
    }
}

function cetakLaporan(mode) {
    updateActualValue();
    var tipeFilter = document.getElementById('tipeFilter').value;
    var filterValue = document.getElementById('actualFilterValue').value;
    var pesertaId = document.getElementById('pesertaId').value;
    
    var url = "{{ route('admin.laporan.cetak') }}?tipe_filter=" + encodeURIComponent(tipeFilter) + "&filter_value=" + encodeURIComponent(filterValue) + "&peserta_id=" + encodeURIComponent(pesertaId);
    if (mode) {
        url += "&mode=" + encodeURIComponent(mode);
    }
    window.open(url, '_blank');
}
</script>
@endsection
