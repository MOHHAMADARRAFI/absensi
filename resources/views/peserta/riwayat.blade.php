@extends('layouts.app')

@section('title', 'Riwayat Presensi')

@section('content')
<div class="mobile-layout" style="padding-bottom: 5rem;">

    {{-- Header --}}
    <div class="mobile-header" style="padding-bottom: 2rem;">
        <div class="d-flex align-center gap-4">
            <a href="{{ route('peserta.dashboard') }}" style="color: white; font-size: 1.4rem; position: relative; z-index: 10;">
                <i class="ph ph-arrow-left"></i>
            </a>
            <div style="position: relative; z-index: 10;">
                <h3 class="font-bold">Riwayat Presensi</h3>
                <p style="font-size: 0.8rem; opacity: 0.85;">{{ $riwayat->count() }} data tercatat</p>
            </div>
        </div>
    </div>

    <div class="mobile-content">

        @forelse($riwayat as $absen)
        <div class="riwayat-item">
            {{-- Dot Status --}}
            <div class="riwayat-dot dot-{{ $absen->status }}"></div>

            {{-- Info Tanggal --}}
            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.25rem;">
                    <span class="font-semibold" style="font-size: 0.875rem;">
                        {{ \Carbon\Carbon::parse($absen->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                    </span>
                    @if($absen->status == 'hadir')
                        <span class="badge badge-success" style="font-size: 0.7rem;">Hadir</span>
                    @elseif($absen->status == 'izin')
                        <span class="badge badge-warning" style="font-size: 0.7rem;">Izin</span>
                    @elseif($absen->status == 'sakit')
                        <span class="badge badge-danger" style="font-size: 0.7rem;">Sakit</span>
                    @else
                        <span class="badge" style="background: #F1F5F9; color: #64748B; border: 1px solid #E2E8F0; font-size: 0.7rem;">Alpa</span>
                    @endif
                </div>

                @if($absen->status == 'hadir')
                <div style="display: flex; gap: 1rem;">
                    <div style="font-size: 0.8rem; color: #64748B;">
                        <i class="ph ph-sign-in" style="color: #22c55e;"></i>
                        Masuk:
                        <span class="font-semibold" style="color: #1E293B;">
                            {{ $absen->jam_masuk ? date('H:i', strtotime($absen->jam_masuk)) : '--:--' }}
                        </span>
                    </div>
                    <div style="font-size: 0.8rem; color: #64748B;">
                        <i class="ph ph-sign-out" style="color: #64748B;"></i>
                        Pulang:
                        <span class="font-semibold" style="color: #1E293B;">
                            {{ $absen->jam_pulang ? date('H:i', strtotime($absen->jam_pulang)) : '--:--' }}
                        </span>
                    </div>
                </div>
                @elseif($absen->keterangan)
                <div style="font-size: 0.78rem; color: #64748B; margin-top: 2px;">
                    {{ Str::limit($absen->keterangan, 60) }}
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center" style="padding: 3rem 1rem;">
            <i class="ph ph-calendar-blank" style="font-size: 3.5rem; color: #CBD5E1; display: block; margin-bottom: 0.75rem;"></i>
            <p class="font-semibold" style="color: #94A3B8;">Belum ada riwayat presensi</p>
            <p class="text-secondary" style="font-size: 0.82rem; margin-top: 0.25rem;">Data presensi Anda akan muncul di sini.</p>
        </div>
        @endforelse

    </div>

    {{-- Bottom Nav --}}
    <div class="bottom-nav">
        <a href="{{ route('peserta.dashboard') }}" class="nav-item">
            <i class="ph ph-house"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('peserta.absen', ['type' => 'masuk']) }}" class="nav-item">
            <i class="ph ph-fingerprint"></i>
            <span>Presensi</span>
        </a>
        <a href="{{ route('peserta.riwayat') }}" class="nav-item active">
            <i class="ph ph-clock-counter-clockwise"></i>
            <span>Riwayat</span>
        </a>
    </div>
</div>
@endsection
