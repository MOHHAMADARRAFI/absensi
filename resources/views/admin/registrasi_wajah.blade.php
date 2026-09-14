@extends('layouts.app')

@section('title', 'Registrasi Wajah - ' . $peserta->name)

@push('styles')
<style>
    .reg-wrapper {
        background: #F8FAFC;
        min-height: 100vh;
    }
    .reg-header {
        background: linear-gradient(135deg, #1E293B, #334155);
        color: white;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .reg-header a { color: white; font-size: 1.4rem; }
    .reg-header h2 { font-size: 1.1rem; font-weight: 700; }

    .reg-body {
        max-width: 560px;
        margin: 0 auto;
        padding: 1.5rem;
    }

    /* Webcam */
    .webcam-wrapper {
        position: relative;
        background: #0f172a;
        border-radius: 14px;
        overflow: hidden;
        aspect-ratio: 4/3;
        margin-bottom: 1rem;
    }
    #webcam {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transform: scaleX(-1);
    }
    #faceCanvas {
        position: absolute;
        top: 0; left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        transform: scaleX(-1);
    }
    .face-guide {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 170px;
        height: 210px;
        border: 2.5px solid rgba(255,255,255,0.25);
        border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
        pointer-events: none;
        transition: border-color 0.3s, box-shadow 0.3s;
    }
    .face-guide.detected {
        border-color: #22c55e;
        box-shadow: 0 0 0 4px rgba(34,197,94,0.15);
    }
    .face-guide.error {
        border-color: #ef4444;
        box-shadow: 0 0 0 4px rgba(239,68,68,0.15);
    }

    .webcam-overlay {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.65));
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .cam-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: #ef4444;
        animation: blink 1s infinite;
    }
    .cam-dot.on { background: #22c55e; animation: none; }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }

    /* Status */
    .status-card {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        border: 1px solid #E2E8F0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
        font-size: 0.875rem;
    }
    .status-icon {
        width: 38px; height: 38px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .s-loading { background: #EFF6FF; color: #3B82F6; }
    .s-idle { background: #F1F5F9; color: #64748B; }
    .s-success { background: #F0FDF4; color: #22c55e; }
    .s-error { background: #FEF2F2; color: #ef4444; }
    .s-warning { background: #FFFBEB; color: #f59e0b; }

    .status-text strong { display: block; color: #1E293B; font-weight: 600; }
    .status-text small { color: #64748B; font-size: 0.78rem; }

    /* Button */
    #btnAmbilWajah {
        width: 100%;
        padding: 1rem;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
        background: #E2E8F0;
        color: #94A3B8;
    }
    #btnAmbilWajah.ready {
        background: linear-gradient(135deg, #059669, #10B981);
        color: white;
        box-shadow: 0 4px 12px rgba(16,185,129,0.3);
    }
    #btnAmbilWajah.ready:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(16,185,129,0.4);
    }
    #btnAmbilWajah:disabled { cursor: not-allowed; }

    /* Info Tips */
    .tips-list {
        background: #F0FDF4;
        border: 1px solid #BBF7D0;
        border-radius: 10px;
        padding: 0.875rem 1rem;
        margin-bottom: 1rem;
    }
    .tips-list h5 { font-size: 0.8rem; font-weight: 700; color: #166534; margin-bottom: 0.5rem; }
    .tips-list ul { margin: 0; padding-left: 1.25rem; }
    .tips-list li { font-size: 0.8rem; color: #166534; line-height: 1.8; }

    .spin { animation: spin 1s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* Peserta info card */
    .peserta-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        padding: 0.875rem 1rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.875rem;
    }
    .peserta-avatar {
        width: 44px; height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1D4ED8, #3B82F6);
        color: white;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="reg-wrapper">

    {{-- Header --}}
    <div class="reg-header">
        <a href="{{ route('admin.peserta') }}"><i class="ph ph-arrow-left"></i></a>
        <div>
            <h2>Registrasi Wajah</h2>
            <p style="font-size: 0.78rem; opacity: 0.8;">Daftarkan wajah peserta untuk presensi</p>
        </div>
    </div>

    <div class="reg-body">

        {{-- Info Peserta --}}
        <div class="peserta-card">
            <div class="peserta-avatar">{{ substr($peserta->name, 0, 1) }}</div>
            <div>
                <div style="font-weight: 700; color: #1E293B;">{{ $peserta->name }}</div>
                <div style="font-size: 0.78rem; color: #64748B;">{{ $peserta->nis_nim }} — {{ $peserta->sekolah_universitas }}</div>
                <div style="margin-top: 0.25rem;">
                    @if($peserta->face_descriptor)
                        <span style="font-size: 0.72rem; background: #F0FDF4; color: #166534; border: 1px solid #BBF7D0; border-radius: 4px; padding: 2px 6px; font-weight: 600;">
                            Wajah sudah terdaftar — akan diperbarui
                        </span>
                    @else
                        <span style="font-size: 0.72rem; background: #FFFBEB; color: #92400E; border: 1px solid #FCD34D; border-radius: 4px; padding: 2px 6px; font-weight: 600;">
                            Belum terdaftar
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tips --}}
        <div class="tips-list">
            <h5><i class="ph ph-info"></i> Panduan Registrasi</h5>
            <ul>
                <li>Pastikan hanya satu wajah di depan kamera</li>
                <li>Wajah harus terlihat jelas dan menghadap kamera</li>
                <li>Pastikan pencahayaan ruangan cukup</li>
                <li>Jaga jarak 30–60 cm dari kamera</li>
                <li>Lepas masker atau kacamata hitam</li>
            </ul>
        </div>

        {{-- Webcam --}}
        <div class="webcam-wrapper">
            <video id="webcam" autoplay playsinline muted></video>
            <canvas id="faceCanvas"></canvas>
            <div class="face-guide" id="faceGuide"></div>
            <div class="webcam-overlay">
                <div class="cam-dot" id="camDot"></div>
                <span style="color:white; font-size: 0.8rem; font-weight: 600;" id="camLabel">Menyiapkan kamera...</span>
            </div>
        </div>

        {{-- Status --}}
        <div id="statusCard" class="status-card">
            <div class="status-icon s-loading"><i class="ph ph-circle-notch spin"></i></div>
            <div class="status-text">
                <strong>Memuat sistem pengenalan wajah</strong>
                <small>Mohon tunggu sebentar...</small>
            </div>
        </div>

        {{-- Tombol --}}
        <button id="btnAmbilWajah" disabled onclick="doAmbilWajah()">
            <i class="ph ph-lock" id="btnIcon"></i>
            <span id="btnLabel">Menunggu Wajah Terdeteksi</span>
        </button>

    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/face-api.min.js') }}"></script>
<script>
const PESERTA_ID = {{ $peserta->id }};
const MODEL_PATH = '/models';
const DETECTION_INTERVAL_MS = 400;

let stream = null;
let detectionInterval = null;
let modelsLoaded = false;
let faceDetected = false;
let currentDescriptor = null;

const video = document.getElementById('webcam');
const canvas = document.getElementById('faceCanvas');
const faceGuide = document.getElementById('faceGuide');

function setStatus(type, title, subtitle) {
    const icons = {
        loading: '<i class="ph ph-circle-notch spin"></i>',
        idle: '<i class="ph ph-scan"></i>',
        success: '<i class="ph ph-check-circle"></i>',
        error: '<i class="ph ph-x-circle"></i>',
        warning: '<i class="ph ph-warning"></i>',
    };
    document.getElementById('statusCard').innerHTML = `
        <div class="status-icon s-${type}">${icons[type]}</div>
        <div class="status-text">
            <strong>${title}</strong>
            ${subtitle ? `<small>${subtitle}</small>` : ''}
        </div>`;
}

function setButtonReady(ready) {
    const btn = document.getElementById('btnAmbilWajah');
    const icon = document.getElementById('btnIcon');
    const label = document.getElementById('btnLabel');
    if (ready) {
        btn.disabled = false;
        btn.classList.add('ready');
        icon.className = 'ph ph-camera';
        label.textContent = 'Ambil & Simpan Wajah';
    } else {
        btn.disabled = true;
        btn.classList.remove('ready');
        icon.className = 'ph ph-lock';
        label.textContent = 'Menunggu Wajah Terdeteksi';
    }
}

async function startCamera() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: { width: { ideal: 640 }, height: { ideal: 480 }, facingMode: 'user' }
        });
        video.srcObject = stream;
        await video.play();

        document.getElementById('camDot').classList.add('on');
        document.getElementById('camLabel').textContent = 'Kamera Aktif';

        video.addEventListener('loadedmetadata', () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
        });

        await loadModels();
    } catch (err) {
        document.getElementById('camLabel').textContent = 'Kamera tidak dapat diakses';
        if (err.name === 'NotAllowedError') {
            setStatus('error', 'Izin kamera ditolak', 'Berikan izin kamera pada browser, lalu refresh halaman.');
        } else {
            setStatus('error', 'Kamera tidak dapat digunakan', err.message);
        }
    }
}

async function loadModels() {
    setStatus('loading', 'Memuat model pengenalan wajah...', 'Proses ini hanya sekali.');
    try {
        await Promise.all([
            faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_PATH),
            faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_PATH),
            faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_PATH),
        ]);
        modelsLoaded = true;
        setStatus('idle', 'Arahkan wajah ke kamera', 'Pastikan wajah terlihat jelas dan satu orang saja.');
        startDetection();
    } catch (err) {
        setStatus('error', 'Gagal memuat model', 'Pastikan file model ada di folder /public/models. Refresh halaman.');
    }
}

function startDetection() {
    if (detectionInterval) clearInterval(detectionInterval);

    detectionInterval = setInterval(async () => {
        if (!modelsLoaded || !stream) return;

        try {
            const detections = await faceapi
                .detectAllFaces(video, new faceapi.SsdMobilenetv1Options({ minConfidence: 0.5 }))
                .withFaceLandmarks()
                .withFaceDescriptors();

            if (detections.length === 0) {
                faceGuide.className = 'face-guide';
                setStatus('idle', 'Wajah tidak terdeteksi', 'Arahkan wajah ke kamera.');
                setButtonReady(false);
                currentDescriptor = null;
                return;
            }

            if (detections.length > 1) {
                faceGuide.className = 'face-guide error';
                setStatus('error', 'Lebih dari satu wajah', 'Pastikan hanya satu orang di depan kamera.');
                setButtonReady(false);
                currentDescriptor = null;
                return;
            }

            // Tepat 1 wajah — siap diambil
            faceGuide.className = 'face-guide detected';
            setStatus('success', 'Wajah terdeteksi ✓', 'Klik tombol untuk mendaftarkan wajah ini.');
            currentDescriptor = Array.from(detections[0].descriptor);
            setButtonReady(true);

        } catch (err) {
            console.error('Detection error:', err);
        }
    }, DETECTION_INTERVAL_MS);
}

async function doAmbilWajah() {
    if (!currentDescriptor || currentDescriptor.length !== 128) {
        setStatus('error', 'Data wajah tidak valid', 'Pastikan wajah terdeteksi dengan baik, lalu coba lagi.');
        return;
    }

    const btn = document.getElementById('btnAmbilWajah');
    const label = document.getElementById('btnLabel');
    const icon = document.getElementById('btnIcon');

    btn.disabled = true;
    icon.className = 'ph ph-circle-notch spin';
    label.textContent = 'Menyimpan data wajah...';

    try {
        const res = await fetch(`/admin/peserta/${PESERTA_ID}/simpan-wajah`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ face_descriptor: JSON.stringify(currentDescriptor) }),
        });

        const data = await res.json();

        if (data.success) {
            // Hentikan kamera
            if (detectionInterval) clearInterval(detectionInterval);
            if (stream) stream.getTracks().forEach(t => t.stop());

            setStatus('success', '✓ ' + data.message, 'Data wajah berhasil disimpan ke database.');
            faceGuide.className = 'face-guide detected';

            icon.className = 'ph ph-check-circle';
            label.textContent = 'Wajah Berhasil Didaftarkan';
            btn.style.background = 'linear-gradient(135deg, #059669, #10B981)';
            btn.style.color = 'white';

            // Redirect setelah 2.5 detik
            setTimeout(() => {
                window.location.href = '{{ route("admin.peserta") }}';
            }, 2500);
        } else {
            setStatus('error', 'Gagal menyimpan', data.message || 'Terjadi kesalahan, coba lagi.');
            btn.disabled = false;
            btn.classList.add('ready');
            icon.className = 'ph ph-camera';
            label.textContent = 'Coba Lagi';
        }
    } catch (err) {
        console.error('Save error:', err);
        setStatus('error', 'Gagal terhubung ke server', 'Periksa koneksi dan coba lagi.');
        btn.disabled = false;
        btn.classList.add('ready');
        icon.className = 'ph ph-camera';
        label.textContent = 'Coba Lagi';
    }
}

window.addEventListener('load', startCamera);
window.addEventListener('beforeunload', () => {
    if (detectionInterval) clearInterval(detectionInterval);
    if (stream) stream.getTracks().forEach(t => t.stop());
});
</script>
@endpush
