<div class="student-sidebar">
    <div class="student-brand">
        <div class="student-brand-mark">
            <i class="ph ph-buildings"></i>
        </div>
        <div>
            <strong>SIAP PKL</strong>
            <span>Admin</span>
        </div>
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
        <a href="{{ route('admin.laporan') }}" class="student-nav-item {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
            <i class="ph ph-file-text"></i>
            Laporan
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
