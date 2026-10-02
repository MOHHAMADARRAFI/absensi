@extends('layouts.app')

@section('title', 'Buat Sertifikat PKL - Admin SIAP PKL')

@section('content')
<div class="student-dashboard">
    {{-- Sidebar --}}
    @include('admin.partials.sidebar')

    {{-- Main Content --}}
    <div class="student-main">
        <div class="student-topbar">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                    <i class="ph ph-list"></i>
                </button>
                <a href="{{ route('admin.sertifikat.index') }}" class="btn-action btn-view" style="width: 36px; height: 36px;">
                    <i class="ph ph-arrow-left"></i>
                </a>
                <div>
                    <div class="student-eyebrow">Sertifikat PKL</div>
                    <h1 style="font-size: 1.25rem; font-weight: 800; color: #1E293B; letter-spacing: -0.02em;">Buat Sertifikat Baru</h1>
                </div>
            </div>
        </div>

        <div class="student-content">
            @if ($errors->any())
            <div class="alert-danger mb-4" style="padding: 1rem; border-radius: 8px; background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B;">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.sertifikat.store') }}" method="POST" id="formSertifikat">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <!-- Data Peserta & Surat -->
                    <div class="modern-card">
                        <div style="margin-bottom: 1.5rem; border-bottom: 1px solid #E2E8F0; padding-bottom: 1rem;">
                            <h3 style="font-size: 1.1rem; font-weight: 700; color: #0F766E; margin: 0;">Data Peserta & Surat</h3>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label font-semibold text-secondary">Pilih Peserta PKL <span class="text-danger">*</span></label>
                            <select name="user_id" id="user_id" class="form-control" required>
                                <option value="">-- Pilih Peserta --</option>
                                @foreach($pesertas as $peserta)
                                    <option value="{{ $peserta->id }}" {{ old('user_id') == $peserta->id ? 'selected' : '' }}>
                                        {{ $peserta->name }} - {{ $peserta->sekolah_universitas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Data Peserta Readonly -->
                        <div id="data-peserta-preview" style="display: none; background: #F8FAFC; padding: 1rem; border-radius: 8px; border: 1px solid #E2E8F0; margin-bottom: 1.5rem;">
                            <h4 style="font-size: 0.9rem; font-weight: 600; color: #475569; margin-top: 0; margin-bottom: 0.75rem;">Preview Data Peserta</h4>
                            <div style="display: grid; grid-template-columns: 120px 1fr; gap: 0.5rem; font-size: 0.875rem;">
                                <div style="color: #64748B;">Nama</div>
                                <div id="preview_name" style="font-weight: 600;">-</div>
                                
                                <div style="color: #64748B;">Tempat/Tgl Lahir</div>
                                <div id="preview_ttl">-</div>
                                
                                <div style="color: #64748B;">Jenis Kelamin</div>
                                <div id="preview_jk">-</div>
                                
                                <div style="color: #64748B;">Sekolah/Univ</div>
                                <div id="preview_sekolah">-</div>
                                
                                <div style="color: #64748B;">Jurusan</div>
                                <div id="preview_jurusan">-</div>
                                
                                <div style="color: #64748B;">Periode PKL</div>
                                <div id="preview_periode">-</div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label font-semibold text-secondary">Nomor Sertifikat <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_sertifikat" class="form-control" value="{{ old('nomor_sertifikat') }}" required placeholder="Contoh: 800/423 / Sekret /2026">
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label font-semibold text-secondary">Tanggal Sertifikat <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_sertifikat" class="form-control" value="{{ old('tanggal_sertifikat', date('Y-m-d')) }}" required>
                        </div>

                        <div class="form-group mb-0">
                            <label class="form-label font-semibold text-secondary">Hasil PKL <span class="text-danger">*</span></label>
                            <select name="hasil_pkl" class="form-control" required>
                                <option value="">-- Pilih Hasil --</option>
                                <option value="SANGAT BAIK" {{ old('hasil_pkl') == 'SANGAT BAIK' ? 'selected' : '' }}>SANGAT BAIK</option>
                                <option value="BAIK" {{ old('hasil_pkl') == 'BAIK' ? 'selected' : '' }}>BAIK</option>
                                <option value="CUKUP" {{ old('hasil_pkl') == 'CUKUP' ? 'selected' : '' }}>CUKUP</option>
                            </select>
                        </div>
                    </div>

                    <!-- Penilaian -->
                    <div class="modern-card">
                        <div style="margin-bottom: 1.5rem; border-bottom: 1px solid #E2E8F0; padding-bottom: 1rem;">
                            <h3 style="font-size: 1.1rem; font-weight: 700; color: #0F766E; margin: 0;">Mata Latihan dan Penilaian</h3>
                            <p style="font-size: 0.875rem; color: #64748B; margin: 0.25rem 0 0 0;">Input nilai angka (0-100). Nilai huruf akan terhitung otomatis.</p>
                        </div>

                        <div class="table-responsive">
                            <table class="modern-table" style="font-size: 0.875rem;">
                                <thead>
                                    <tr>
                                        <th style="width: 40%">Mata Latihan</th>
                                        <th style="width: 25%">Nilai Angka</th>
                                        <th style="width: 35%">Nilai Huruf</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Kerajinan</td>
                                        <td><input type="number" name="nilai_kerajinan" class="form-control nilai-input" value="{{ old('nilai_kerajinan') }}" required min="0" max="100" style="padding: 0.3rem 0.5rem;"></td>
                                        <td><span class="nilai-huruf text-secondary" style="font-style: italic;">-</span></td>
                                    </tr>
                                    <tr>
                                        <td>Inisiatif</td>
                                        <td><input type="number" name="nilai_inisiatif" class="form-control nilai-input" value="{{ old('nilai_inisiatif') }}" required min="0" max="100" style="padding: 0.3rem 0.5rem;"></td>
                                        <td><span class="nilai-huruf text-secondary" style="font-style: italic;">-</span></td>
                                    </tr>
                                    <tr>
                                        <td>Kerjasama</td>
                                        <td><input type="number" name="nilai_kerjasama" class="form-control nilai-input" value="{{ old('nilai_kerjasama') }}" required min="0" max="100" style="padding: 0.3rem 0.5rem;"></td>
                                        <td><span class="nilai-huruf text-secondary" style="font-style: italic;">-</span></td>
                                    </tr>
                                    <tr>
                                        <td>Kedisiplinan</td>
                                        <td><input type="number" name="nilai_kedisiplinan" class="form-control nilai-input" value="{{ old('nilai_kedisiplinan') }}" required min="0" max="100" style="padding: 0.3rem 0.5rem;"></td>
                                        <td><span class="nilai-huruf text-secondary" style="font-style: italic;">-</span></td>
                                    </tr>
                                    <tr>
                                        <td>Prestasi Kerja</td>
                                        <td><input type="number" name="nilai_prestasi_kerja" class="form-control nilai-input" value="{{ old('nilai_prestasi_kerja') }}" required min="0" max="100" style="padding: 0.3rem 0.5rem;"></td>
                                        <td><span class="nilai-huruf text-secondary" style="font-style: italic;">-</span></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr style="background: #F8FAFC; font-weight: 700;">
                                        <td class="text-end">JUMLAH</td>
                                        <td id="total_angka">0</td>
                                        <td id="total_huruf" style="font-style: italic;">-</td>
                                    </tr>
                                    <tr style="background: #F0FDF4; font-weight: 700; color: #059669;">
                                        <td class="text-end">NILAI RATA-RATA</td>
                                        <td id="rata_angka">0</td>
                                        <td id="rata_huruf" style="font-style: italic;">-</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="d-flex justify-end mt-4">
                            <button type="submit" class="btn btn-primary" style="background: #0F766E; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                                <i class="ph ph-file-pdf"></i> Generate Sertifikat
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userIdSelect = document.getElementById('user_id');
        const previewBox = document.getElementById('data-peserta-preview');
        
        // Fetch User Data
        userIdSelect.addEventListener('change', function() {
            const userId = this.value;
            if (userId) {
                fetch(`/admin/sertifikat/peserta/${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('preview_name').textContent = data.name || '-';
                        document.getElementById('preview_ttl').textContent = data.tempat_tgl_lahir || '-';
                        document.getElementById('preview_jk').textContent = data.jenis_kelamin || '-';
                        document.getElementById('preview_sekolah').textContent = data.sekolah_universitas || '-';
                        document.getElementById('preview_jurusan').textContent = data.jurusan || '-';
                        
                        let tglMulai = data.tgl_mulai ? new Date(data.tgl_mulai).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}) : '-';
                        let tglSelesai = data.tgl_selesai ? new Date(data.tgl_selesai).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}) : '-';
                        document.getElementById('preview_periode').textContent = `${tglMulai} s.d ${tglSelesai}`;
                        
                        previewBox.style.display = 'block';
                    });
            } else {
                previewBox.style.display = 'none';
            }
        });

        // Trigger change if already selected (e.g. validation fail back)
        if (userIdSelect.value) {
            userIdSelect.dispatchEvent(new Event('change'));
        }

        // Terbilang Function
        function terbilang(angka) {
            angka = Math.floor(Math.abs(angka));
            var huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
            var temp = "";

            if (angka < 12) {
                temp = " " + huruf[angka];
            } else if (angka < 20) {
                temp = terbilang(angka - 10) + " Belas";
            } else if (angka < 100) {
                temp = terbilang(Math.floor(angka / 10)) + " Puluh" + terbilang(angka % 10);
            } else if (angka < 200) {
                temp = " Seratus" + terbilang(angka - 100);
            } else if (angka < 1000) {
                temp = terbilang(Math.floor(angka / 100)) + " Ratus" + terbilang(angka % 100);
            } else {
                temp = angka.toString();
            }

            return temp.trim();
        }

        // Hitung Nilai
        const inputs = document.querySelectorAll('.nilai-input');
        inputs.forEach(input => {
            input.addEventListener('input', calculateNilai);
        });

        function calculateNilai() {
            let total = 0;
            let count = 0;
            
            inputs.forEach(input => {
                let val = parseFloat(input.value) || 0;
                total += val;
                
                // Update row huruf
                let hurufSpan = input.closest('tr').querySelector('.nilai-huruf');
                if (input.value !== '') {
                    hurufSpan.textContent = terbilang(val);
                    count++;
                } else {
                    hurufSpan.textContent = '-';
                }
            });

            // Update footer
            document.getElementById('total_angka').textContent = total;
            document.getElementById('total_huruf').textContent = total > 0 ? terbilang(total) : '-';
            
            if (count > 0) {
                let rata = total / inputs.length; // selalu dibagi 5
                document.getElementById('rata_angka').textContent = rata.toFixed(2).replace('.', ',');
                document.getElementById('rata_huruf').textContent = terbilang(rata);
            } else {
                document.getElementById('rata_angka').textContent = '0';
                document.getElementById('rata_huruf').textContent = '-';
            }
        }

        // Initial calculate
        calculateNilai();
    });
</script>
@endpush
