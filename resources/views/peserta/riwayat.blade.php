@extends('layouts.app')

@section('title', 'Riwayat Absensi')

@section('content')
<div class="mobile-layout" style="padding-bottom: 5rem;">
    <div class="mobile-header" style="padding-bottom: 1.5rem; border-radius: 0;">
        <h3 class="font-bold">Riwayat Absensi</h3>
    </div>

    <div class="mobile-content">
        @forelse($riwayat as $absen)
        <div class="card mb-2" style="padding: 1rem;">
            <div class="d-flex justify-between align-center mb-2">
                <span class="font-bold">{{ date('d M Y', strtotime($absen->tanggal)) }}</span>
                @if($absen->status == 'hadir')
                    <span class="badge badge-success">Hadir</span>
                @elseif($absen->status == 'alpa')
                    <span class="badge badge-danger">Alpa</span>
                @else
                    <span class="badge badge-warning" style="text-transform: capitalize;">{{ $absen->status }}</span>
                @endif
            </div>
            
            @if($absen->status == 'hadir')
            <div class="d-flex justify-between mt-2">
                <div>
                    <p class="text-secondary" style="font-size: 0.75rem;">Masuk</p>
                    <p class="font-semibold">{{ $absen->jam_masuk ? date('H:i', strtotime($absen->jam_masuk)) : '--:--' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-secondary" style="font-size: 0.75rem;">Pulang</p>
                    <p class="font-semibold">{{ $absen->jam_pulang ? date('H:i', strtotime($absen->jam_pulang)) : '--:--' }}</p>
                </div>
            </div>
            @endif
        </div>
        @empty
        <div class="text-center mt-4">
            <i class="ph ph-folder-open text-secondary" style="font-size: 3rem;"></i>
            <p class="text-secondary mt-2">Belum ada riwayat absensi</p>
        </div>
        @endforelse
    </div>

    <!-- Bottom Nav -->
    <div class="bottom-nav">
        <a href="{{ route('peserta.dashboard') }}" class="nav-item">
            <i class="ph ph-house"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('peserta.riwayat') }}" class="nav-item active">
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
