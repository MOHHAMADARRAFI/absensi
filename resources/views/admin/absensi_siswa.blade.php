@extends('layouts.app')

@section('title', 'Absensi Siswa - Admin SIAP PKL')

@push('styles')
<style>
    .absensi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }
    
    .absensi-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    .absensi-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px dashed #E2E8F0;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    
    .student-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .student-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1D4ED8, #3B82F6);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        flex-shrink: 0;
    }
    
    .absensi-card-body {
        padding: 1.25rem 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .time-boxes {
        display: flex;
        gap: 0.75rem;
    }
    
    .time-box {
        flex: 1;
        background: #F8FAFC;
        border: 1px solid #F1F5F9;
        border-radius: 8px;
        padding: 0.75rem;
        text-align: center;
    }
    
    .time-box span {
        display: block;
        font-size: 0.7rem;
        color: #64748B;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 0.25rem;
    }
    
    .time-box strong {
        font-size: 1.1rem;
        color: #1E293B;
    }
    
    .report-box {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        padding: 1rem;
    }
    
    .report-title {
        font-size: 0.8rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .report-content {
        font-size: 0.9rem;
        color: #334155;
        line-height: 1.5;
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
    }
    
    .photo-grid {
        display: flex;
        gap: 0.75rem;
        margin-top: auto;
    }
    
    .photo-item {
        flex: 1;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #E2E8F0;
        background: #F8FAFC;
        text-align: center;
    }
    
    .photo-item img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        display: block;
        cursor: pointer;
        transition: transform 0.2s;
    }
    
    .photo-item img:hover {
        transform: scale(1.05);
    }
    
    .photo-label {
        font-size: 0.7rem;
        font-weight: 600;
        color: #64748B;
        padding: 0.25rem 0;
        border-bottom: 1px solid #E2E8F0;
        background: white;
    }
    
    /* Image Modal Styles */
    .img-modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.8);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        backdrop-filter: blur(4px);
    }
    .img-modal-overlay.active { display: flex; }
    .img-modal {
        background: white;
        border-radius: 12px;
        padding: 0.5rem;
        max-width: 90vw;
        max-height: 90vh;
        position: relative;
    }
    .img-modal img {
        max-width: 100%;
        max-height: calc(90vh - 1rem);
        border-radius: 8px;
        display: block;
    }
    .img-modal-close {
        position: absolute;
        top: -1.5rem;
        right: -1.5rem;
        background: white;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.2rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        color: #0F172A;
    }
    
    @media (max-width: 768px) {
        .absensi-grid {
            grid-template-columns: 1fr;
        }
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
                    <div class="student-eyebrow">Real-time</div>
                    <h1 style="font-size: 1.25rem; font-weight: 800; color: #1E293B; letter-spacing: -0.02em;">Absensi Siswa</h1>
                </div>
            </div>
            <div class="student-date">
                <i class="ph ph-calendar-blank"></i>
                <span class="font-semibold">{{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
            </div>
        </div>

        <div class="student-content">
            
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <h2 style="font-size: 1.1rem; font-weight: 700; color: #1E293B;">
                    Data Kehadiran Hari Ini
                </h2>
                <span class="badge badge-info">{{ $absensi->count() }} Data</span>
            </div>

            @if($absensi->isEmpty())
                <div class="modern-card text-center py-5">
                    <i class="ph ph-clock-dashed mb-3" style="font-size: 3rem; color: #94A3B8;"></i>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #1E293B;">Belum Ada Data</h3>
                    <p style="color: #64748B;">Belum ada siswa yang melakukan absensi hari ini.</p>
                </div>
            @else
                <div class="absensi-grid">
                    @foreach($absensi as $absen)
                    <div class="absensi-card">
                        <div class="absensi-card-header">
                            <div class="student-info">
                                <div class="student-avatar">
                                    {{ substr($absen->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold" style="font-size: 0.95rem; color: #1E293B;">{{ $absen->user->name }}</div>
                                    <div style="font-size: 0.8rem; color: #64748B;">{{ $absen->user->divisi ?? 'Belum ada divisi' }} &bull; {{ $absen->user->nis_nim }}</div>
                                </div>
                            </div>
                            <div>
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
                            </div>
                        </div>
                        
                        <div class="absensi-card-body">
                            @if(in_array($absen->status, ['hadir', 'terlambat']))
                            <div class="time-boxes">
                                <div class="time-box">
                                    <span>Jam Masuk</span>
                                    <strong>{{ $absen->jam_masuk ? date('H:i', strtotime($absen->jam_masuk)) : '--:--' }} WIB</strong>
                                </div>
                                <div class="time-box">
                                    <span>Jam Pulang</span>
                                    <strong>{{ $absen->jam_pulang ? date('H:i', strtotime($absen->jam_pulang)) : '--:--' }} WIB</strong>
                                </div>
                            </div>
                            @endif

                            <div class="report-box">
                                <div class="report-title">
                                    @if(in_array($absen->status, ['hadir', 'terlambat']))
                                        <i class="ph ph-notebook" style="color: #059669;"></i> Laporan Kegiatan
                                    @else
                                        <i class="ph ph-info" style="color: #D97706;"></i> Keterangan
                                    @endif
                                </div>
                                <div class="report-content">
                                    @if($absen->keterangan)
                                        {{ $absen->keterangan }}
                                    @else
                                        <span style="font-style: italic; color: #94A3B8;">Belum ada keterangan/laporan.</span>
                                    @endif
                                </div>
                            </div>
                            
                            @if($absen->foto_masuk || $absen->foto_pulang)
                            <div class="photo-grid">
                                @if($absen->foto_masuk)
                                <div class="photo-item">
                                    <div class="photo-label">Foto Masuk</div>
                                    <img src="{{ asset('storage/' . $absen->foto_masuk) }}" alt="Foto Masuk" onclick="openImgModal(this.src)">
                                </div>
                                @endif
                                
                                @if($absen->foto_pulang)
                                <div class="photo-item">
                                    <div class="photo-label">Foto Pulang</div>
                                    <img src="{{ asset('storage/' . $absen->foto_pulang) }}" alt="Foto Pulang" onclick="openImgModal(this.src)">
                                </div>
                                @endif
                            </div>
                            @endif
                            
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="img-modal-overlay" id="imgModalOverlay" onclick="closeImgModal(event)">
    <div class="img-modal">
        <button class="img-modal-close" onclick="closeImgModal(event, true)"><i class="ph ph-x"></i></button>
        <img id="imgModalSrc" src="" alt="Full View">
    </div>
</div>

@push('scripts')
<script>
    function openImgModal(src) {
        document.getElementById('imgModalSrc').src = src;
        document.getElementById('imgModalOverlay').classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }
    
    function closeImgModal(e, force = false) {
        // Only close if clicking the overlay (background) or the close button
        if (force || e.target === document.getElementById('imgModalOverlay')) {
            document.getElementById('imgModalOverlay').classList.remove('active');
            document.body.style.overflow = '';
        }
    }
</script>
@endpush
@endsection
