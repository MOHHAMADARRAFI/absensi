@extends('layouts.app')

@section('title', 'Pengajuan Izin/Sakit - Admin SIAP PKL')

@section('content')
<div class="admin-layout">
    {{-- Sidebar --}}
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
                <button type="submit" class="btn btn-outline w-100"
                    style="justify-content: flex-start; border: none; color: var(--danger);">
                    <i class="ph ph-sign-out"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="admin-main">
        <div class="admin-header">
            <div>
                <h2 class="font-bold">Pengajuan Izin / Sakit</h2>
                <p class="text-secondary" style="font-size: 0.8rem;">Kelola pengajuan dari peserta PKL</p>
            </div>
        </div>

        <div class="admin-content">

            @if(session('success'))
            <div style="background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 8px; padding: 0.875rem 1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; color: #166534;">
                <i class="ph ph-check-circle"></i>
                {{ session('success') }}
            </div>
            @endif

            <div class="card" style="padding: 0; overflow: hidden;">
                <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center;">
                    <h3 class="font-bold" style="font-size: 1rem;">Daftar Pengajuan</h3>
                    @php
                        $menunggu = $pengajuan->where('status', 'menunggu')->count();
                    @endphp
                    @if($menunggu > 0)
                    <span class="badge badge-warning">{{ $menunggu }} menunggu persetujuan</span>
                    @else
                    <span class="badge badge-success">Semua sudah diproses</span>
                    @endif
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Peserta</th>
                                <th>Jenis</th>
                                <th>Periode</th>
                                <th>Alasan</th>
                                <th>Dokumen</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengajuan as $p)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <div style="width: 30px; height: 30px; border-radius: 50%; background: linear-gradient(135deg, #1D4ED8, #3B82F6); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; flex-shrink: 0;">
                                            {{ substr($p->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold" style="font-size: 0.875rem;">{{ $p->user->name }}</div>
                                            <div class="text-secondary" style="font-size: 0.72rem;">{{ $p->user->nis_nim }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($p->jenis == 'izin')
                                        <span class="badge badge-warning">Izin</span>
                                    @else
                                        <span class="badge badge-danger">Sakit</span>
                                    @endif
                                </td>
                                <td style="font-size: 0.82rem;">
                                    @if($p->tanggal_mulai == $p->tanggal_selesai)
                                        {{ date('d M Y', strtotime($p->tanggal_mulai)) }}
                                    @else
                                        {{ date('d M Y', strtotime($p->tanggal_mulai)) }}<br>
                                        <span class="text-secondary">s/d {{ date('d M Y', strtotime($p->tanggal_selesai)) }}</span>
                                    @endif
                                </td>
                                <td style="font-size: 0.82rem; max-width: 200px;">
                                    <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px;"
                                        title="{{ $p->alasan }}">
                                        {{ $p->alasan }}
                                    </div>
                                    @if($p->keterangan)
                                    <div class="text-secondary" style="font-size: 0.72rem; margin-top: 2px;">{{ Str::limit($p->keterangan, 50) }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($p->bukti_dokumen)
                                        <a href="{{ asset('storage/' . $p->bukti_dokumen) }}" target="_blank"
                                            style="color: var(--primary); font-size: 0.8rem; display: flex; align-items: center; gap: 0.25rem;">
                                            <i class="ph ph-file"></i> Lihat
                                        </a>
                                    @else
                                        <span class="text-secondary" style="font-size: 0.8rem;">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($p->status == 'menunggu')
                                        <span class="badge badge-pending">Menunggu</span>
                                    @elseif($p->status == 'disetujui')
                                        <span class="badge badge-approved">Disetujui</span>
                                    @else
                                        <span class="badge badge-rejected">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    @if($p->status == 'menunggu')
                                    <div style="display: flex; gap: 0.4rem;">
                                        <form action="{{ route('admin.pengajuan.update', $p->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="disetujui">
                                            <button type="submit" class="btn btn-success"
                                                style="padding: 0.35rem 0.65rem; font-size: 0.78rem; gap: 0.3rem;"
                                                onclick="return confirm('Setujui pengajuan {{ $p->jenis }} dari {{ $p->user->name }}?')">
                                                <i class="ph ph-check"></i> Setujui
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.pengajuan.update', $p->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="ditolak">
                                            <button type="submit" class="btn btn-danger"
                                                style="padding: 0.35rem 0.65rem; font-size: 0.78rem; gap: 0.3rem;"
                                                onclick="return confirm('Tolak pengajuan {{ $p->jenis }} dari {{ $p->user->name }}?')">
                                                <i class="ph ph-x"></i> Tolak
                                            </button>
                                        </form>
                                    </div>
                                    @else
                                        <span class="text-secondary" style="font-size: 0.8rem;">Sudah diproses</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-secondary" style="padding: 2.5rem;">
                                    <i class="ph ph-envelope-open" style="font-size: 2rem; display: block; margin-bottom: 0.5rem; opacity: 0.3;"></i>
                                    Belum ada pengajuan masuk.
                                </td>
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
