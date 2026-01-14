<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Sampah - EcoRewards</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@4.11.0/dist/tf.min.js"></script>
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900">
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-8 py-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">📸 Scan Sampah AI</h2>
                    <p class="text-gray-600 dark:text-gray-400">Ambil foto sampahmu, dapatkan poin, dan bantu lingkungan!</p>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 overflow-auto">
                <div class="p-8 space-y-6">

        <form action="{{ route('user.scan.store') }}" method="POST" enctype="multipart/form-data" id="scanForm" class="space-y-6">
            @csrf

            <!-- Image Upload Section -->
            <div class="border-2 border-dashed border-slate-600 rounded-xl p-8 hover:border-teal-500 transition"
                 id="dropZone">
                <div class="text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gray-400 mb-2">Drag & drop foto sampah di sini</p>
                    <p class="text-gray-500 text-sm mb-4">atau</p>
                    <label class="px-6 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg cursor-pointer transition inline-block">
                        Pilih Foto
                        <input type="file" name="image" id="imageInput" accept="image/*" class="hidden" required>
                    </label>

                    <button id="useCameraBtn" type="button" onclick="startCamera()" class="ml-3 px-4 py-2 border border-teal-500 text-teal-500 rounded-lg hover:bg-teal-50 transition">
                        Gunakan Kamera
                    </button>

                    <div id="cameraContainer" class="mt-4 hidden">
                        <video id="cameraVideo" autoplay playsinline class="w-full rounded-lg mb-4 bg-black"></video>
                        <div class="flex gap-3">
                            <button id="captureBtn" type="button" onclick="captureFromCamera()" class="px-4 py-2 bg-teal-500 text-white rounded-lg">Ambil Foto</button>
                            <button id="stopCameraBtn" type="button" onclick="stopCamera()" class="px-4 py-2 border rounded-lg">Stop Kamera</button>
                        </div>
                    </div>

                    <p class="text-gray-500 text-xs mt-4">JPG, PNG, GIF (max 5MB)</p>

                    <!-- Debug status for camera (temporary) -->
                    <div id="cameraStatus" class="mt-3 text-sm text-yellow-300">Memeriksa status kamera...</div>
                </div>
            </div>

            <!-- Image Preview -->
            <div id="imagePreview" class="hidden">
                <div class="bg-slate-700 rounded-xl overflow-hidden">
                    <img id="previewImg" src="" alt="Preview" class="w-full h-96 object-cover">
                </div>
                <div class="flex gap-2 mt-4">
                    <button type="button" id="removeImageBtn" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                        Ubah Foto
                    </button>
                </div>
            </div>

            @error('image')
                <div class="text-red-400 text-sm bg-red-500/20 border border-red-500 rounded-lg p-3">
                    {{ $message }}
                </div>
            @enderror

            <!-- AI Detection Results (Hidden Input) -->
            <input type="hidden" name="detected_items" id="detectedItemsInput" value="">
            <input type="hidden" name="ai_confidence" id="aiConfidenceInput" value="">

            <!-- Info Box -->
            <div class="bg-blue-900/20 border border-blue-500 rounded-lg p-4">
                <p class="text-blue-300 text-sm">
                    💡 <strong>Tips:</strong> Ambil foto dengan pencahayaan yang baik dan sampah terlihat jelas untuk hasil deteksi AI yang lebih akurat.
                </p>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-4">
                <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white font-bold rounded-lg transition shadow-lg"
                        id="submitBtn" disabled>
                    Kirim Scan untuk Dikonfirmasi
                </button>
            </div>
        </form>

        <!-- Recent Scans Info -->
        <div class="mt-12 pt-8 border-t border-slate-700">
            <h3 class="text-lg font-bold text-white mb-4">📊 Status Scan Terbaru</h3>
            <div class="grid grid-cols-3 gap-4">
                @php
                    $pendingCount = auth()->user()->scans()->where('status', 'pending')->count();
                    $approvedCount = auth()->user()->scans()->where('status', 'approved')->count();
                    $rejectedCount = auth()->user()->scans()->where('status', 'rejected')->count();
                @endphp

                <div class="bg-slate-700 rounded-lg p-4">
                    <p class="text-gray-400 text-sm">Menunggu Konfirmasi</p>
                    <p class="text-2xl font-bold text-yellow-400">{{ $pendingCount }}</p>
                </div>
                <div class="bg-slate-700 rounded-lg p-4">
                    <p class="text-gray-400 text-sm">Disetujui</p>
                    <p class="text-2xl font-bold text-green-400">{{ $approvedCount }}</p>
                </div>
                <div class="bg-slate-700 rounded-lg p-4">
                    <p class="text-gray-400 text-sm">Ditolak</p>
                    <p class="text-2xl font-bold text-red-400">{{ $rejectedCount }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const dropZone = document.getElementById('dropZone');
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const removeImageBtn = document.getElementById('removeImageBtn');
    const submitBtn = document.getElementById('submitBtn');

    // Drag and drop
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-teal-500', 'bg-slate-700');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-teal-500', 'bg-slate-700');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-teal-500', 'bg-slate-700');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            imageInput.files = files;
            previewImage();
        }
    });

    // File input change
    imageInput.addEventListener('change', previewImage);

    function previewImage() {
        const file = imageInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('hidden');
                dropZone.classList.add('hidden');
                submitBtn.disabled = false;
            };
            reader.readAsDataURL(file);
        }
    }

    // Remove image
    removeImageBtn.addEventListener('click', () => {
        imageInput.value = '';
        imagePreview.classList.add('hidden');
        dropZone.classList.remove('hidden');
        submitBtn.disabled = true;
    });

    // AI and camera integration
    let aiModel = null;
    let aiLabels = [];
    const DEFAULT_SIZE = 224;
    let cameraStream = null;

    async function loadAIModel() {
        const candidates = ['/models/UiPePlLlB/model.json', '/model/model.json'];
        const labelCandidates = ['/models/UiPePlLlB/labels.txt', '/model/labels.txt'];

        for (const url of candidates) {
            try {
                const resp = await fetch(url, { method: 'GET' });
                if (!resp.ok) continue;
                try { aiModel = await tf.loadGraphModel(url); } catch (e) {
                    try { aiModel = await tf.loadLayersModel(url); } catch (e2) { aiModel = null; }
                }
                if (aiModel) {
                    for (const lurl of labelCandidates) {
                        try {
                            const lresp = await fetch(lurl);
                            if (!lresp.ok) continue;
                            const txt = await lresp.text();
                            aiLabels = txt.trim().split('\n').map(s=>s.trim());
                            break;
                        } catch (el) { continue; }
                    }
                    console.log('AI model loaded from', url, 'labels:', aiLabels);
                    return;
                }
            } catch (e) { continue; }
        }
        console.warn('AI model not found. Predictions will be skipped.');
    }

    function getTopK(preds, labels, k=1) {
        const arr = Array.from(preds).map((v,i)=>({label: labels[i]||i, score: v}));
        arr.sort((a,b)=>b.score - a.score);
        return arr.slice(0,k);
    }

    async function analyzeImageDataUrl(dataUrl) {
        if (!aiModel) return null;
        return new Promise(async (resolve, reject) => {
            try {
                const img = new Image();
                img.src = dataUrl;
                img.onload = async ()=>{
                    const tensor = tf.browser.fromPixels(img).resizeNearestNeighbor([DEFAULT_SIZE, DEFAULT_SIZE]).toFloat();
                    const input = tensor.div(127.5).sub(1).expandDims(0);
                    let output = aiModel.predict(input);
                    let data;
                    if (Array.isArray(output)) data = output[0].dataSync();
                    else if (output.dataSync) data = output.dataSync();
                    else data = output.arraySync()[0];
                    const top = getTopK(data, aiLabels, 1)[0] || {label: 'unknown', score: 0};
                    tf.dispose([tensor, input, output]);
                    resolve({ label: top.label, confidence: top.score });
                };
                img.onerror = (e) => reject(e);
            } catch (err) { reject(err); }
        });
    }

    // Camera functions
    async function startCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('Kamera tidak didukung di browser ini');
            return;
        }
        try {
            cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' }, audio: false });
            const video = document.getElementById('cameraVideo');
            video.srcObject = cameraStream;
            document.getElementById('cameraContainer').classList.remove('hidden');
            document.getElementById('dropZone').classList.add('hidden');
        } catch (err) {
            console.error('Camera error', err);
            alert('Tidak dapat mengakses kamera: ' + err.message);
        }
    }

    function stopCamera() {
        if (cameraStream) {
            cameraStream.getTracks().forEach(t => t.stop());
            cameraStream = null;
        }
        const video = document.getElementById('cameraVideo');
        if (video) video.srcObject = null;
        const cam = document.getElementById('cameraContainer');
        if (cam) cam.classList.add('hidden');
        document.getElementById('dropZone').classList.remove('hidden');
    }

    function captureFromCamera() {
        const video = document.getElementById('cameraVideo');
        if (!video || !video.srcObject) { alert('Kamera tidak aktif'); return; }
        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth || DEFAULT_SIZE;
        canvas.height = video.videoHeight || DEFAULT_SIZE;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const dataUrl = canvas.toDataURL('image/png');
        previewImg.src = dataUrl;
        imagePreview.classList.remove('hidden');
        dropZone.classList.add('hidden');
        submitBtn.disabled = false;
        stopCamera();
    }

    // Hook into form submit: run AI analysis if possible, populate hidden inputs, then submit
    document.getElementById('scanForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        submitBtn.disabled = true;
        const file = imageInput.files[0];
        let dataUrl = '';

        if (file) {
            dataUrl = await new Promise(res => {
                const reader = new FileReader();
                reader.onload = (ev)=> res(ev.target.result);
                reader.readAsDataURL(file);
            });
        } else if (previewImg.src) {
            dataUrl = previewImg.src;
        }

        if (dataUrl && aiModel) {
            try {
                const res = await analyzeImageDataUrl(dataUrl);
                if (res) {
                    document.getElementById('detectedItemsInput').value = JSON.stringify([{label: res.label, confidence: res.confidence}]);
                    const confPercent = Math.round(res.confidence * 100);
                    document.getElementById('aiConfidenceInput').value = confPercent;
                }
            } catch (err) {
                console.error('AI analysis error', err);
            }
        }

        // Submit form finally
        document.getElementById('scanForm').submit();
    });

    // Initialize AI model and camera status on page load
    document.addEventListener('DOMContentLoaded', ()=>{ 
        loadAIModel();
        try {
            const statusEl = document.getElementById('cameraStatus');
            statusEl.textContent = typeof startCamera === 'function' ? 'Fungsi kamera sudah dimuat' : 'Fungsi kamera TIDAK ditemukan';
        } catch (e) { console.warn('cameraStatus element not found'); }
    });

    // Enhance startCamera to update status element
    const _startCamera = startCamera;
    startCamera = async function() {
        const statusEl = document.getElementById('cameraStatus');
        try {
            if (statusEl) statusEl.textContent = 'Meminta akses kamera...';
            await _startCamera();
            if (statusEl) statusEl.textContent = 'Kamera aktif';
        } catch (err) {
            console.error('startCamera error', err);
            if (statusEl) statusEl.textContent = 'Gagal mengakses kamera: ' + (err.message || err);
            alert('Gagal mengakses kamera: ' + (err.message || err));
        }
    };
</script>

<style>
    #dropZone {
        cursor: pointer;
    }
</style>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
