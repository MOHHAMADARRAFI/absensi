@extends('layouts.app')

@section('title', 'Data Pengajuan - Admin')

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
            <a href="{{ route('admin.pengajuan') }}" class="sidebar-item active">
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
            <h2 class="font-bold">Pengajuan Izin / Sakit</h2>
        </div>

        <div class="admin-content">
            <div class="card">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal Pengajuan</th>
                                <th>Nama Peserta</th>
                                <th>Jenis</th>
                                <th>Tanggal Izin</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengajuan as $p)
                            <tr>
                                <td>{{ date('d M Y', strtotime($p->created_at)) }}</td>
                                <td>
                                    <div class="font-semibold">{{ $p->user->name }}</div>
                                    <div class="text-secondary" style="font-size: 0.75rem;">{{ $p->user->sekolah_universitas }}</div>
                                </td>
                                <td>
                                    <span class="badge {{ $p->jenis == 'izin' ? 'badge-warning' : 'badge-danger' }}" style="text-transform: capitalize;">{{ $p->jenis }}</span>
                                </td>
                                <td>
                                    @if($p->tanggal_mulai == $p->tanggal_selesai)
                                        {{ date('d M Y', strtotime($p->tanggal_mulai)) }}
                                    @else
                                        {{ date('d M', strtotime($p->tanggal_mulai)) }} - {{ date('d M Y', strtotime($p->tanggal_selesai)) }}
                                    @endif
                                </td>
                                <td>{{ $p->alasan }}</td>
                                <td>
                                    @if($p->status == 'menunggu')
                                        <span class="badge" style="background-color: #E2E8F0; color: #475569;">Menunggu</span>
                                    @elseif($p->status == 'disetujui')
                                        <span class="badge badge-success">Disetujui</span>
                                    @else
                                        <span class="badge badge-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">Lihat</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-secondary py-4">Belum ada data pengajuan.</td>
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
