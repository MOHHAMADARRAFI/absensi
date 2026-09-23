@extends('layouts.app')

@section('title', 'Riwayat Presensi')

@push('styles')
<style>
    .timeline-page {
        margin-top: 1rem;
    }
    .timeline-page-item {
        position: relative;
        margin-bottom: 2.5rem;
        display: flex;
        align-items: flex-start;
    }
    .timeline-page-item:last-child {
        margin-bottom: 0;
    }
    
    .timeline-page-date {
        width: 6rem;
        flex-shrink: 0;
        text-align: right;
        padding-right: 1.5rem;
        padding-top: 0.25rem;
    }
    .timeline-page-date .day {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1E293B;
        line-height: 1;
    }
    .timeline-page-date .month {
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748B;
        text-transform: uppercase;
        margin-top: 0.25rem;
    }

    .timeline-page-divider {
        position: relative;
        width: 32px;
        flex-shrink: 0;
        display: flex;
        justify-content: center;
        margin-top: 0.25rem;
    }
    .timeline-page-marker {
        position: relative;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        color: white;
        z-index: 2;
    }
    
    .timeline-page-item:not(:last-child) .timeline-page-divider::after {
        content: '';
        position: absolute;
        top: 28px;
        bottom: -2.5rem;
        width: 3px;
        background: #E2E8F0;
        border-radius: 3px;
        left: 50%;
        transform: translateX(-50%);
    }

    .timeline-page-marker.hadir { background: #10B981; border: 3px solid white; box-shadow: 0 0 0 4px #D1FAE5; }
    .timeline-page-marker.izin { background: #F59E0B; border: 3px solid white; box-shadow: 0 0 0 4px #FEF3C7; }
    .timeline-page-marker.sakit { background: #EF4444; border: 3px solid white; box-shadow: 0 0 0 4px #FEE2E2; }
    .timeline-page-marker.alpa { background: #94A3B8; border: 3px solid white; box-shadow: 0 0 0 4px #F1F5F9; }

    .timeline-page-content-wrapper {
        flex-grow: 1;
        padding-left: 1.5rem;
        min-width: 0; /* Ensures child elements don't overflow */
    }

    .timeline-page-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
        transition: all 0.2s ease-in-out;
    }
    .timeline-page-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
        border-color: #CBD5E1;
        transform: translateY(-2px);
    }

    .tp-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.25rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px dashed #E2E8F0;
    }
    .tp-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.15rem;
        font-weight: 700;
        color: #1E293B;
    }
    
    .tp-times {
        display: flex;
        gap: 1rem;
        margin-top: 0.75rem;
        flex-wrap: wrap;
    }
    .tp-time-box {
        display: flex;
        flex-direction: column;
        background: #F8FAFC;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        border: 1px solid #F1F5F9;
        flex: 1;
        min-width: 100px;
    }
    .tp-time-box span { font-size: 0.7rem; color: #64748B; font-weight: 700; text-transform: uppercase; margin-bottom: 2px; }
    .tp-time-box strong { font-size: 1rem; color: #1E293B; }

    .tp-content-box {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        padding: 1.25rem;
    }
    .tp-content-title {
        font-weight: 700;
        color: #334155;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .tp-content-text {
        color: #475569;
        font-size: 0.95rem;
        line-height: 1.6;
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
    }
    
    .tp-footer {
        margin-top: 1.25rem;
        padding-top: 1.25rem;
        border-top: 1px solid #F1F5F9;
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

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
    .custom-modal-body textarea {
        width: 100%;
        min-height: 120px;
        padding: 0.75rem;
        border: 1px solid #CBD5E1;
        border-radius: 8px;
        font-family: inherit;
        font-size: 0.95rem;
        resize: vertical;
    }
    .custom-modal-footer {
        display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.25rem;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .timeline-page-item {
            flex-direction: column;
            align-items: stretch;
            margin-bottom: 1.5rem;
        }
        .timeline-page-date {
            width: auto;
            text-align: left;
            padding-right: 0;
            padding-top: 0;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
        }
        .timeline-page-date .day {
            font-size: 1.25rem;
        }
        .timeline-page-date .month {
            margin-top: 0;
            font-size: 0.9rem;
        }
        .timeline-page-divider {
            display: none;
        }
        .timeline-page-content-wrapper {
            padding-left: 0;
        }
        .timeline-page-card {
            padding: 1.25rem;
            position: relative;
        }
        .tp-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }
        .tp-title {
            font-size: 1rem;
        }
        .tp-header > div:last-child {
            position: absolute;
            top: 1.25rem;
            right: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<div class="student-dashboard">
    <!-- Sidebar -->
    @include('peserta.partials.sidebar')

    <!-- Main Content -->
    <div class="student-main">
        <div class="student-topbar">
            <div style="display: flex; align-items: center;">
                <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                    <i class="ph ph-list"></i>
                </button>
                <div>
                    <div class="student-eyebrow">Data Log</div>
                    <h1>Riwayat Presensi & Aktivitas</h1>
                </div>
            </div>
            <div class="student-date">
                <i class="ph ph-calendar-blank"></i>
                <span class="font-semibold">{{ date('l, d F Y') }}</span>
            </div>
        </div>

        <div class="student-content">
            
            @if($riwayat->count() == 0)
                <div class="modern-card text-center py-5">
                    <i class="ph ph-clock-dashed mb-3" style="font-size: 3rem; color: #94A3B8;"></i>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #1E293B;">Belum Ada Riwayat</h3>
                    <p style="color: #64748B;">Aktivitas presensi Anda akan muncul di sini.</p>
                </div>
            @else
                <div class="timeline-page">
                    @foreach($riwayat as $r)
                        @php
                            $icon = 'ph-check';
                            if($r->status == 'izin') $icon = 'ph-envelope-simple';
                            if($r->status == 'sakit') $icon = 'ph-first-aid';
                            if($r->status == 'alpa') $icon = 'ph-x';
                        @endphp
                        <div class="timeline-page-item">
                            
                            <!-- Date on the left -->
                            <div class="timeline-page-date">
                                <div class="day">{{ date('d', strtotime($r->tanggal)) }}</div>
                                <div class="month">{{ date('M Y', strtotime($r->tanggal)) }}</div>
                            </div>
                            
                            <!-- Marker and Line -->
                            <div class="timeline-page-divider">
                                <div class="timeline-page-marker {{ $r->status }}">
                                    <i class="ph {{ $icon }}"></i>
                                </div>
                            </div>

                            <!-- Card -->
                            <div class="timeline-page-content-wrapper">
                                <div class="timeline-page-card">
                                <div class="tp-header">
                                    <div>
                                        <div class="tp-title">
                                            @if($r->status == 'hadir')
                                                <span style="color: #10B981;">Hadir & Mengikuti PKL</span>
                                            @else
                                                <span style="color: #F59E0B; text-transform: capitalize;">{{ $r->status }}</span>
                                            @endif
                                        </div>
                                        @if($r->status == 'hadir' || $r->status == 'terlambat')
                                        <div class="tp-times">
                                            <div class="tp-time-box">
                                                <span>Jam Masuk</span>
                                                <strong>{{ $r->jam_masuk ? date('H:i', strtotime($r->jam_masuk)) : '--:--' }} WIB</strong>
                                            </div>
                                            <div class="tp-time-box">
                                                <span>Jam Pulang</span>
                                                <strong>{{ $r->jam_pulang ? date('H:i', strtotime($r->jam_pulang)) : '--:--' }} WIB</strong>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div>
                                        @if($r->status == 'hadir')
                                            <span class="badge badge-success">Hadir</span>
                                        @elseif($r->status == 'izin' || $r->status == 'sakit')
                                            <span class="badge badge-warning" style="text-transform: capitalize;">{{ $r->status }}</span>
                                        @else
                                            <span class="badge badge-danger" style="text-transform: capitalize;">{{ $r->status }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="tp-content-box">
                                    @if($r->status == 'hadir' || $r->status == 'terlambat')
                                        <div class="tp-content-title" style="display: flex; justify-content: space-between;">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="ph ph-notebook" style="font-size: 1.2rem; color: #059669;"></i> Laporan Kegiatan:
                                            </div>
                                            <button type="button" class="btn btn-outline" style="padding: 0.2rem 0.6rem; font-size: 0.75rem; border-color: #CBD5E1; color: #64748B; border-radius: 6px;" onclick="openEditModal({{ $r->id }}, `{{ htmlspecialchars($r->keterangan ?? '') }}`)">
                                                <i class="ph ph-pencil-simple"></i> Edit
                                            </button>
                                        </div>
                                        <div class="tp-content-text">
                                            @if($r->keterangan)
                                                {{ $r->keterangan }}
                                            @else
                                                <span style="color: #94A3B8; font-style: italic;">Belum ada laporan kegiatan hari ini (diisi saat absen pulang).</span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="tp-content-title"><i class="ph ph-info" style="font-size: 1.2rem; color: #D97706;"></i> Keterangan {{ ucfirst($r->status) }}:</div>
                                        <div class="tp-content-text">
                                            {{ $r->keterangan ?? 'Tidak ada keterangan tambahan.' }}
                                        </div>
                                    @endif
                                </div>

                                @if($r->foto_masuk || $r->foto_pulang)
                                <div class="tp-footer">
                                    @if($r->foto_masuk)
                                        <a href="{{ asset('storage/' . $r->foto_masuk) }}" target="_blank" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; gap: 0.3rem;">
                                            <i class="ph ph-image"></i> Foto Masuk
                                        </a>
                                    @endif
                                    @if($r->foto_pulang)
                                        <a href="{{ asset('storage/' . $r->foto_pulang) }}" target="_blank" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; gap: 0.3rem;">
                                            <i class="ph ph-image"></i> Foto Pulang
                                        </a>
                                    @endif
                                </div>
                                @endif
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
                
                <div class="mt-4">
                    {{ $riwayat->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="custom-modal-overlay" id="editModalOverlay">
    <div class="custom-modal">
        <div class="custom-modal-header">
            <div class="custom-modal-title">Edit Laporan Kegiatan</div>
            <button class="custom-modal-close" onclick="closeEditModal()"><i class="ph ph-x"></i></button>
        </div>
        <form id="editLaporanForm" method="POST" action="">
            @csrf
            <div class="custom-modal-body">
                <label class="form-label mb-2" style="font-size: 0.85rem; font-weight: 600;">Laporan Kegiatan (Wajib)</label>
                <textarea name="keterangan" id="editKeteranganText" required placeholder="Tuliskan apa saja yang Anda kerjakan atau pelajari hari ini..."></textarea>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn btn-primary" style="background: #059669; border: none;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, text) {
        document.getElementById('editLaporanForm').action = `/riwayat/update-laporan/${id}`;
        
        // Decode HTML entities if any
        let txt = document.createElement("textarea");
        txt.innerHTML = text;
        document.getElementById('editKeteranganText').value = txt.value;
        
        document.getElementById('editModalOverlay').classList.add('active');
    }

    function closeEditModal() {
        document.getElementById('editModalOverlay').classList.remove('active');
    }
</script>
@endsection
