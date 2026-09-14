@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="admin-layout">
    <!-- Sidebar -->
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
                <button type="submit" class="btn btn-outline w-100" style="justify-content: flex-start; border: none; color: var(--danger);">
                    <i class="ph ph-sign-out"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="admin-main">
        <div class="admin-header">
            <h2 class="font-bold">Dashboard</h2>
            <div class="d-flex align-center gap-2">
                <div class="avatar" style="width: 40px; height: 40px; font-size: 1rem; border-color: var(--border); color: var(--dark);">A</div>
                <span class="font-semibold">Admin Kecamatan</span>
            </div>
        </div>

        <div class="admin-content">
            <div class="stat-grid">
                <div class="card d-flex align-center gap-4">
                    <div style="padding: 1rem; background-color: rgba(59, 130, 246, 0.1); border-radius: 50%; color: var(--primary);">
                        <i class="ph ph-users" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <p class="text-secondary font-semibold" style="font-size: 0.875rem;">Total Peserta PKL</p>
                        <h3 style="font-size: 1.5rem; margin-top: 0.25rem;">{{ $totalPeserta }}</h3>
                    </div>
                </div>
                
                <div class="card d-flex align-center gap-4">
                    <div style="padding: 1rem; background-color: rgba(16, 185, 129, 0.1); border-radius: 50%; color: var(--success);">
                        <i class="ph ph-check-circle" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <p class="text-secondary font-semibold" style="font-size: 0.875rem;">Hadir Hari Ini</p>
                        <h3 style="font-size: 1.5rem; margin-top: 0.25rem;">{{ $hadirHariIni }}</h3>
                    </div>
                </div>

                <div class="card d-flex align-center gap-4">
                    <div style="padding: 1rem; background-color: rgba(245, 158, 11, 0.1); border-radius: 50%; color: var(--warning);">
                        <i class="ph ph-envelope-simple" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <p class="text-secondary font-semibold" style="font-size: 0.875rem;">Izin Hari Ini</p>
                        <h3 style="font-size: 1.5rem; margin-top: 0.25rem;">{{ $izinHariIni }}</h3>
                    </div>
                </div>

                <div class="card d-flex align-center gap-4">
                    <div style="padding: 1rem; background-color: rgba(239, 68, 68, 0.1); border-radius: 50%; color: var(--danger);">
                        <i class="ph ph-first-aid" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <p class="text-secondary font-semibold" style="font-size: 0.875rem;">Sakit Hari Ini</p>
                        <h3 style="font-size: 1.5rem; margin-top: 0.25rem;">{{ $sakitHariIni }}</h3>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3 class="mb-4">Absensi Hari Ini</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Peserta</th>
                                <th>Sekolah / Instansi</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensiHariIni as $absen)
                            <tr>
                                <td>
                                    <div class="font-semibold">{{ $absen->user->name }}</div>
                                    <div class="text-secondary" style="font-size: 0.75rem;">{{ $absen->user->nis_nim }}</div>
                                </td>
                                <td>{{ $absen->user->sekolah_universitas }}</td>
                                <td>{{ $absen->jam_masuk ? date('H:i', strtotime($absen->jam_masuk)) : '-' }}</td>
                                <td>{{ $absen->jam_pulang ? date('H:i', strtotime($absen->jam_pulang)) : '-' }}</td>
                                <td>
                                    @if($absen->status == 'hadir')
                                        <span class="badge badge-success">Hadir</span>
                                    @elseif($absen->status == 'alpa')
                                        <span class="badge badge-danger">Alpa</span>
                                    @else
                                        <span class="badge badge-warning" style="text-transform: capitalize;">{{ $absen->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">Detail</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary py-4">Belum ada data absensi hari ini.</td>
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
