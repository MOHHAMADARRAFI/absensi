@extends('layouts.app')

@section('title', 'Data Peserta - Admin')

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
            <a href="{{ route('admin.peserta') }}" class="sidebar-item active">
                <i class="ph ph-users"></i>
                <span>Data Peserta</span>
            </a>
            <a href="{{ route('admin.pengajuan') }}" class="sidebar-item">
                <i class="ph ph-envelope-open"></i>
                <span>Pengajuan</span>
            </a>
            <a href="{{ route('admin.laporan') }}" class="sidebar-item">
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
            <h2 class="font-bold">Data Peserta PKL</h2>
            <button class="btn btn-primary"><i class="ph ph-plus"></i> Tambah Peserta</button>
        </div>

        <div class="admin-content">
            <div class="card">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Peserta</th>
                                <th>Instansi / Jurusan</th>
                                <th>Divisi</th>
                                <th>Periode PKL</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peserta as $p)
                            <tr>
                                <td>
                                    <div class="font-semibold">{{ $p->name }}</div>
                                    <div class="text-secondary" style="font-size: 0.75rem;">NIS/NIM: {{ $p->nis_nim }}</div>
                                </td>
                                <td>
                                    <div>{{ $p->sekolah_universitas }}</div>
                                    <div class="text-secondary" style="font-size: 0.75rem;">{{ $p->jurusan }}</div>
                                </td>
                                <td>{{ $p->divisi }}</td>
                                <td>
                                    <div style="font-size: 0.875rem;">{{ date('d M Y', strtotime($p->tgl_mulai)) }} -</div>
                                    <div style="font-size: 0.875rem;">{{ date('d M Y', strtotime($p->tgl_selesai)) }}</div>
                                </td>
                                <td>
                                    @if($p->status_aktif == 'aktif')
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">Edit</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary py-4">Belum ada data peserta.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
