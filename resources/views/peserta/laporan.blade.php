@extends('layouts.app')

@section('title', 'Laporan Pribadi - SIAP PKL')

@section('content')
<div class="student-dashboard">
    {{-- Sidebar --}}
    @include('peserta.partials.sidebar')

    {{-- Main Content --}}
    <div class="student-main">
        <div class="student-topbar">
            <div style="display: flex; align-items: center;">
                <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                    <i class="ph ph-list"></i>
                </button>
                <div>
                    <div class="student-eyebrow">Data Log</div>
                    <h1 style="font-size: 1.25rem; font-weight: 800; color: #1E293B; letter-spacing: -0.02em;">Laporan Presensi Pribadi</h1>
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
                <form method="GET" action="{{ route('peserta.laporan') }}"
                    style="display: flex; align-items: flex-end; gap: 1rem; flex-wrap: wrap;">
                    
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
                        <input type="date" name="filter_value_hari" id="filterHari" value="{{ $tipeFilter == 'hari' ? $filterValue : now()->format('Y-m-d') }}" class="form-control" style="display: {{ $tipeFilter == 'hari' ? 'block' : 'none' }}; width: auto; min-width: 180px; padding: 0.5rem 0.875rem;">
                        <input type="week" name="filter_value_minggu" id="filterMinggu" value="{{ $tipeFilter == 'minggu' ? $filterValue : now()->format('Y-\WW') }}" class="form-control" style="display: {{ $tipeFilter == 'minggu' ? 'block' : 'none' }}; width: auto; min-width: 180px; padding: 0.5rem 0.875rem;">
                        <input type="month" name="filter_value_bulan" id="filterBulan" value="{{ $tipeFilter == 'bulan' ? $filterValue : now()->format('Y-m') }}" class="form-control" style="display: {{ $tipeFilter == 'bulan' ? 'block' : 'none' }}; width: auto; min-width: 180px; padding: 0.5rem 0.875rem;">
                        
                        <input type="hidden" name="filter_value" id="actualFilterValue" value="{{ $filterValue }}">
                    </div>

                    <div style="display: flex; gap: 0.5rem;">
                        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.875rem;" onclick="updateActualValue()">
                            <i class="ph ph-funnel"></i> Tampilkan
                        </button>
                        <a href="{{ route('peserta.laporan.cetak', ['tipe_filter' => $tipeFilter, 'filter_value' => $filterValue]) }}" target="_blank" class="btn btn-outline" style="padding: 0.5rem 1.25rem; font-size: 0.875rem; border-color: #059669; color: #059669;">
                            <i class="ph ph-printer"></i> Cetak PDF
                        </a>
                    </div>
                </form>
            </div>

            <div class="dash-grid" style="margin-bottom: 1.5rem;">
                <div class="modern-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <h4 style="font-size: 0.85rem; color: #64748B; font-weight: 600;">Total Kehadiran</h4>
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: #D1FAE5; display: flex; align-items: center; justify-content: center; color: #059669;">
                            <i class="ph ph-check-circle"></i>
                        </div>
                    </div>
                    <div style="font-size: 1.75rem; font-weight: 800; color: #1E293B;">{{ $data['hadir'] }} <span style="font-size: 0.85rem; font-weight: 600; color: #94A3B8;">Hari</span></div>
                </div>

                <div class="modern-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <h4 style="font-size: 0.85rem; color: #64748B; font-weight: 600;">Total Izin/Sakit</h4>
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: #FEF3C7; display: flex; align-items: center; justify-content: center; color: #D97706;">
                            <i class="ph ph-envelope-simple"></i>
                        </div>
                    </div>
                    <div style="font-size: 1.75rem; font-weight: 800; color: #1E293B;">{{ $data['izin'] + $data['sakit'] }} <span style="font-size: 0.85rem; font-weight: 600; color: #94A3B8;">Hari</span></div>
                </div>

                <div class="modern-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <h4 style="font-size: 0.85rem; color: #64748B; font-weight: 600;">Total Alpa</h4>
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: #FEE2E2; display: flex; align-items: center; justify-content: center; color: #DC2626;">
                            <i class="ph ph-x-circle"></i>
                        </div>
                    </div>
                    <div style="font-size: 1.75rem; font-weight: 800; color: #1E293B;">{{ $data['alpa'] }} <span style="font-size: 0.85rem; font-weight: 600; color: #94A3B8;">Hari</span></div>
                </div>
            </div>

            {{-- Tabel Riwayat --}}
            <div class="card" style="padding: 0; overflow: hidden;">
                <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center;">
                    <h3 class="font-bold" style="font-size: 1rem;">
                        Rincian Harian — 
                        @if($tipeFilter == 'hari')
                            {{ \Carbon\Carbon::parse($filterValue)->locale('id')->isoFormat('D MMMM YYYY') }}
                        @elseif($tipeFilter == 'minggu')
                            Minggu {{ substr($filterValue, -2) }}, Tahun {{ substr($filterValue, 0, 4) }}
                        @else
                            {{ \Carbon\Carbon::createFromFormat('Y-m', $filterValue)->locale('id')->isoFormat('MMMM YYYY') }}
                        @endif
                    </h3>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Laporan / Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['detail_absensi'] as $r)
                            <tr>
                                <td style="font-weight: 600; color: #1E293B;">{{ \Carbon\Carbon::parse($r->tanggal)->locale('id')->isoFormat('DD MMM YYYY') }}</td>
                                <td>
                                    @if($r->status == 'hadir')
                                        <span class="badge badge-success">Hadir</span>
                                    @elseif($r->status == 'izin' || $r->status == 'sakit')
                                        <span class="badge badge-warning" style="text-transform: capitalize;">{{ $r->status }}</span>
                                    @else
                                        <span class="badge badge-danger" style="text-transform: capitalize;">{{ $r->status }}</span>
                                    @endif
                                </td>
                                <td style="color: #64748B;">{{ $r->jam_masuk ? \Carbon\Carbon::parse($r->jam_masuk)->format('H:i') : '--:--' }}</td>
                                <td style="color: #64748B;">{{ $r->jam_pulang ? \Carbon\Carbon::parse($r->jam_pulang)->format('H:i') : '--:--' }}</td>
                                <td style="color: #475569; font-size: 0.85rem;">{{ $r->keterangan ?: '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-secondary" style="padding: 2.5rem;">
                                    <i class="ph ph-calendar-blank" style="font-size: 2rem; display: block; margin-bottom: 0.5rem; opacity: 0.3;"></i>
                                    Tidak ada data absensi untuk ditampilkan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function changeFilterInput() {
    var type = document.getElementById('tipeFilter').value;
    document.getElementById('filterHari').style.display = type === 'hari' ? 'block' : 'none';
    document.getElementById('filterMinggu').style.display = type === 'minggu' ? 'block' : 'none';
    document.getElementById('filterBulan').style.display = type === 'bulan' ? 'block' : 'none';
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
</script>
@endsection
