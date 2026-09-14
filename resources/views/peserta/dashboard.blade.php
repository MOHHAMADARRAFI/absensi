@extends('layouts.app')

@section('title', 'Dashboard Peserta')

@section('content')
<div class="student-dashboard">
    <aside class="student-sidebar">
        <div class="student-brand">
            <div class="student-brand-mark"><i class="ph ph-buildings"></i></div>
            <div><strong>SIAP PKL</strong><span>Kecamatan Cikampek</span></div>
        </div>
        <nav class="student-nav" aria-label="Navigasi peserta">
            <a href="{{ route('peserta.dashboard') }}" class="student-nav-item active"><i class="ph ph-squares-four"></i><span>Dashboard</span></a>
            <a href="{{ route('peserta.riwayat') }}" class="student-nav-item"><i class="ph ph-clock-counter-clockwise"></i><span>Riwayat Absensi</span></a>
            <a href="{{ route('peserta.izin_sakit') }}" class="student-nav-item"><i class="ph ph-file-text"></i><span>Pengajuan Izin</span></a>
        </nav>
        <div class="student-sidebar-footer">
            <div class="student-mini-profile">
                @if($user->foto_profil)
                    <img src="{{ asset('storage/' . $user->foto_profil) }}" class="avatar" alt="Foto">
                @else
                    <div class="avatar">{{ substr($user->name, 0, 1) }}</div>
                @endif
                <div><strong>{{ $user->name }}</strong><span>Peserta PKL</span></div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="student-logout" title="Keluar"><i class="ph ph-sign-out"></i></button>
            </form>
        </div>
    </aside>

    <main class="student-main">
        <header class="student-topbar">
            <div><p class="student-eyebrow">Portal Peserta</p><h1>Selamat datang, {{ $user->name }}</h1></div>
            <div class="student-date"><i class="ph ph-calendar-blank"></i>{{ date('l, d F Y') }}</div>
        </header>
        <div class="student-content">
            <section class="student-welcome-panel">
                <div><p class="student-eyebrow">Informasi Penempatan</p><h2>{{ $user->sekolah_universitas }}</h2><p>{{ $user->jurusan }} <span class="student-divider">|</span> Divisi {{ $user->divisi }}</p></div>
                <div class="student-placement"><span>Pembimbing</span><strong>{{ $user->pembimbing }}</strong><small>Periode {{ date('d M Y', strtotime($user->tgl_mulai)) }} - {{ date('d M Y', strtotime($user->tgl_selesai)) }}</small></div>
            </section>

            <div class="student-section-heading"><div><p class="student-eyebrow">Ringkasan hari ini</p><h2>Status Absensi</h2></div><span class="badge badge-warning">{{ !$absensiHariIni ? 'Belum Absen' : ucfirst($absensiHariIni->status) }}</span></div>
            <section class="student-status-card">
                <div class="student-status-intro"><div class="student-status-icon"><i class="ph ph-calendar-check"></i></div><div><strong>Absensi Hari Ini</strong><p>{{ date('l, d F Y') }}</p></div></div>
                <div class="student-time"><span>Masuk</span><strong>{{ $absensiHariIni && $absensiHariIni->jam_masuk ? date('H:i', strtotime($absensiHariIni->jam_masuk)) : '--:--' }}</strong></div>
                <div class="student-time"><span>Pulang</span><strong>{{ $absensiHariIni && $absensiHariIni->jam_pulang ? date('H:i', strtotime($absensiHariIni->jam_pulang)) : '--:--' }}</strong></div>
            </section>

            <div class="student-section-heading"><div><p class="student-eyebrow">Akses cepat</p><h2>Aktivitas Peserta</h2></div></div>
            <div class="action-grid student-action-grid">
                <a href="{{ route('peserta.absen', ['type' => 'masuk']) }}" class="action-card" {!! ($absensiHariIni && $absensiHariIni->jam_masuk) || ($absensiHariIni && $absensiHariIni->status != 'alpa' && $absensiHariIni->status != 'hadir') ? 'style="opacity:0.5; pointer-events:none;"' : '' !!}><i class="ph ph-sign-in"></i><span>Absen Masuk</span></a>
                <a href="{{ route('peserta.absen', ['type' => 'pulang']) }}" class="action-card" {!! (!$absensiHariIni || !$absensiHariIni->jam_masuk || $absensiHariIni->jam_pulang) ? 'style="opacity:0.5; pointer-events:none;"' : '' !!}><i class="ph ph-sign-out"></i><span>Absen Pulang</span></a>
                <a href="{{ route('peserta.izin_sakit') }}" class="action-card"><i class="ph ph-envelope-simple"></i><span>Izin</span></a>
                <a href="{{ route('peserta.izin_sakit') }}?type=sakit" class="action-card"><i class="ph ph-first-aid"></i><span>Sakit</span></a>
            </div>
        </div>
    </main>
</div>
@endsection
