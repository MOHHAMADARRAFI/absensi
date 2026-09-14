@extends('layouts.app')

@section('title', 'Dashboard Admin - SIAP PKL')

@section('content')
<div class="admin-layout">
    {{-- Sidebar --}}
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="ph ph-buildings"></i>
            <span>SIAP PKL</span>
        </div>
        <div class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-item active">
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
            <a href="{{ route('admin.laporan') }}" class="sidebar-item">
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
                <h2 class="font-bold">Dashboard</h2>
                <p class="text-secondary" style="font-size: 0.8rem;">
                    {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </p>
            </div>
            <div class="d-flex align-center gap-2">
                <div class="avatar"
                    style="width: 38px; height: 38px; font-size: 0.9rem; background: var(--primary-gradient); color: white; border: none;">
                    A
                </div>
                <div>
                    <div class="font-semibold" style="font-size: 0.875rem;">Admin</div>
                    <div class="text-secondary" style="font-size: 0.75rem;">Kecamatan Cikampek</div>
                </div>
            </div>
        </div>

        <div class="admin-content">

            {{-- Stat Cards --}}
            <div class="stat-grid">
                <div class="card d-flex align-center gap-4">
                    <div style="padding: 0.875rem; background: rgba(59,130,246,0.1); border-radius: 50%; color: var(--primary); flex-shrink: 0;">
                        <i class="ph ph-users" style="font-size: 1.75rem;"></i>
                    </div>
                    <div>
                        <p class="text-secondary font-semibold" style="font-size: 0.8rem;">Total Peserta PKL</p>
                        <h3 style="font-size: 1.75rem; font-weight: 800; margin-top: 0.1rem;">{{ $totalPeserta }}</h3>
                    </div>
                </div>

                <div class="card d-flex align-center gap-4">
                    <div style="padding: 0.875rem; background: rgba(16,185,129,0.1); border-radius: 50%; color: var(--success); flex-shrink: 0;">
                        <i class="ph ph-check-circle" style="font-size: 1.75rem;"></i>
                    </div>
                    <div>
                        <p class="text-secondary font-semibold" style="font-size: 0.8rem;">Hadir Hari Ini</p>
                        <h3 style="font-size: 1.75rem; font-weight: 800; margin-top: 0.1rem;">{{ $hadirHariIni }}</h3>
                    </div>
                </div>

                <div class="card d-flex align-center gap-4">
                    <div style="padding: 0.875rem; background: rgba(245,158,11,0.1); border-radius: 50%; color: var(--warning); flex-shrink: 0;">
                        <i class="ph ph-envelope-simple" style="font-size: 1.75rem;"></i>
                    </div>
                    <div>
                        <p class="text-secondary font-semibold" style="font-size: 0.8rem;">Izin Hari Ini</p>
                        <h3 style="font-size: 1.75rem; font-weight: 800; margin-top: 0.1rem;">{{ $izinHariIni }}</h3>
                    </div>
                </div>

                <div class="card d-flex align-center gap-4">
                    <div style="padding: 0.875rem; background: rgba(239,68,68,0.1); border-radius: 50%; color: var(--danger); flex-shrink: 0;">
                        <i class="ph ph-first-aid" style="font-size: 1.75rem;"></i>
                    </div>
                    <div>
                        <p class="text-secondary font-semibold" style="font-size: 0.8rem;">Sakit Hari Ini</p>
                        <h3 style="font-size: 1.75rem; font-weight: 800; margin-top: 0.1rem;">{{ $sakitHariIni }}</h3>
                    </div>
                </div>
            </div>

            {{-- Status Registrasi Wajah --}}
            <div class="face-stat-card mb-4">
                <div>
                    <div style="font-size: 0.8rem; opacity: 0.7; margin-bottom: 0.25rem;">
                        <i class="ph ph-scan-smiley"></i> Status Registrasi Wajah
                    </div>
                    <div style="font-size: 0.875rem; font-weight: 600;">
                        Peserta yang sudah mendaftarkan wajah dapat melakukan presensi secara mandiri.
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 1.5rem; flex-shrink: 0;">
                    <div class="stat-item text-center">
                        <div class="num" style="color: #4ade80;">{{ $terdaftarWajah }}</div>
                        <div class="lbl">Terdaftar</div>
                    </div>
                    <div class="face-stat-divider"></div>
                    <div class="stat-item text-center">
                        <div class="num" style="color: #fbbf24;">{{ $belumTerdaftarWajah }}</div>
                        <div class="lbl">Belum Terdaftar</div>
                    </div>
                    <div class="face-stat-divider"></div>
                    <div class="stat-item text-center">
                        <div class="num">{{ $totalPeserta }}</div>
                        <div class="lbl">Total</div>
                    </div>
                </div>
                <a href="{{ route('admin.peserta') }}"
                    style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); color: white; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.8rem; font-weight: 600; flex-shrink: 0; white-space: nowrap;">
                    Kelola Wajah →
                </a>
            </div>

            {{-- Tabel Absensi Hari Ini --}}
            <div class="card" style="padding: 0; overflow: hidden;">
                <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center;">
                    <h3 class="font-bold" style="font-size: 1rem;">Presensi Hari Ini</h3>
                    <span class="badge badge-info" style="font-size: 0.75rem;">
                        {{ $absensiHariIni->count() }} peserta
                    </span>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Peserta</th>
                                <th>Divisi</th>
                                <th>Masuk</th>
                                <th>Pulang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensiHariIni as $absen)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <div style="width: 30px; height: 30px; border-radius: 50%; background: linear-gradient(135deg, #1D4ED8, #3B82F6); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; flex-shrink: 0;">
                                            {{ substr($absen->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold" style="font-size: 0.875rem;">{{ $absen->user->name }}</div>
                                            <div class="text-secondary" style="font-size: 0.72rem;">{{ $absen->user->nis_nim }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size: 0.875rem; color: #475569;">{{ $absen->user->divisi ?? '-' }}</td>
                                <td style="font-weight: 600; font-size: 0.875rem;">
                                    {{ $absen->jam_masuk ? date('H:i', strtotime($absen->jam_masuk)) : '-' }}
                                </td>
                                <td style="font-size: 0.875rem;">
                                    {{ $absen->jam_pulang ? date('H:i', strtotime($absen->jam_pulang)) : '-' }}
                                </td>
                                <td>
                                    @if($absen->status == 'hadir')
                                        <span class="badge badge-success">Hadir</span>
                                    @elseif($absen->status == 'izin')
                                        <span class="badge badge-warning">Izin</span>
                                    @elseif($absen->status == 'sakit')
                                        <span class="badge badge-danger">Sakit</span>
                                    @else
                                        <span class="badge" style="background:#F1F5F9; color:#64748B; border: 1px solid #E2E8F0;">Alpa</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-secondary" style="padding: 2.5rem;">
                                    <i class="ph ph-calendar-blank" style="font-size: 2rem; display: block; margin-bottom: 0.5rem; opacity: 0.3;"></i>
                                    Belum ada data presensi hari ini.
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
