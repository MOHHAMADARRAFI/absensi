@extends('layouts.app')

@section('title', 'Laporan - Admin')

@section('content')
<div class="admin-layout">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="ph ph-buildings"></i>
            <span>SIAP PKL</span>
        </div>
        <div class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-item">
                <i class="ph ph-squares-four"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.peserta') }}" class="sidebar-item">
                <i class="ph ph-users"></i>
                <span>Data Peserta</span>
            </a>
            <a href="{{ route('admin.pengajuan') }}" class="sidebar-item">
                <i class="ph ph-envelope-open"></i>
                <span>Pengajuan</span>
            </a>
            <a href="{{ route('admin.laporan') }}" class="sidebar-item active">
                <i class="ph ph-file-text"></i>
                <span>Laporan</span>
            </a>
        </div>
        <div style="padding: 1rem;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline w-100" style="justify-content: flex-start; border: none; color: var(--danger);">
                    <i class="ph ph-sign-out"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="admin-main">
        <div class="admin-header">
            <h2 class="font-bold">Laporan Absensi</h2>
        </div>

        <div class="admin-content">
            <div class="card mb-4">
                <form action="" method="GET" class="d-flex align-center gap-4">
                    <div class="form-group mb-0 flex-1">
                        <label class="form-label">Periode Bulan</label>
                        <input type="month" name="bulan" class="form-control" value="{{ request('bulan', date('Y-m')) }}">
                    </div>
                    <div class="form-group mb-0 flex-1">
                        <label class="form-label">Instansi / Sekolah</label>
                        <select name="sekolah" class="form-control">
                            <option value="">Semua Instansi</option>
                            <option value="SMKN 1 Cikampek">SMKN 1 Cikampek</option>
                        </select>
                    </div>
                    <div class="form-group mb-0" style="margin-top: 1.75rem;">
                        <button type="submit" class="btn btn-primary"><i class="ph ph-funnel"></i> Filter</button>
                    </div>
                    <div class="form-group mb-0" style="margin-top: 1.75rem;">
                        <button type="button" class="btn btn-success"><i class="ph ph-export"></i> Export Excel</button>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Peserta</th>
                                <th>Instansi</th>
                                <th>Hadir</th>
                                <th>Izin</th>
                                <th>Sakit</th>
                                <th>Alpa</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Contoh Data dummy -->
                            <tr>
                                <td>Budi Santoso</td>
                                <td>SMKN 1 Cikampek</td>
                                <td>20</td>
                                <td>1</td>
                                <td>1</td>
                                <td>0</td>
                                <td>
                                    <span class="badge badge-success">95%</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
