@extends('layouts.app')

@section('title', 'Absen ' . ucfirst($type))

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    .webcam-container {
        width: 100%;
        max-width: 100%;
        height: 300px;
        background: #000;
        border-radius: var(--radius-lg);
        overflow: hidden;
        margin: 0 auto 1.5rem auto;
        position: relative;
        box-shadow: inset 0 0 20px rgba(0,0,0,0.5);
    }
    #webcam {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    #map {
        height: 200px;
        border-radius: var(--radius-lg);
        margin-bottom: 1.5rem;
        z-index: 1;
        border: 1px solid var(--border);
    }
    
    /* Radio Pills */
    .keterangan-selector {
        display: flex;
        gap: 0.5rem;
        background: #F1F5F9;
        padding: 0.35rem;
        border-radius: var(--radius-md);
        margin-bottom: 1.5rem;
    }
    .keterangan-selector label {
        flex: 1;
        text-align: center;
        padding: 0.75rem 0;
        cursor: pointer;
        font-weight: 600;
        color: #64748B;
        border-radius: var(--radius-sm);
        transition: all 0.2s;
    }
    .keterangan-selector input[type="radio"] {
        display: none;
    }
    .keterangan-selector input[type="radio"]:checked + label {
        background: white;
        color: var(--primary);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
</style>
@endpush

@section('content')
<div class="mobile-layout" style="padding-bottom: 2rem;">
    <div class="mobile-header" style="padding-bottom: 1.5rem; border-radius: 0;">
        <div class="d-flex align-center gap-4">
            <a href="{{ route('peserta.dashboard') }}" style="color: white; font-size: 1.5rem; position: relative; z-index: 10;"><i class="ph ph-arrow-left"></i></a>
            <h3 class="font-bold" style="position: relative; z-index: 10;">Presensi {{ ucfirst($type) }}</h3>
        </div>
    </div>

    <div class="mobile-content">
        <div class="card mt-4" style="padding: 2rem 1.5rem;">
            <div class="text-center mb-4">
                <h3 class="font-bold text-dark" style="font-size: 1.5rem;">Form Presensi PKL</h3>
                <p class="text-secondary" style="font-size: 0.875rem;">SIAP PKL Kec. Cikampek</p>
            </div>

            <div class="form-group">
                <label class="form-label">NIS / NIM</label>
                <input type="text" class="form-control" value="{{ Auth::user()->nis_nim }}" readonly style="background-color: #F8FAFC; color: #475569;">
            </div>

            <div class="form-group mb-4">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly style="background-color: #F8FAFC; color: #475569;">
            </div>

            <!-- Keterangan Selector -->
            <label class="form-label">Keterangan</label>
            <div class="keterangan-selector">
                <input type="radio" id="ket_hadir" name="keterangan_type" value="hadir" checked onchange="toggleForm()">
                <label for="ket_hadir">Hadir</label>

                <input type="radio" id="ket_izin" name="keterangan_type" value="izin" onchange="toggleForm()">
                <label for="ket_izin">Izin</label>
                
                <input type="radio" id="ket_sakit" name="keterangan_type" value="sakit" onchange="toggleForm()">
                <label for="ket_sakit">Sakit</label>
            </div>

            <!-- FORM HADIR -->
            <form id="form-hadir" method="POST" action="{{ $type === 'masuk' ? route('absen.masuk') : route('absen.pulang') }}">
                @csrf
                <div class="form-group mt-4 pt-4" style="border-top: 1px dashed var(--border);">
                    <label class="form-label mb-3">Bukti Kehadiran & Lokasi (Wajib)</label>
                    
                    <div id="statusMessage" class="mb-3 text-center font-bold text-secondary" style="font-size: 0.875rem;">
                        Menyiapkan lokasi dan kamera...
                    </div>

                    <div class="webcam-container">
                        <video id="webcam" autoplay playsinline></video>
                    </div>

                    <div id="map"></div>

                    <div class="text-center mb-4">
                        <p class="text-secondary" style="font-size: 0.875rem;">Jarak Anda: <span id="distanceText" class="font-bold">Menghitung...</span></p>
                    </div>
                </div>

                <input type="hidden" name="latitude" id="inputLat">
                <input type="hidden" name="longitude" id="inputLng">
                <input type="hidden" name="jarak" id="inputJarak">
                <input type="hidden" name="foto" id="inputFoto">

                <button type="button" id="btnSubmitAbsen" class="btn btn-primary w-100" style="padding: 1rem; font-size: 1.1rem; border-radius: var(--radius-lg);" disabled onclick="submitAbsen()">
                    <i class="ph ph-paper-plane-tilt"></i>
                    <span>Kirim Presensi Hadir</span>
                </button>
            </form>

            <!-- FORM IZIN/SAKIT -->
            <form id="form-izin-sakit" method="POST" action="{{ route('peserta.submit_izin_sakit') }}" enctype="multipart/form-data" style="display: none;">
                @csrf
                <input type="hidden" name="jenis" id="inputJenis" value="izin">
                <!-- Kita set otomatis tanggal hari ini karena ini absen harian -->
                <input type="hidden" name="tanggal_mulai" value="{{ date('Y-m-d') }}">
                <input type="hidden" name="tanggal_selesai" value="{{ date('Y-m-d') }}">

                <div class="form-group mt-4 pt-4" style="border-top: 1px dashed var(--border);">
                    <label class="form-label">Alasan Singkat</label>
                    <input type="text" name="alasan" class="form-control" required placeholder="Cth: Keperluan keluarga / Sakit demam">
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan Detail (Opsional)</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Jelaskan secara rinci..."></textarea>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Bukti Dokumen (Opsional/Wajib untuk Sakit)</label>
                    <input type="file" name="bukti_dokumen" class="form-control" accept="image/*,.pdf">
                    <small class="text-secondary mt-1 d-block" style="font-size: 0.75rem;">Format: JPG, PNG, PDF. Max 2MB.</small>
                </div>

                <button type="submit" class="btn btn-warning w-100" style="padding: 1rem; font-size: 1.1rem; border-radius: var(--radius-lg); color: white;">
                    <i class="ph ph-paper-plane-tilt"></i>
                    <span>Kirim Pengajuan</span>
                </button>
            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const KANTOR_LAT = {{ $pengaturan->latitude_kantor ?? -6.4025 }};
    const KANTOR_LNG = {{ $pengaturan->longitude_kantor ?? 107.4589 }};
    const MAX_RADIUS = {{ $pengaturan->radius_meter ?? 100 }};
    
    let map, marker, circle;
    let stream;
    let currentJarak = null;
    let isCameraRunning = false;

    function toggleForm() {
        const val = document.querySelector('input[name="keterangan_type"]:checked').value;
        const formHadir = document.getElementById('form-hadir');
        const formIzinSakit = document.getElementById('form-izin-sakit');
        const inputJenis = document.getElementById('inputJenis');
        const btnIzinSakit = formIzinSakit.querySelector('button[type="submit"]');

        if(val === 'hadir') {
            formHadir.style.display = 'block';
            formIzinSakit.style.display = 'none';
            if(!isCameraRunning) initAbsen();
        } else {
            formHadir.style.display = 'none';
            formIzinSakit.style.display = 'block';
            
            // Set inputJenis
            inputJenis.value = val;
            
            // Ubah warna tombol sesuai tipe
            if(val === 'sakit') {
                btnIzinSakit.className = 'btn w-100 btn-danger';
                btnIzinSakit.innerHTML = '<i class="ph ph-first-aid"></i><span>Kirim Pengajuan Sakit</span>';
            } else {
                btnIzinSakit.className = 'btn w-100 btn-warning';
                btnIzinSakit.innerHTML = '<i class="ph ph-envelope-simple"></i><span>Kirim Pengajuan Izin</span>';
            }

            // Stop camera if running to save battery
            if(stream) {
                stream.getTracks().forEach(track => track.stop());
                isCameraRunning = false;
            }
        }
    }

    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371e3; // metres
        const φ1 = lat1 * Math.PI/180;
        const φ2 = lat2 * Math.PI/180;
        const Δφ = (lat2-lat1) * Math.PI/180;
        const Δλ = (lon2-lon1) * Math.PI/180;

        const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
                Math.cos(φ1) * Math.cos(φ2) *
                Math.sin(Δλ/2) * Math.sin(Δλ/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

        return R * c; // in metres
    }

    async function initAbsen() {
        isCameraRunning = true;
        // Start Camera
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } });
            document.getElementById('webcam').srcObject = stream;
        } catch (err) {
            document.getElementById('statusMessage').innerText = "Gagal mengakses kamera: " + err.message;
            document.getElementById('statusMessage').style.color = "var(--danger)";
        }

        // Setup Map
        if(!map) {
            map = L.map('map').setView([KANTOR_LAT, KANTOR_LNG], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
            L.circle([KANTOR_LAT, KANTOR_LNG], {
                color: 'blue',
                fillColor: '#3B82F6',
                fillOpacity: 0.15,
                radius: MAX_RADIUS
            }).addTo(map);
        }

        // Get Location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                document.getElementById('inputLat').value = lat;
                document.getElementById('inputLng').value = lng;

                if(marker) map.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(map);
                map.setView([lat, lng], 17);

                currentJarak = calculateDistance(lat, lng, KANTOR_LAT, KANTOR_LNG);
                document.getElementById('inputJarak').value = Math.round(currentJarak);
                
                let distText = document.getElementById('distanceText');
                distText.innerText = Math.round(currentJarak) + " meter";

                let statusMsg = document.getElementById('statusMessage');
                let btn = document.getElementById('btnSubmitAbsen');

                if (currentJarak <= MAX_RADIUS) {
                    statusMsg.innerText = "Lokasi Valid (Dalam Area)";
                    statusMsg.style.color = "var(--success)";
                    distText.style.color = "var(--success)";
                    btn.disabled = false;
                } else {
                    statusMsg.innerText = "Lokasi Tidak Valid (Di Luar Area)";
                    statusMsg.style.color = "var(--danger)";
                    distText.style.color = "var(--danger)";
                    btn.disabled = true;
                }

            }, (error) => {
                document.getElementById('statusMessage').innerText = "Gagal mendapatkan lokasi.";
                document.getElementById('statusMessage').style.color = "var(--danger)";
            }, { enableHighAccuracy: true });
        } else {
            document.getElementById('statusMessage').innerText = "Geolocation tidak didukung browser ini.";
        }
    }

    function submitAbsen() {
        const video = document.getElementById('webcam');
        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        
        const dataUrl = canvas.toDataURL('image/jpeg');
        document.getElementById('inputFoto').value = dataUrl;

        document.getElementById('btnSubmitAbsen').innerText = 'Menyimpan...';
        document.getElementById('btnSubmitAbsen').disabled = true;
        
        document.getElementById('form-hadir').submit();
    }

    // Initialize when page loads
    window.addEventListener('load', function() {
        if(document.querySelector('input[name="keterangan_type"]:checked').value === 'hadir') {
            initAbsen();
        }
    });

    // Stop camera when leaving page
    window.addEventListener('beforeunload', function() {
        if(stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    });
</script>
@endpush
