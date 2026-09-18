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
                <form method="GET" action="{{ route('admin.laporan') }}"
                    style="display: flex; align-items: flex-end; gap: 1rem; flex-wrap: wrap;">
                    
                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.85rem; color: #475569; margin-bottom: 0.5rem;">Peserta</label>
                        <select name="peserta_id" id="pesertaId" class="form-control" style="width: auto; min-width: 150px; padding: 0.5rem 0.875rem;">
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
                        <input type="date" name="filter_value_hari" id="filterHari" value="{{ $tipeFilter == 'hari' ? $filterValue : now()->format('Y-m-d') }}" class="form-control" style="display: {{ $tipeFilter == 'hari' ? 'block' : 'none' }}; width: auto; min-width: 180px; padding: 0.5rem 0.875rem;">
                        <input type="week" name="filter_value_minggu" id="filterMinggu" value="{{ $tipeFilter == 'minggu' ? $filterValue : now()->format('Y-\WW') }}" class="form-control" style="display: {{ $tipeFilter == 'minggu' ? 'block' : 'none' }}; width: auto; min-width: 180px; padding: 0.5rem 0.875rem;">
                        <input type="month" name="filter_value_bulan" id="filterBulan" value="{{ $tipeFilter == 'bulan' ? $filterValue : now()->format('Y-m') }}" class="form-control" style="display: {{ $tipeFilter == 'bulan' ? 'block' : 'none' }}; width: auto; min-width: 180px; padding: 0.5rem 0.875rem;">
                        
                        <!-- Hidden input to hold the actual submitted value -->
                        <input type="hidden" name="filter_value" id="actualFilterValue" value="{{ $filterValue }}">
                    </div>

                    <div style="display: flex; gap: 0.5rem;">
                        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.875rem;" onclick="updateActualValue()">
                            <i class="ph ph-funnel"></i> Tampilkan
                        </button>
                        <button type="button" onclick="cetakLaporan()" class="btn btn-outline" style="padding: 0.5rem 1.25rem; font-size: 0.875rem; border-color: #059669; color: #059669;">
                            <i class="ph ph-printer"></i> Cetak PDF
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tabel Laporan --}}
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
                                <th style="text-align: center; color: #D97706;">Izin</th>
                                <th style="text-align: center; color: #DC2626;">Sakit</th>
                                <th style="text-align: center; color: #64748B;">Alpa</th>
                                <th style="text-align: center;">% Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $i => $item)
                            @php
                                $persen = $item['total'] > 0
                                    ? round(($item['hadir'] / $item['total']) * 100)
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
                                    <span style="font-weight: 700; color: #D97706; font-size: 1rem;">{{ $item['izin'] }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <span style="font-weight: 700; color: #DC2626; font-size: 1rem;">{{ $item['sakit'] }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <span style="font-weight: 700; color: #64748B; font-size: 1rem;">{{ $item['alpa'] }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; justify-content: center;">
                                        <div style="width: 60px; height: 6px; background: #E2E8F0; border-radius: 3px; overflow: hidden;">
                                            <div style="height: 100%; width: {{ $persen }}%; background: {{ $warnaBar }}; border-radius: 3px;"></div>
                                        </div>
                                        <span style="font-weight: 700; font-size: 0.82rem; color: {{ $warnaBar }};">{{ $persen }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-secondary" style="padding: 2.5rem;">
                                    <i class="ph ph-chart-bar" style="font-size: 2rem; display: block; margin-bottom: 0.5rem; opacity: 0.3;"></i>
                                    Tidak ada data peserta untuk ditampilkan.
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

function cetakLaporan() {
    updateActualValue();
    var tipeFilter = document.getElementById('tipeFilter').value;
    var filterValue = document.getElementById('actualFilterValue').value;
    var pesertaId = document.getElementById('pesertaId').value;
    
    var url = "{{ route('admin.laporan.cetak') }}?tipe_filter=" + tipeFilter + "&filter_value=" + filterValue + "&peserta_id=" + pesertaId;
    window.open(url, '_blank');
}
</script>
@endsection
