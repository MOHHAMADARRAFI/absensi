@extends('layouts.app')

@section('title', 'Riwayat Presensi')

@section('content')
<div class="student-dashboard">
    <!-- Sidebar -->
    <div class="student-sidebar">
        <div class="student-brand">
            <div class="student-brand-mark">
                <i class="ph ph-map-pin-line"></i>
            </div>
            <div>
                <strong>SIAP PKL</strong>
                <span>Kec. Cikampek</span>
            </div>
        </div>

        <nav class="student-nav">
            <a href="{{ route('peserta.dashboard') }}" class="student-nav-item">
                <i class="ph ph-squares-four"></i>
                Dashboard
            </a>
            <a href="{{ route('peserta.riwayat') }}" class="student-nav-item active">
                <i class="ph ph-clock-counter-clockwise"></i>
                Riwayat Presensi
            </a>
        </nav>

        <div class="student-sidebar-footer">
            <div class="student-mini-profile">
                @if(Auth::user()->foto_profil)
                    <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" class="avatar" alt="Foto">
                @else
                    <div class="avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                @endif
                <div>
                    <strong>{{ Auth::user()->name }}</strong>
                    <span>Peserta PKL</span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="student-logout" title="Keluar">
                    <i class="ph ph-sign-out"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="student-main">
        <div class="student-topbar">
            <div>
                <div class="student-eyebrow">Data Log</div>
                <h1>Riwayat Presensi</h1>
            </div>
            <div class="student-date">
                <i class="ph ph-calendar-blank"></i>
                <span class="font-semibold">{{ date('l, d F Y') }}</span>
            </div>
        </div>

        <div class="student-content">
            <div class="card p-0" style="overflow: hidden;">
                <div class="table-container">
                    <table style="min-width: 800px;">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Masuk</th>
                                <th>Pulang</th>
                                <th>Status</th>
                                <th>Keterangan / Jarak</th>
                                <th>Foto / Bukti</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $r)
                            <tr>
                                <td class="font-bold">{{ date('d M Y', strtotime($r->tanggal)) }}</td>
                                <td>{{ $r->jam_masuk ? date('H:i', strtotime($r->jam_masuk)) : '--:--' }}</td>
                                <td>{{ $r->jam_pulang ? date('H:i', strtotime($r->jam_pulang)) : '--:--' }}</td>
                                <td>
                                    @if($r->status == 'hadir')
                                        <span class="badge badge-success">Hadir</span>
                                    @elseif($r->status == 'izin' || $r->status == 'sakit')
                                        <span class="badge badge-warning" style="text-transform: capitalize;">{{ $r->status }}</span>
                                    @else
                                        <span class="badge badge-danger" style="text-transform: capitalize;">{{ $r->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($r->status == 'hadir')
                                        <span class="text-secondary" style="font-size: 0.85rem;"><i class="ph ph-map-pin"></i> {{ $r->jarak_masuk ?? 0 }}m</span>
                                    @else
                                        <span class="text-secondary" style="font-size: 0.85rem;">{{ $r->keterangan ?? '-' }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($r->foto_masuk)
                                        <a href="{{ asset('storage/' . $r->foto_masuk) }}" target="_blank" class="text-primary font-bold" style="font-size: 0.85rem;">Lihat Bukti</a>
                                    @else
                                        <span class="text-secondary" style="opacity: 0.5;">Tidak ada</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary py-5">
                                    <i class="ph ph-folder-open mb-2 d-block" style="font-size: 2rem; opacity: 0.5;"></i>
                                    Belum ada data riwayat presensi.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-4">
                {{ $riwayat->links() ?? '' }}
            </div>
        </div>
    </div>
</div>
@endsection
