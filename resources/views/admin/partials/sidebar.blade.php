<div class="student-sidebar">
    <div class="student-brand" style="display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div class="student-brand-mark" style="background: transparent; padding: 0; box-shadow: none;">
                <img src="{{ asset('img/logo-karawang.png') }}" alt="Logo Karawang" style="width: 36px; height: auto;">
            </div>
            <div>
                <strong>SIAP PKL</strong>
                <span>Admin</span>
            </div>
        </div>
        <button class="mobile-menu-toggle d-lg-none" onclick="toggleSidebar()" style="color: white;">
            <i class="ph ph-x"></i>
        </button>
    </div>

    <nav class="student-nav">
        <a href="{{ route('admin.dashboard') }}" class="student-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="ph ph-squares-four"></i>
            Dashboard
        </a>
        <a href="{{ route('admin.peserta') }}" class="student-nav-item {{ request()->routeIs('admin.peserta') ? 'active' : '' }}">
            <i class="ph ph-users"></i>
            Data Peserta
        </a>
        <a href="{{ route('admin.pengajuan') }}" class="student-nav-item {{ request()->routeIs('admin.pengajuan') ? 'active' : '' }}">
            <i class="ph ph-envelope-open"></i>
            Pengajuan
        </a>
        <a href="{{ route('admin.kehadiran') }}" class="student-nav-item {{ request()->routeIs('admin.kehadiran') ? 'active' : '' }}">
            <i class="ph ph-clock-counter-clockwise"></i>
            Manajemen Kehadiran
        </a>
        <a href="{{ route('admin.laporan') }}" class="student-nav-item {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
            <i class="ph ph-file-text"></i>
            Laporan
        </a>
        <a href="{{ route('admin.pengaturan') }}" class="student-nav-item {{ request()->routeIs('admin.pengaturan') ? 'active' : '' }}">
            <i class="ph ph-gear"></i>
            Pengaturan
        </a>
    </nav>

    <div class="sidebar-illustration">
        <img src="{{ asset('img/sidebar-ill.png') }}" alt="Ilustrasi Kecamatan Cikampek">
    </div>

    <div class="student-sidebar-footer">
        <div class="student-mini-profile">
            <div class="avatar" style="background: white; color: #0F766E;">A</div>
            <div>
                <strong>Admin</strong>
                <span>Kec. Cikampek</span>
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
