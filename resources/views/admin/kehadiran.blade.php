@extends('layouts.app')

@section('title', 'Manajemen Kehadiran - Admin SIAP PKL')

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
                    <h1 style="font-size: 1.25rem; font-weight: 800; color: #1E293B; letter-spacing: -0.02em;">Manajemen Kehadiran</h1>
                </div>
            </div>
            <div class="student-date">
                <i class="ph ph-calendar-blank"></i>
                <span class="font-semibold">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
            </div>
        </div>

        <div class="student-content">
            {{-- Filter Laporan --}}
            <div class="card mb-4" style="padding: 1.25rem 1.5rem;">
                <form method="GET" action="{{ route('admin.kehadiran') }}"
                    style="display: flex; align-items: flex-end; gap: 1rem; flex-wrap: wrap;">
                    
                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.85rem; color: #475569; margin-bottom: 0.5rem;">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control" style="width: auto; min-width: 180px; padding: 0.5rem 0.875rem;">
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.875rem;">
                            <i class="ph ph-funnel"></i> Tampilkan
                        </button>
                    </div>
                </form>
            </div>

            @if(session('success'))
            <div class="alert alert-success" style="background: #D1FAE5; color: #065F46; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
            @endif

            <div class="card" style="padding: 0; overflow: hidden;">
                <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center;">
                    <h3 class="font-bold" style="font-size: 1rem;">
                        Data Kehadiran — {{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM YYYY') }}
                    </h3>
                    <span class="badge badge-info">{{ count($absensi) }} peserta</span>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Peserta</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensi as $absen)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <div style="width: 30px; height: 30px; border-radius: 50%; background: linear-gradient(135deg, #1D4ED8, #3B82F6); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; flex-shrink: 0;">
                                            {{ substr($absen->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold" style="font-size: 0.875rem;">{{ $absen->user->name }}</div>
                                            <div class="text-secondary" style="font-size: 0.72rem;">{{ $absen->user->nis_nim }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-weight: 600; font-size: 0.875rem;">
                                    {{ $absen->jam_masuk ? date('H:i', strtotime($absen->jam_masuk)) : '-' }}
                                </td>
                                <td style="font-size: 0.875rem;">
                                    {{ $absen->jam_pulang ? date('H:i', strtotime($absen->jam_pulang)) : '-' }}
                                </td>
                                <td>
                                    @if($absen->status == 'hadir')
                                        <span class="badge badge-success">Hadir</span>
                                    @elseif($absen->status == 'terlambat')
                                        <span class="badge badge-warning">Terlambat</span>
                                    @elseif($absen->status == 'izin')
                                        <span class="badge badge-info">Izin</span>
                                    @elseif($absen->status == 'sakit')
                                        <span class="badge badge-danger">Sakit</span>
                                    @elseif($absen->status == 'tidak_hadir' || $absen->status == 'alpa')
                                        <span class="badge" style="background:#FEE2E2; color:#EF4444; border: 1px solid #FCA5A5;">Tidak Hadir</span>
                                    @else
                                        <span class="badge" style="background:#F1F5F9; color:#64748B; border: 1px solid #E2E8F0;">{{ ucfirst($absen->status) }}</span>
                                    @endif
                                </td>
                                <td style="font-size: 0.875rem; color: #475569; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $absen->keterangan ?: '-' }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.kehadiran.edit', $absen->id) }}" class="btn btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary" style="padding: 2.5rem;">
                                    <i class="ph ph-calendar-blank" style="font-size: 2rem; display: block; margin-bottom: 0.5rem; opacity: 0.3;"></i>
                                    Tidak ada data presensi pada tanggal ini.
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
