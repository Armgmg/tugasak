<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Sampah - EcoRewards</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@4.11.0/dist/tf.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/@teachablemachine/image@latest/dist/teachablemachine-image.min.js"></script>
</head>

<body class="bg-gray-50 dark:bg-gray-900">
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900">
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <div
                class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-8 py-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">📸 Scan Sampah AI</h2>
                    <p class="text-gray-600 dark:text-gray-400">Ambil foto sampahmu, dapatkan poin, dan bantu
                        lingkungan!</p>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 overflow-auto">
                <div class="p-8 space-y-6">

                    <form action="{{ route('user.scan.store') }}" method="POST" enctype="multipart/form-data"
                        id="scanForm" class="space-y-6">
                        @csrf

                        <!-- Image Upload Section -->
                        <div class="border-2 border-dashed border-slate-600 rounded-xl p-8 hover:border-teal-500 transition"
                            id="dropZone">
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto text-white mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <p class="text-white font-medium mb-2">Drag & drop foto sampah di sini</p>
                                <p class="text-white text-sm mb-4">atau</p>
                                <label
                                    class="px-6 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg cursor-pointer transition inline-block">
                                    Pilih Foto
                                    <input type="file" name="image" id="imageInput" accept="image/*" class="hidden">
                                </label>

                                <button id="useCameraBtn" type="button" onclick="startCamera()"
                                    class="ml-3 px-4 py-2 border border-teal-500 text-white hover:text-gray-200 rounded-lg hover:bg-teal-900/30 transition">
                                    Gunakan Kamera
                                </button>


                                <p class="text-white text-xs mt-4">JPG, PNG, GIF (max 5MB)</p>

                                <!-- Debug status for camera (temporary) -->
                                <div id="cameraStatus" class="mt-3 text-sm text-white font-medium">Memeriksa status
                                    kamera...</div>
                            </div>
                        </div>

                        <div id="cameraContainer" class="mt-4 hidden">
                            <video id="cameraVideo" autoplay playsinline
                                class="w-full rounded-lg mb-4 bg-black"></video>
                            <div class="flex gap-3">
                                <button id="captureBtn" type="button" onclick="captureFromCamera()"
                                    class="px-4 py-2 bg-teal-500 text-white rounded-lg">Ambil Foto</button>
                                <button id="stopCameraBtn" type="button" onclick="stopCamera()"
                                    class="px-4 py-2 border rounded-lg dark:text-white text-gray-700">Stop
                                    Kamera</button>
                            </div>
                        </div>

                        <!-- Image Preview -->
                        <div id="imagePreview" class="hidden">
                            <div class="bg-slate-700 rounded-xl overflow-hidden">
                                <img id="previewImg" src="" alt="Preview" class="w-full h-96 object-cover">
                            </div>
                            <div class="flex gap-2 mt-4">
                                <button type="button" id="removeImageBtn"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
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
                            <p class="text-white text-sm">
                                💡 <strong>Tips:</strong> Ambil foto dengan pencahayaan yang baik dan sampah terlihat
                                jelas untuk hasil deteksi AI yang lebih akurat.
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex gap-4">
                            <button type="submit"
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white font-bold rounded-lg transition shadow-lg"
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
                    const modelURL = "/model/model.json";
                    const metadataURL = "/model/metadata.json";

                    try {
                        aiModel = await tmImage.load(modelURL, metadataURL);
                        maxPredictions = aiModel.getTotalClasses();
                        console.log("Teachable Machine Model loaded!");
                        aiLabels = aiModel.getClassLabels();

                        // Update status if element exists
                        const statusEl = document.getElementById('cameraStatus');
                        if (statusEl) statusEl.textContent = 'Model AI Siap Digunakan';
                    } catch (error) {
                        console.error("Gagal memuat model:", error);
                        const statusEl = document.getElementById('cameraStatus');
                        if (statusEl) statusEl.textContent = 'Gagal memuat Model AI (Cek console)';
                    }
                }

                function getTopK(preds, labels, k = 1) {
                    const arr = Array.from(preds).map((v, i) => ({ label: labels[i] || i, score: v }));
                    arr.sort((a, b) => b.score - a.score);
                    return arr.slice(0, k);
                }

                async function analyzeImageDataUrl(dataUrl) {
                    if (!aiModel) return null;
                    return new Promise(async (resolve, reject) => {
                        try {
                            const img = new Image();
                            img.src = dataUrl;
                            img.onload = async () => {
                                try {
                                    const prediction = await aiModel.predict(img);
                                    let maxProb = 0;
                                    let bestClass = "unknown";

                                    for (let i = 0; i < maxPredictions; i++) {
                                        if (prediction[i].probability > maxProb) {
                                            maxProb = prediction[i].probability;
                                            bestClass = prediction[i].className;
                                        }
                                    }

                                    // Labels mapping
                                    const labelMap = {
                                        'Botol': 'Botol Plastik',
                                        'Kertas': 'Kertas/Karton',
                                        'Aluminium': 'Aluminium',
                                        'Besi & Logam': 'Logam'
                                    };

                                    const displayLabel = labelMap[bestClass] || bestClass;
                                    console.log("AI Prediction:", bestClass, " -> ", displayLabel, " Confidence:", maxProb);

                                    resolve({ label: displayLabel, confidence: maxProb });
                                } catch (e) { reject(e); }
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

                // Helper to convert base64 to blob/file
                function dataURLtoFile(dataurl, filename) {
                    var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
                        bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
                    while (n--) {
                        u8arr[n] = bstr.charCodeAt(n);
                    }
                    return new File([u8arr], filename, { type: mime });
                }

                // Hook into form submit: run AI analysis if possible, populate hidden inputs, then submit
                document.getElementById('scanForm').addEventListener('submit', async (e) => {
                    e.preventDefault();

                    const submitBtn = document.getElementById('submitBtn');
                    const fileInput = document.getElementById('imageInput');

                    // Validation: Check if image exists
                    if (fileInput.files.length === 0 && (!previewImg.src || !previewImg.src.startsWith('data:'))) {
                        alert('Silakan ambil foto atau upload gambar terlebih dahulu.');
                        return;
                    }

                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Memproses...';

                    // Handle camera image (base64) -> File Input
                    if (fileInput.files.length === 0 && previewImg.src && previewImg.src.startsWith('data:')) {
                        try {
                            const file = dataURLtoFile(previewImg.src, 'camera_capture.jpg');
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            fileInput.files = dataTransfer.files;
                        } catch (err) {
                            console.error('Error converting camera image:', err);
                            alert('Gagal memproses gambar dari kamera.');
                            submitBtn.disabled = false;
                            submitBtn.textContent = 'Kirim Scan untuk Dikonfirmasi';
                            return;
                        }
                    }

                    const file = imageInput.files[0];
                    let dataUrl = '';

                    if (file) {
                        dataUrl = await new Promise(res => {
                            const reader = new FileReader();
                            reader.onload = (ev) => res(ev.target.result);
                            reader.readAsDataURL(file);
                        });
                    }

                    if (dataUrl && aiModel) {
                        try {
                            const res = await analyzeImageDataUrl(dataUrl);
                            if (res) {
                                // Store with 'name' key and percentage confidence for compatibility with show view
                                const confPercent = (res.confidence * 100).toFixed(1);
                                document.getElementById('detectedItemsInput').value = JSON.stringify([{
                                    name: res.label,
                                    confidence: parseFloat(confPercent)
                                }]);
                                document.getElementById('aiConfidenceInput').value = Math.round(res.confidence * 100);
                            }
                        } catch (err) {
                            console.error('AI analysis error', err);
                        }
                    }

                    // Submit form finally
                    document.getElementById('scanForm').submit();
                });

                // Initialize AI model and camera status on page load
                document.addEventListener('DOMContentLoaded', () => {
                    loadAIModel();
                    try {
                        const statusEl = document.getElementById('cameraStatus');
                        statusEl.textContent = typeof startCamera === 'function' ? 'Fungsi kamera sudah dimuat' : 'Fungsi kamera TIDAK ditemukan';
                    } catch (e) { console.warn('cameraStatus element not found'); }
                });

                // Enhance startCamera to update status element
                const _startCamera = startCamera;
                startCamera = async function () {
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