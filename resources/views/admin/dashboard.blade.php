@extends('layouts.app')

@section('title', 'Dashboard Admin - SIAP PKL')

@push('styles')
<style>
    /* Modal Styles */
    .custom-modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(4px);
    }
    .custom-modal-overlay.active { display: flex; }
    .custom-modal {
        background: white;
        border-radius: 16px;
        width: 100%;
        max-width: 500px;
        padding: 1.5rem;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        transform: scale(0.95);
        opacity: 0;
        transition: all 0.2s;
    }
    .custom-modal-overlay.active .custom-modal {
        transform: scale(1);
        opacity: 1;
    }
    .custom-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #E2E8F0;
    }
    .custom-modal-title { font-weight: 700; font-size: 1.1rem; color: #1E293B; }
    .custom-modal-close {
        background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #64748B;
    }
    .custom-modal-close:hover { color: #0F172A; }
    .custom-modal-body {
        max-height: 60vh;
        overflow-y: auto;
    }
    .custom-modal-footer {
        display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.25rem;
    }
    
    .keterangan-cell {
        font-size: 0.8rem; color: #475569; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: pointer; transition: color 0.2s;
    }
    .keterangan-cell:hover {
        color: #2563EB;
        text-decoration: underline;
    }
</style>
@endpush

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
                    <div class="student-eyebrow">Ringkasan</div>
                    <h1 style="font-size: 1.25rem; font-weight: 800; color: #1E293B; letter-spacing: -0.02em;">Dashboard Admin</h1>
                </div>
            </div>
            <div class="student-date">
                <i class="ph ph-calendar-blank"></i>
                <span class="font-semibold">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
            </div>
        </div>

        <div class="student-content">

            {{-- Stat Cards --}}
            <div class="stat-grid">
                <div class="modern-card d-flex align-center gap-4">
                    <div style="padding: 0.875rem; background: rgba(59,130,246,0.1); border-radius: 50%; color: var(--primary); flex-shrink: 0;">
                        <i class="ph ph-users" style="font-size: 1.75rem;"></i>
                    </div>
                    <div>
                        <p class="text-secondary font-semibold" style="font-size: 0.8rem;">Total Peserta PKL</p>
                        <h3 style="font-size: 1.75rem; font-weight: 800; margin-top: 0.1rem;">{{ $totalPeserta }}</h3>
                    </div>
                </div>

                <div class="modern-card d-flex align-center gap-4">
                    <div style="padding: 0.875rem; background: rgba(16,185,129,0.1); border-radius: 50%; color: var(--success); flex-shrink: 0;">
                        <i class="ph ph-check-circle" style="font-size: 1.75rem;"></i>
                    </div>
                    <div>
                        <p class="text-secondary font-semibold" style="font-size: 0.8rem;">Hadir Hari Ini</p>
                        <h3 style="font-size: 1.75rem; font-weight: 800; margin-top: 0.1rem;">{{ $hadirHariIni }}</h3>
                    </div>
                </div>

                <div class="modern-card d-flex align-center gap-4">
                    <div style="padding: 0.875rem; background: rgba(245,158,11,0.1); border-radius: 50%; color: var(--warning); flex-shrink: 0;">
                        <i class="ph ph-envelope-simple" style="font-size: 1.75rem;"></i>
                    </div>
                    <div>
                        <p class="text-secondary font-semibold" style="font-size: 0.8rem;">Izin Hari Ini</p>
                        <h3 style="font-size: 1.75rem; font-weight: 800; margin-top: 0.1rem;">{{ $izinHariIni }}</h3>
                    </div>
                </div>

                <div class="modern-card d-flex align-center gap-4">
                    <div style="padding: 0.875rem; background: rgba(239,68,68,0.1); border-radius: 50%; color: var(--danger); flex-shrink: 0;">
                        <i class="ph ph-first-aid" style="font-size: 1.75rem;"></i>
                    </div>
                    <div>
                        <p class="text-secondary font-semibold" style="font-size: 0.8rem;">Sakit Hari Ini</p>
                        <h3 style="font-size: 1.75rem; font-weight: 800; margin-top: 0.1rem;">{{ $sakitHariIni }}</h3>
                    </div>
                </div>
            </div>



            {{-- Tabel Absensi Hari Ini --}}
            <div class="modern-card p-0" style="overflow: hidden;">
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
                                <th>Keterangan/Laporan</th>
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
                                    @elseif($absen->status == 'terlambat')
                                        <span class="badge badge-warning">Terlambat</span>
                                    @elseif($absen->status == 'izin')
                                        <span class="badge badge-info">Izin</span>
                                    @elseif($absen->status == 'sakit')
                                        <span class="badge badge-danger">Sakit</span>
                                    @elseif($absen->status == 'tidak_hadir' || $absen->status == 'alpa')
                                        <span class="badge" style="background:#FEE2E2; color:#EF4444; border: 1px solid #FCA5A5;">Tidak Hadir</span>
                                    @else
                                        <span class="badge" style="background:#F1F5F9; color:#64748B; border: 1px solid #E2E8F0;">{{ ucfirst($absen->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($absen->keterangan)
                                        <div class="keterangan-cell" title="Klik untuk melihat detail" onclick="openDetailModal(`{{ htmlspecialchars($absen->keterangan) }}`)">
                                            {{ $absen->keterangan }}
                                        </div>
                                    @else
                                        <div style="font-size: 0.8rem; color: #475569;">-</div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary" style="padding: 2.5rem;">
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

<!-- Detail Modal -->
<div class="custom-modal-overlay" id="detailModalOverlay">
    <div class="custom-modal">
        <div class="custom-modal-header">
            <div class="custom-modal-title">Detail Keterangan/Laporan</div>
            <button class="custom-modal-close" onclick="closeDetailModal()"><i class="ph ph-x"></i></button>
        </div>
        <div class="custom-modal-body">
            <div id="detailKeteranganText" style="font-size: 0.95rem; line-height: 1.6; color: #475569; word-wrap: break-word; overflow-wrap: break-word; word-break: break-word;"></div>
        </div>
        <div class="custom-modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeDetailModal()">Tutup</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openDetailModal(text) {
        // Decode HTML entities
        let txt = document.createElement("textarea");
        txt.innerHTML = text;
        document.getElementById('detailKeteranganText').innerText = txt.value;
        document.getElementById('detailModalOverlay').classList.add('active');
    }

    function closeDetailModal() {
        document.getElementById('detailModalOverlay').classList.remove('active');
    }
</script>
@endpush
@endsection
