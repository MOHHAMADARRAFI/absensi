@extends('layouts.app')

@section('title', 'Dashboard Peserta')



@section('content')
<div class="mobile-layout">
    <div class="mobile-header">
        <div class="d-flex justify-between align-center">
            <div class="user-profile-header">
                @if($user->foto_profil)
                    <img src="{{ asset('storage/' . $user->foto_profil) }}" class="avatar" alt="Foto Profil">
                @else
                    <div class="avatar">{{ substr($user->name, 0, 1) }}</div>
                @endif
                <div>
                    <h3 class="font-bold">{{ $user->name }}</h3>
                    <p style="font-size: 0.875rem; opacity: 0.9;">{{ $user->sekolah_universitas }} - {{ $user->jurusan }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" style="color: white; font-size: 1.5rem; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    <i class="ph ph-sign-out"></i>
                </button>
            </form>
        </div>
        <div class="mt-4">
            <p style="font-size: 0.875rem; opacity: 0.9;">Divisi: {{ $user->divisi }} | Pembimbing: {{ $user->pembimbing }}</p>
            <p style="font-size: 0.875rem; opacity: 0.9;">Periode: {{ date('d M Y', strtotime($user->tgl_mulai)) }} - {{ date('d M Y', strtotime($user->tgl_selesai)) }}</p>
        </div>
    </div>

    <div class="mobile-content">
        <div class="card mb-4">
            <h4 class="mb-2">Status Absensi Hari Ini</h4>
            <p class="text-secondary" style="font-size: 0.875rem;">{{ date('l, d F Y') }}</p>
            
            <div class="d-flex justify-between mt-4">
                <div class="text-center">
                    <p class="text-secondary" style="font-size: 0.875rem;">Masuk</p>
                    <p class="font-bold" style="font-size: 1.25rem;">
                        {{ $absensiHariIni && $absensiHariIni->jam_masuk ? date('H:i', strtotime($absensiHariIni->jam_masuk)) : '--:--' }}
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-secondary" style="font-size: 0.875rem;">Pulang</p>
                    <p class="font-bold" style="font-size: 1.25rem;">
                        {{ $absensiHariIni && $absensiHariIni->jam_pulang ? date('H:i', strtotime($absensiHariIni->jam_pulang)) : '--:--' }}
                    </p>
                </div>
            </div>

            <div class="mt-4 text-center">
                @if(!$absensiHariIni || (!$absensiHariIni->jam_masuk && $absensiHariIni->status == 'alpa'))
                    <span class="badge badge-warning">Belum Absen</span>
                @elseif($absensiHariIni->status == 'hadir')
                    <span class="badge badge-success">Hadir</span>
                @else
                    <span class="badge badge-warning" style="text-transform: capitalize;">{{ $absensiHariIni->status }}</span>
                @endif
            </div>
        </div>

        <div class="action-grid">
            <a href="{{ route('peserta.absen', ['type' => 'masuk']) }}" class="action-card" {!! ($absensiHariIni && $absensiHariIni->jam_masuk) || ($absensiHariIni && $absensiHariIni->status != 'alpa' && $absensiHariIni->status != 'hadir') ? 'style="opacity:0.5; pointer-events:none;"' : '' !!}>
                <i class="ph ph-sign-in"></i>
                <span>Absen Masuk</span>
            </a>
            <a href="{{ route('peserta.absen', ['type' => 'pulang']) }}" class="action-card" {!! (!$absensiHariIni || !$absensiHariIni->jam_masuk || $absensiHariIni->jam_pulang) ? 'style="opacity:0.5; pointer-events:none;"' : '' !!}>
                <i class="ph ph-sign-out"></i>
                <span>Absen Pulang</span>
            </a>
            <a href="{{ route('peserta.izin_sakit') }}" class="action-card">
                <i class="ph ph-envelope-simple"></i>
                <span>Izin</span>
            </a>
            <a href="{{ route('peserta.izin_sakit') }}?type=sakit" class="action-card">
                <i class="ph ph-first-aid"></i>
                <span>Sakit</span>
            </a>
        </div>
    </div>

    <!-- Bottom Nav -->
    <div class="bottom-nav">
        <a href="{{ route('peserta.dashboard') }}" class="nav-item active">
            <i class="ph ph-house"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('peserta.riwayat') }}" class="nav-item">
            <i class="ph ph-clock-counter-clockwise"></i>
            <span>Riwayat</span>
        </a>
        <a href="#" class="nav-item">
            <i class="ph ph-user"></i>
            <span>Profil</span>
        </a>
    </div>
</div>
@endsection
