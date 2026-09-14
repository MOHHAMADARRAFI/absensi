@extends('layouts.app')

@section('title', 'Laporan Presensi - Admin SIAP PKL')

@section('content')
<div class="admin-layout">
    {{-- Sidebar --}}
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="ph ph-buildings"></i>
            <span>SIAP PKL</span>
        </div>
        <div class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-item">
                <i class="ph ph-squares-four"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.peserta') }}" class="sidebar-item">
                <i class="ph ph-users"></i>
                <span>Data Peserta</span>
            </a>
            <a href="{{ route('admin.pengajuan') }}" class="sidebar-item">
                <i class="ph ph-envelope-open"></i>
                <span>Pengajuan</span>
            </a>
            <a href="{{ route('admin.laporan') }}" class="sidebar-item active">
                <i class="ph ph-file-text"></i>
                <span>Laporan</span>
            </a>
        </div>
        <div style="padding: 1rem;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline w-100"
                    style="justify-content: flex-start; border: none; color: var(--danger);">
                    <i class="ph ph-sign-out"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="admin-main">
        <div class="admin-header">
            <div>
                <h2 class="font-bold">Laporan Presensi</h2>
                <p class="text-secondary" style="font-size: 0.8rem;">Rekap kehadiran peserta PKL per bulan</p>
            </div>
        </div>

        <div class="admin-content">

            {{-- Filter Bulan --}}
            <div class="card mb-4" style="padding: 1.25rem 1.5rem;">
                <form method="GET" action="{{ route('admin.laporan') }}"
                    style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                    <label style="font-weight: 600; font-size: 0.875rem; color: #475569; flex-shrink: 0;">Filter Bulan:</label>
                    <input type="month" name="bulan" value="{{ $bulan }}"
                        class="form-control" style="width: auto; min-width: 180px; padding: 0.5rem 0.875rem;">
                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.875rem;">
                        <i class="ph ph-funnel"></i> Tampilkan
                    </button>
                    <span class="text-secondary" style="font-size: 0.82rem;">
                        Menampilkan: <strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->locale('id')->isoFormat('MMMM YYYY') }}</strong>
                    </span>
                </form>
            </div>

            {{-- Tabel Laporan --}}
            <div class="card" style="padding: 0; overflow: hidden;">
                <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center;">
                    <h3 class="font-bold" style="font-size: 1rem;">
                        Rekap Kehadiran — {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->locale('id')->isoFormat('MMMM YYYY') }}
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
@endsection
