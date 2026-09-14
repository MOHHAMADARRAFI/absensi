@extends('layouts.app')

@section('title', 'Dashboard - ' . $user->name)

@section('content')
<div class="mobile-layout">
    {{-- Header --}}
    <div class="mobile-header">
        <div class="d-flex justify-between align-center">
            <div class="user-profile-header">
                @if($user->foto_profil)
                    <img src="{{ asset('storage/' . $user->foto_profil) }}" class="avatar" alt="Foto Profil">
                @else
                    <div class="avatar">{{ substr($user->name, 0, 1) }}</div>
                @endif
                <div>
                    <h3 class="font-bold" style="font-size: 1rem; line-height: 1.2;">{{ $user->name }}</h3>
                    <p style="font-size: 0.8rem; opacity: 0.85; margin-top: 2px;">{{ $user->sekolah_universitas }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="position: relative; z-index: 10;">
                @csrf
                <button type="submit" title="Keluar" style="color: white; font-size: 1.5rem; cursor: pointer;">
                    <i class="ph ph-sign-out"></i>
                </button>
            </form>
        </div>

        {{-- Info PKL --}}
        <div class="mt-3 pt-3" style="border-top: 1px solid rgba(255,255,255,0.2);">
            <div class="d-flex gap-4" style="flex-wrap: wrap; gap: 0.75rem;">
                <div style="font-size: 0.8rem; opacity: 0.9;">
                    <i class="ph ph-buildings"></i>
                    {{ $user->divisi ?? 'Belum diatur' }}
                </div>
                @if($user->tgl_mulai && $user->tgl_selesai)
                <div style="font-size: 0.8rem; opacity: 0.9;">
                    <i class="ph ph-calendar"></i>
                    {{ date('d M Y', strtotime($user->tgl_mulai)) }} — {{ date('d M Y', strtotime($user->tgl_selesai)) }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mobile-content">

        {{-- Notifikasi Wajah Belum Terdaftar --}}
        @if(!$user->face_descriptor)
        <div class="alert-info mb-4">
            <i class="ph ph-warning"></i>
            <div>
                <strong>Wajah Belum Terdaftar</strong>
                <p style="font-size: 0.82rem; margin-top: 2px; opacity: 0.9;">Silakan hubungi Admin untuk melakukan registrasi wajah sebelum dapat melakukan presensi.</p>
            </div>
        </div>
        @endif

        {{-- Status Presensi Hari Ini --}}
        <div class="card mb-4">
            <div class="d-flex justify-between align-center mb-3">
                <h4 class="font-bold">Presensi Hari Ini</h4>
                <span class="text-secondary" style="font-size: 0.8rem;">
                    {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </span>
            </div>

            <div class="attendance-status-grid">
                {{-- Masuk --}}
                <div class="attendance-time-card">
                    <div class="attendance-time-label">
                        <i class="ph ph-sign-in"></i>
                        <span>Masuk</span>
                    </div>
                    @if($absensiHariIni && $absensiHariIni->jam_masuk)
                        <div class="attendance-time-value text-success">
                            {{ date('H:i', strtotime($absensiHariIni->jam_masuk)) }}
                            <span class="attendance-wib">WIB</span>
                        </div>
                        <div style="font-size: 0.75rem;" class="text-secondary mt-1">Tercatat</div>
                    @elseif($absensiHariIni && in_array($absensiHariIni->status, ['izin', 'sakit']))
                        <div class="attendance-time-value" style="color: var(--warning); text-transform: capitalize;">
                            {{ $absensiHariIni->status }}
                        </div>
                    @else
                        <div class="attendance-time-value text-secondary">--:--</div>
                        <div style="font-size: 0.75rem;" class="text-secondary mt-1">Belum absen</div>
                    @endif
                </div>

                <div class="attendance-divider"><i class="ph ph-minus"></i></div>

                {{-- Pulang --}}
                <div class="attendance-time-card">
                    <div class="attendance-time-label">
                        <i class="ph ph-sign-out"></i>
                        <span>Pulang</span>
                    </div>
                    @if($absensiHariIni && $absensiHariIni->jam_pulang)
                        <div class="attendance-time-value text-success">
                            {{ date('H:i', strtotime($absensiHariIni->jam_pulang)) }}
                            <span class="attendance-wib">WIB</span>
                        </div>
                        <div style="font-size: 0.75rem;" class="text-secondary mt-1">Tercatat</div>
                    @else
                        <div class="attendance-time-value text-secondary">--:--</div>
                        <div style="font-size: 0.75rem;" class="text-secondary mt-1">Belum absen</div>
                    @endif
                </div>
            </div>

            {{-- Status Badge --}}
            <div class="mt-3 text-center">
                @if(!$absensiHariIni || (!$absensiHariIni->jam_masuk && $absensiHariIni->status === 'alpa'))
                    <span class="badge badge-warning">Belum Presensi</span>
                @elseif($absensiHariIni->jam_masuk && $absensiHariIni->jam_pulang)
                    <span class="badge badge-success">Presensi Lengkap</span>
                @elseif($absensiHariIni->jam_masuk)
                    <span class="badge badge-info">Sudah Masuk</span>
                @elseif($absensiHariIni->status === 'izin')
                    <span class="badge badge-warning">Izin</span>
                @elseif($absensiHariIni->status === 'sakit')
                    <span class="badge badge-danger">Sakit</span>
                @endif
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="action-grid mb-4">
            {{-- Absen Masuk --}}
            @php
                $bisaMasuk = $user->face_descriptor &&
                    (!$absensiHariIni || (!$absensiHariIni->jam_masuk && $absensiHariIni->status === 'alpa') || !$absensiHariIni);
            @endphp
            <a href="{{ route('peserta.absen', ['type' => 'masuk']) }}"
                class="action-card {{ !$bisaMasuk ? 'action-card-disabled' : '' }}"
                {{ !$bisaMasuk ? 'onclick="return false;"' : '' }}>
                <i class="ph ph-fingerprint"></i>
                <span>Absen Masuk</span>
            </a>

            {{-- Absen Pulang --}}
            @php
                $bisaPulang = $user->face_descriptor &&
                    $absensiHariIni && $absensiHariIni->jam_masuk && !$absensiHariIni->jam_pulang;
            @endphp
            <a href="{{ route('peserta.absen', ['type' => 'pulang']) }}"
                class="action-card {{ !$bisaPulang ? 'action-card-disabled' : '' }}"
                {{ !$bisaPulang ? 'onclick="return false;"' : '' }}>
                <i class="ph ph-door-open"></i>
                <span>Absen Pulang</span>
            </a>

            <a href="{{ route('peserta.izin_sakit') }}" class="action-card">
                <i class="ph ph-envelope-simple"></i>
                <span>Pengajuan Izin</span>
            </a>

            <a href="{{ route('peserta.riwayat') }}" class="action-card">
                <i class="ph ph-clock-counter-clockwise"></i>
                <span>Riwayat</span>
            </a>
        </div>

        {{-- Statistik Bulan Ini --}}
        <div class="card mb-4">
            <h4 class="font-bold mb-3">Rekap Bulan Ini</h4>
            <div class="stats-row">
                <div class="stat-pill stat-hadir">
                    <span class="stat-number">{{ $totalHadir }}</span>
                    <span class="stat-label">Hadir</span>
                </div>
                <div class="stat-pill stat-izin">
                    <span class="stat-number">{{ $totalIzin }}</span>
                    <span class="stat-label">Izin/Sakit</span>
                </div>
            </div>
        </div>

        {{-- Info Peserta --}}
        <div class="card mb-4">
            <h4 class="font-bold mb-3">Informasi PKL</h4>
            <div class="info-list">
                <div class="info-item">
                    <span class="info-key">NIS / NIM</span>
                    <span class="info-val">{{ $user->nis_nim ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-key">Jurusan</span>
                    <span class="info-val">{{ $user->jurusan ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-key">Divisi</span>
                    <span class="info-val">{{ $user->divisi ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-key">Pembimbing</span>
                    <span class="info-val">{{ $user->pembimbing ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-key">Status Wajah</span>
                    <span class="info-val">
                        @if($user->face_descriptor)
                            <span class="badge badge-success" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">Terdaftar</span>
                        @else
                            <span class="badge badge-warning" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">Belum Terdaftar</span>
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-key">Status PKL</span>
                    <span class="info-val">
                        @if($user->status_aktif === 'aktif')
                            <span class="badge badge-success" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">Aktif</span>
                        @else
                            <span class="badge badge-danger" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">Nonaktif</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

    </div>

    {{-- Bottom Nav --}}
    <div class="bottom-nav">
        <a href="{{ route('peserta.dashboard') }}" class="nav-item active">
            <i class="ph ph-house"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('peserta.absen', ['type' => 'masuk']) }}" class="nav-item">
            <i class="ph ph-fingerprint"></i>
            <span>Presensi</span>
        </a>
        <a href="{{ route('peserta.riwayat') }}" class="nav-item">
            <i class="ph ph-clock-counter-clockwise"></i>
            <span>Riwayat</span>
        </a>
    </div>
</div>
@endsection
