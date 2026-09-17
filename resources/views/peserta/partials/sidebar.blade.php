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
        <a href="{{ route('peserta.dashboard') }}" class="student-nav-item {{ request()->routeIs('peserta.dashboard') ? 'active' : '' }}">
            <i class="ph ph-squares-four"></i>
            Dashboard
        </a>
        <a href="{{ route('peserta.riwayat') }}" class="student-nav-item {{ request()->routeIs('peserta.riwayat') ? 'active' : '' }}">
            <i class="ph ph-clock-counter-clockwise"></i>
            Riwayat Presensi
        </a>
        <a href="{{ route('peserta.laporan') }}" class="student-nav-item {{ request()->routeIs('peserta.laporan') ? 'active' : '' }}">
            <i class="ph ph-file-text"></i>
            Laporan
        </a>
    </nav>

    <div class="sidebar-illustration">
        <img src="{{ asset('img/sidebar-ill.png') }}" alt="Ilustrasi Kecamatan Cikampek">
    </div>

    <div class="student-sidebar-footer">
        <div class="student-mini-profile">
            @if(auth()->user()->foto_profil)
                <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" class="avatar" alt="Foto">
            @else
                <div class="avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
            @endif
            <div>
                <strong>{{ auth()->user()->name }}</strong>
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
