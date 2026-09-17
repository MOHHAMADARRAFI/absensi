@extends('layouts.app')

@section('title', 'Data Peserta - Admin SIAP PKL')

@section('content')
<div class="student-dashboard">
    {{-- Sidebar --}}
    @include('admin.partials.sidebar')

    {{-- Main Content --}}
    <div class="student-main">
        <div class="student-topbar">
            <div style="display: flex; align-items: center;">
                <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                    <i class="ph ph-list"></i>
                </button>
                <div>
                    <div class="student-eyebrow">Manajemen</div>
                    <h1 style="font-size: 1.25rem; font-weight: 800; color: #1E293B; letter-spacing: -0.02em;">Data Peserta PKL</h1>
                </div>
            </div>
            <div class="student-date">
                <i class="ph ph-calendar-blank"></i>
                <span class="font-semibold">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
            </div>
        </div>

        <div class="student-content">
            <div style="margin-bottom: 1.5rem; padding: 1rem; background: white; border: 1px solid #E2E8F0; border-radius: 12px; display: inline-block; font-size: 0.875rem; color: #475569;">
                <strong>Statistik Peserta:</strong> 
                Total {{ $peserta->count() }} orang
            </div>

            @if(session('success'))
            <div class="alert-success mb-4" style="padding: 0.875rem 1rem; border-radius: 8px; background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; display: flex; align-items: center; gap: 0.5rem; font-weight: 600;">
                <i class="ph ph-check-circle" style="font-size: 1.25rem;"></i>
                {{ session('success') }}
            </div>
            @endif

            <div class="modern-card p-0" style="overflow: hidden;">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Peserta</th>
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
                                    <div style="display: flex; align-items: center; gap: 0.625rem;">
                                        <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #1D4ED8, #3B82F6); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem; flex-shrink: 0;">
                                            {{ substr($p->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold" style="font-size: 0.9rem;">{{ $p->name }}</div>
                                            <div class="text-secondary" style="font-size: 0.75rem;">{{ $p->nis_nim }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size: 0.875rem;">{{ $p->sekolah_universitas }}</div>
                                    <div class="text-secondary" style="font-size: 0.75rem;">{{ $p->jurusan }}</div>
                                </td>
                                <td style="font-size: 0.875rem;">{{ $p->divisi ?? '-' }}</td>
                                <td>
                                    @if($p->tgl_mulai && $p->tgl_selesai)
                                    <div style="font-size: 0.8rem;">{{ date('d M Y', strtotime($p->tgl_mulai)) }}</div>
                                    <div style="font-size: 0.8rem; color: #64748B;">s/d {{ date('d M Y', strtotime($p->tgl_selesai)) }}</div>
                                    @else
                                    <span class="text-secondary" style="font-size: 0.8rem;">-</span>
                                    @endif
                                </td>

                                <td>
                                    @if($p->status_aktif == 'aktif')
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">

                                        <a href="{{ route('admin.peserta.edit', $p->id) }}"
                                            class="btn"
                                            style="padding: 0.35rem 0.65rem; font-size: 0.78rem; gap: 0.3rem; background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0;">
                                            <i class="ph ph-pencil-simple"></i>
                                            Edit
                                        </a>

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-secondary" style="padding: 2.5rem;">
                                    <i class="ph ph-users" style="font-size: 2rem; display: block; margin-bottom: 0.5rem; opacity: 0.3;"></i>
                                    Belum ada data peserta PKL.
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
