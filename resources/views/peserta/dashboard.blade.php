@extends('layouts.app')

@section('title', 'Dashboard Peserta')

@section('content')
@php
    $tglMulai = $user->tgl_mulai ? \Carbon\Carbon::parse($user->tgl_mulai) : null;
    $tglSelesai = $user->tgl_selesai ? \Carbon\Carbon::parse($user->tgl_selesai) : null;
    
    $totalHari = 0;
    $sisaHari = 0;
    
    if($tglMulai && $tglSelesai) {
        $totalHari = $tglMulai->diffInDays($tglSelesai) + 1;
        if(now()->startOfDay()->lte($tglSelesai)) {
            $sisaHari = now()->startOfDay()->diffInDays($tglSelesai) + 1;
        }
    }
    $hadir = \App\Models\Absensi::where('user_id', $user->id)->where('status', 'hadir')->count();
    $izin = \App\Models\Absensi::where('user_id', $user->id)->where('status', 'izin')->count();
    $sakit = \App\Models\Absensi::where('user_id', $user->id)->where('status', 'sakit')->count();
    $terlambat = \App\Models\Absensi::where('user_id', $user->id)->where('status', 'terlambat')->count();
    $tidak_hadir = \App\Models\Absensi::where('user_id', $user->id)->where('status', 'tidak_hadir')->count();
    $alpa = \App\Models\Absensi::where('user_id', $user->id)->where('status', 'alpa')->count();
    
    $totalDataAbsen = $hadir + $terlambat + $izin + $sakit + $tidak_hadir + $alpa;
    $totalHari = $totalDataAbsen; // Use actual recorded days so far instead of estimated total duration

    $jumlahHadir = $hadir + $terlambat;
    $persentase = $totalHari > 0 ? round(($jumlahHadir / $totalHari) * 100) : 0;
    if($persentase > 100) $persentase = 100;
    
    $durasi = '--';
    if($absensiHariIni && $absensiHariIni->jam_masuk) {
        $masuk = \Carbon\Carbon::createFromFormat('H:i:s', $absensiHariIni->jam_masuk);
        $pulang = $absensiHariIni->jam_pulang ? \Carbon\Carbon::createFromFormat('H:i:s', $absensiHariIni->jam_pulang) : now();
        $diff = $masuk->diff($pulang);
        $durasi = $diff->h . 'j ' . $diff->i . 'm';
    }
@endphp
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
                <h1>Dashboard Peserta</h1>
            </div>
            <div class="student-date">
                <i class="ph ph-calendar-blank"></i>
                <span class="font-semibold">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                <span class="student-divider">|</span>
                <i class="ph ph-clock"></i>
                <span id="realtime-clock" class="font-bold text-primary">--:--:-- WIB</span>
            </div>
        </div>

        <div class="student-content">
            <!-- Welcome Hero -->
            <div class="hero-card mb-4">
                <div class="hero-content">
                    <div class="hero-eyebrow">SELAMAT DATANG</div>
                    <h2>Halo, {{ $user->name }}! 👋</h2>
                    <p class="hero-school">{{ $user->sekolah_universitas ?: 'Sekolah/Kampus belum ditentukan' }}</p>
                    
                    <div class="hero-details">
                        <div class="hero-detail-item">
                            <i class="ph ph-buildings"></i>
                            <div>
                                <span>Penempatan:</span>
                                <strong>{{ $user->divisi ?: 'Belum ditentukan' }}</strong>
                            </div>
                        </div>
                        <div class="hero-detail-item">
                            <i class="ph ph-user"></i>
                            <div>
                                <span>Pembimbing:</span>
                                <strong>{{ $user->pembimbing ?: '-' }}</strong>
                            </div>
                        </div>
                        <div class="hero-detail-item">
                            <i class="ph ph-calendar"></i>
                            <div>
                                <span>Periode PKL:</span>
                                <strong>
                                    @if($tglMulai && $tglSelesai)
                                        {{ $tglMulai->format('d M Y') }} - {{ $tglSelesai->format('d M Y') }}
                                    @else
                                        Belum ditentukan
                                    @endif
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hero-illustration">
                    <img src="{{ asset('img/hero-illustration.jpg') }}" alt="Ilustrasi Peserta PKL">
                </div>
            </div>

            <!-- CSS Grid Layout for content -->
            <div class="dash-layout">
                <!-- Left Column -->
                <div class="dash-col-main">
                    
                    <!-- Aktivitas Hari Ini -->
                    <div class="section-title">Aktivitas Hari Ini</div>
                    <div class="modern-card activity-bar mb-4">
                        <div class="activity-item">
                            <span class="activity-label">Status Presensi</span>
                            <div class="activity-value">
                                @if(!$absensiHariIni || (!$absensiHariIni->jam_masuk && $absensiHariIni->status == 'alpa'))
                                    <span class="status-badge badge-gray"><i class="ph ph-minus"></i> Belum Presensi</span>
                                @elseif($absensiHariIni->status == 'hadir')
                                    <span class="status-badge badge-green"><i class="ph ph-check-circle"></i> Hadir</span>
                                @elseif($absensiHariIni->status == 'terlambat')
                                    <span class="status-badge badge-warning"><i class="ph ph-warning"></i> Terlambat</span>
                                @elseif($absensiHariIni->status == 'izin')
                                    <span class="status-badge badge-amber"><i class="ph ph-clock"></i> Izin</span>
                                @elseif($absensiHariIni->status == 'sakit')
                                    <span class="status-badge badge-red"><i class="ph ph-first-aid"></i> Sakit</span>
                                @elseif($absensiHariIni->status == 'tidak_hadir' || $absensiHariIni->status == 'alpa')
                                    <span class="status-badge badge-red"><i class="ph ph-x-circle"></i> Tidak Hadir</span>
                                @else
                                    <span class="status-badge badge-gray" style="text-transform: capitalize;">{{ $absensiHariIni->status }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="activity-divider"></div>
                        <div class="activity-item">
                            <span class="activity-label">Jam Masuk</span>
                            <strong class="activity-time">{{ $absensiHariIni && $absensiHariIni->jam_masuk ? date('H:i', strtotime($absensiHariIni->jam_masuk)) . ' WIB' : '--:--' }}</strong>
                        </div>
                        <div class="activity-divider"></div>
                        <div class="activity-item">
                            <span class="activity-label">Jam Pulang</span>
                            <strong class="activity-time">{{ $absensiHariIni && $absensiHariIni->jam_pulang ? date('H:i', strtotime($absensiHariIni->jam_pulang)) . ' WIB' : '--:--' }}</strong>
                        </div>
                        <div class="activity-divider"></div>
                        <div class="activity-item">
                            <span class="activity-label">Durasi Kehadiran</span>
                            <strong class="activity-time">{{ $durasi }}</strong>
                        </div>
                    </div>

                    <!-- Menu Cepat -->
                    <div class="section-title">Menu Cepat</div>
                    <div class="quick-menu-grid mb-4">
                        <!-- Presensi Masuk Card -->
                        <div class="quick-card {{ ($absensiHariIni && $absensiHariIni->jam_masuk) || ($absensiHariIni && $absensiHariIni->status != 'alpa' && $absensiHariIni->status != 'hadir') ? 'disabled' : '' }}">
                            <div class="quick-icon-wrapper">
                                <i class="ph ph-sign-in"></i>
                            </div>
                            <div class="quick-info">
                                <h3>Presensi Masuk</h3>
                                <p>Catat waktu masuk kegiatan PKL</p>
                            </div>
                            <a href="{{ route('peserta.absen', ['type' => 'masuk']) }}" class="btn-quick">
                                Presensi Masuk &rarr;
                            </a>
                        </div>
                        
                        <!-- Presensi Pulang Card -->
                        <div class="quick-card {{ (!$absensiHariIni || !$absensiHariIni->jam_masuk || $absensiHariIni->jam_pulang) ? 'disabled' : '' }}">
                            <div class="quick-icon-wrapper">
                                <i class="ph ph-sign-out"></i>
                            </div>
                            <div class="quick-info">
                                <h3>Presensi Pulang</h3>
                                <p>Catat waktu pulang kegiatan PKL</p>
                            </div>
                            <a href="{{ route('peserta.absen', ['type' => 'pulang']) }}" class="btn-quick">
                                Presensi Pulang &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- Timeline Aktivitas -->
                    <div class="section-title">Timeline Aktivitas</div>
                    <div class="modern-card timeline-card">
                        @if(!$absensiHariIni || (!$absensiHariIni->jam_masuk && !$absensiHariIni->jam_pulang))
                            <div class="empty-state">
                                <i class="ph ph-clock-dashed"></i>
                                <p>Belum ada aktivitas hari ini.</p>
                            </div>
                        @else
                            <div class="timeline">
                                @if($absensiHariIni->jam_masuk)
                                <div class="timeline-item">
                                    <div class="timeline-time">{{ date('H:i', strtotime($absensiHariIni->jam_masuk)) }}</div>
                                    <div class="timeline-marker success"><i class="ph ph-check"></i></div>
                                    <div class="timeline-content">
                                        <h4>Presensi Masuk</h4>
                                        <p>Mulai kegiatan PKL</p>
                                    </div>
                                </div>
                                @endif
                                
                                @if($absensiHariIni->jam_pulang)
                                <div class="timeline-item">
                                    <div class="timeline-time">{{ date('H:i', strtotime($absensiHariIni->jam_pulang)) }}</div>
                                    <div class="timeline-marker success"><i class="ph ph-check"></i></div>
                                    <div class="timeline-content">
                                        <h4>Presensi Pulang</h4>
                                        <p>Selesai kegiatan PKL</p>
                                    </div>
                                </div>
                                @else
                                <div class="timeline-item">
                                    <div class="timeline-time">--:--</div>
                                    <div class="timeline-marker pending"><i class="ph ph-dots-three"></i></div>
                                    <div class="timeline-content">
                                        <h4>Presensi Pulang</h4>
                                        <p>Belum dilakukan</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    
                </div>
                
                <!-- Right Column -->
                <div class="dash-col-side">
                    


                    <!-- Ringkasan Kehadiran -->
                    <div class="section-title">Ringkasan Kehadiran</div>
                    <div class="modern-card summary-card mb-4">
                        <div class="summary-grid">
                            <div class="sum-box hadir">
                                <span>Hadir</span>
                                <strong>{{ $hadir ?: 0 }}</strong>
                            </div>
                            <div class="sum-box warning">
                                <span>Terlambat</span>
                                <strong>{{ $terlambat ?: 0 }}</strong>
                            </div>
                            <div class="sum-box izin">
                                <span>Izin</span>
                                <strong>{{ $izin ?: 0 }}</strong>
                            </div>
                            <div class="sum-box sakit">
                                <span>Sakit</span>
                                <strong>{{ $sakit ?: 0 }}</strong>
                            </div>
                            <div class="sum-box danger">
                                <span>Tidak Hadir</span>
                                <strong>{{ ($tidak_hadir + $alpa) ?: 0 }}</strong>
                            </div>
                            <div class="sum-box total">
                                <span>Total Hari</span>
                                <strong>{{ $totalHari ?: 0 }}</strong>
                            </div>
                        </div>
                        <div class="progress-wrap mt-4">
                            <div class="progress-header">
                                <strong>{{ $persentase }}%</strong>
                                <span>Persentase Kehadiran</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $persentase }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi PKL -->
                    <div class="section-title">Informasi PKL</div>
                    <div class="modern-card info-card mb-4">
                        <ul class="info-list">
                            <li>
                                <i class="ph ph-buildings"></i>
                                <div>
                                    <span>Instansi</span>
                                    <strong>Kecamatan Cikampek</strong>
                                </div>
                            </li>
                            <li>
                                <i class="ph ph-map-trifold"></i>
                                <div>
                                    <span>Penempatan</span>
                                    <strong>{{ $user->divisi ?: 'Belum ditentukan' }}</strong>
                                </div>
                            </li>
                            <li>
                                <i class="ph ph-user"></i>
                                <div>
                                    <span>Pembimbing</span>
                                    <strong>{{ $user->pembimbing ?: '-' }}</strong>
                                </div>
                            </li>
                            <li>
                                <i class="ph ph-calendar"></i>
                                <div>
                                    <span>Periode PKL</span>
                                    <strong>
                                        @if($tglMulai && $tglSelesai)
                                            {{ $tglMulai->format('d M Y') }} - {{ $tglSelesai->format('d M Y') }}
                                        @else
                                            Belum ditentukan
                                        @endif
                                    </strong>
                                </div>
                            </li>
                            <li>
                                <i class="ph ph-hourglass-high"></i>
                                <div>
                                    <span>Sisa Hari PKL</span>
                                    <strong>{{ $sisaHari > 0 ? $sisaHari . ' Hari' : '-' }}</strong>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Pengingat -->
                    <div class="reminder-card mb-4">
                        <i class="ph ph-bell-ringing"></i>
                        <div>
                            <strong>Pengingat</strong>
                            <p>
                                @if(!$absensiHariIni || !$absensiHariIni->jam_masuk)
                                    Jangan lupa melakukan presensi masuk sebelum memulai kegiatan PKL.
                                @elseif(!$absensiHariIni->jam_pulang)
                                    Jangan lupa melakukan presensi pulang setelah kegiatan PKL selesai.
                                @else
                                    Presensi hari ini telah selesai. Selamat beristirahat!
                                @endif
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        const clockElem = document.getElementById('realtime-clock');
        if (clockElem) {
            clockElem.innerText = `${hours}:${minutes}:${seconds} WIB`;
        }
    }
    
    updateClock();
    setInterval(updateClock, 1000);
</script>
@endpush
