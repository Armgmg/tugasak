<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Sampah AI - EcoRewards</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@4.11.0/dist/tf.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/@teachablemachine/image@latest/dist/teachablemachine-image.min.js"></script>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        .animate-slide-in-up {
            animation: slideInUp 0.6s ease-out;
        }

        .dashed-border {
            border: 2px dashed #0d9488;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .dashed-border.dragover {
            background-color: rgba(13, 148, 136, 0.1);
            border-color: #0f766e;
        }

        .pulse-animation {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }
    </style>
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
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Scan AI</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola sampahmu, selamatkan bumi.</p>
                </div>
                <div class="flex items-center gap-6">
                    <div
                        class="flex items-center gap-2 bg-gradient-to-r from-yellow-100 to-amber-100 dark:from-yellow-900/20 dark:to-amber-900/20 px-4 py-2 rounded-lg">
                        <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                            </path>
                        </svg>
                        <span class="font-bold text-gray-900 dark:text-white" id="scanPoints">2450 Poin</span>
                    </div>
                    <div class="relative">
                        <button onclick="toggleNotifications()"
                            class="relative p-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                            <span id="notificationDot"
                                class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full hidden"></span>
                        </button>

                        <!-- Floating Notification Bar (Dropdown) -->
                        <div id="notificationDropdown"
                            class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 z-50 overflow-hidden">
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                <h3 class="font-semibold text-gray-900 dark:text-white">Notifikasi</h3>
                                <button onclick="markAllRead()" class="text-xs text-teal-600 hover:text-teal-700">Tandai
                                    dibaca</button>
                            </div>
                            <div id="dropdownList" class="max-h-96 overflow-y-auto">
                                <!-- Items will be injected here -->
                                <div class="p-4 text-center text-gray-500 text-sm">Memuat...</div>
                            </div>
                            <div
                                class="px-4 py-2 bg-gray-50 dark:bg-gray-700/50 text-center border-t border-gray-100 dark:border-gray-700">
                                <a href="{{ route('notifications.index') }}"
                                    class="text-xs text-gray-600 dark:text-gray-400 hover:text-teal-600">Lihat Semua</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto">
                <div class="p-8">
                    <!-- Title Section -->
                    <div class="mb-8 animate-fade-in">
                        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Identifikasi Sampah AI</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">Upload foto sampah untuk dianalisis jenis dan
                            nilai poinnya secara otomatis.</p>
                    </div>

                    <!-- Main Container -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Upload Section -->
                        <div class="animate-slide-in-up">
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-8">
                                <!-- Upload Area -->
                                <div id="uploadArea"
                                    class="dashed-border p-12 flex flex-col items-center justify-center text-center bg-teal-50/50 dark:bg-teal-900/10 cursor-pointer hover:bg-teal-100/50 dark:hover:bg-teal-900/20 transition">
                                    <svg class="w-16 h-16 text-teal-600 mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Upload atau Ambil
                                        Foto</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-6">Drag & drop foto di sini, atau klik
                                        tombol di bawah untuk membuka kamera/galeri.</p>
                                    <input type="file" id="fileInput" accept="image/*" class="hidden">
                                    <button onclick="document.getElementById('fileInput').click()"
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Pilih Foto
                                    </button>

                                    <button id="useCameraBtn" type="button" onclick="startCamera()"
                                        class="inline-flex items-center gap-2 px-4 py-2 ml-3 border border-teal-500 text-teal-500 rounded-lg hover:bg-teal-50 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 10l4.553-2.276A2 2 0 0122 9.618V17a2 2 0 01-2 2H4a2 2 0 01-2-2V9.618a2 2 0 012.447-1.894L9 10V6a2 2 0 012-2h2a2 2 0 012 2v4z" />
                                        </svg>
                                        Gunakan Kamera
                                    </button>

                                    <div id="cameraContainer" class="mt-4 hidden">
                                        <video id="cameraVideo" autoplay playsinline
                                            class="w-full rounded-lg mb-4 bg-black" style="min-height: 300px;"></video>
                                        <div class="flex gap-3">
                                            <button id="captureBtn" onclick="captureFromCamera()"
                                                class="px-4 py-2 bg-teal-500 text-white rounded-lg">Ambil Foto</button>
                                            <button id="stopCameraBtn" onclick="stopCamera()"
                                                class="px-4 py-2 border rounded-lg">Stop Kamera</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Preview -->
                                <div id="previewContainer" class="hidden mt-6">
                                    <img id="previewImage" src="" alt="Preview" class="w-full rounded-lg mb-4">
                                    <div class="flex gap-3">
                                        <button onclick="resetUpload()"
                                            class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition font-semibold">
                                            Ubah Foto
                                        </button>
                                        <button onclick="analyzeImage()"
                                            class="flex-1 px-4 py-2 bg-gradient-to-r from-teal-500 to-teal-600 text-white rounded-lg hover:from-teal-600 hover:to-teal-700 transition font-semibold">
                                            Analisis
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Analysis Result Section -->
                        <div class="animate-slide-in-up">
                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-8 sticky top-8">
                                <h2
                                    class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                                    <svg class="w-6 h-6 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                        <path fill-rule="evenodd"
                                            d="M4 5a2 2 0 012-2 1 1 0 000 2H6a6 6 0 016 6v3a3 3 0 01-3 3H6a1 1 0 000 2h2a5 5 0 005-5v-3a7 7 0 00-7-7H6a1 1 0 000-2 2 2 0 00-2 2v12a1 1 0 102 0V5z">
                                        </path>
                                    </svg>
                                    Hasil Analisis
                                </h2>

                                <div id="resultContainer" class="hidden">
                                    <!-- Waste Type -->
                                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">Jenis Sampah</p>
                                        <p id="resultType" class="text-2xl font-bold text-gray-900 dark:text-white">-
                                        </p>
                                    </div>

                                    <!-- Weight -->
                                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">Berat Sampah</p>
                                        <p id="resultWeight" class="text-2xl font-bold text-gray-900 dark:text-white">-
                                        </p>
                                    </div>

                                    <!-- Points -->
                                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">Poin yang Didapat</p>
                                        <p id="resultPoints"
                                            class="text-3xl font-bold text-teal-600 dark:text-teal-400">-</p>
                                    </div>

                                    <!-- Carbon Saved -->
                                    <div class="mb-8">
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">Jejak Karbon Dicegah
                                        </p>
                                        <p id="resultCarbon"
                                            class="text-xl font-bold text-green-600 dark:text-green-400">-</p>
                                    </div>

                                    <!-- Submit Button -->
                                    <button onclick="submitAnalysis()"
                                        class="w-full px-4 py-3 bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Simpan Hasil Analisis
                                    </button>
                                </div>

                                <div id="noResultContainer" class="text-center py-12">
                                    <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <p class="text-gray-600 dark:text-gray-400">Hasil analisis akan muncul di sini</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Upload foto sampah di panel
                                        sebelah kiri untuk melihat detail jenis, berat, dan poin.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Global state
        let currentFile = null;
        let aiResultData = null;

        // Helper to convert base64 to blob/file
        function dataURLtoFile(dataurl, filename) {
            var arr = dataurl.split(','),
                mime = arr[0].match(/:(.*?);/)[1],
                bstr = atob(arr[1]),
                n = bstr.length,
                u8arr = new Uint8Array(n);
            while (n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new File([u8arr], filename, {
                type: mime
            });
        }

        // Update poin display from localStorage (Only for header)
        function updateScanPointsDisplay() {
            const scanPoints = parseInt(localStorage.getItem('userPoints')) || 2450;
            const scanPointsEl = document.getElementById('scanPoints');
            if (scanPointsEl) {
                scanPointsEl.textContent = scanPoints.toLocaleString('id-ID') + ' Poin';
            }
        }

        document.addEventListener('DOMContentLoaded', updateScanPointsDisplay);
        updateScanPointsDisplay();

        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');
        const previewContainer = document.getElementById('previewContainer');
        const previewImage = document.getElementById('previewImage');
        const resultContainer = document.getElementById('resultContainer');
        const noResultContainer = document.getElementById('noResultContainer');

        // Drag and drop
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect();
            }
        });

        fileInput.addEventListener('change', handleFileSelect);

        function handleFileSelect() {
            const file = fileInput.files[0];
            if (file) {
                currentFile = file; // Store for submission
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImage.src = e.target.result;
                    uploadArea.style.display = 'none';
                    previewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function resetUpload() {
            fileInput.value = '';
            currentFile = null;
            aiResultData = null;
            uploadArea.style.display = 'flex';
            previewContainer.classList.add('hidden');
            resultContainer.classList.add('hidden');
            noResultContainer.classList.remove('hidden');
            stopCamera();
        }

        // CAMERA FUNCTIONS
        let cameraStream = null;

        async function startCamera() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                alert('Kamera tidak didukung di browser ini');
                return;
            }
            try {
                cameraStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'environment'
                    },
                    audio: false
                });
                const video = document.getElementById('cameraVideo');
                video.srcObject = cameraStream;

                // CRITICAL: Explicitly play the video
                video.onloadedmetadata = () => {
                    video.play().catch(err => {
                        console.error('Video play error:', err);
                        alert('Gagal memutar video kamera. Coba refresh halaman.');
                    });
                };

                document.getElementById('cameraContainer').classList.remove('hidden');
                document.getElementById('uploadArea').classList.add('hidden');
            } catch (err) {
                console.error('Camera error', err);
                let msg = 'Tidak dapat mengakses kamera: ' + err.message;
                if (err.name === 'NotAllowedError') {
                    msg = 'Akses kamera ditolak. Klik ikon gembok di address bar → Site settings → Camera → Allow';
                }
                alert(msg);
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
            if (!previewImage.src || previewImage.src === '') {
                document.getElementById('uploadArea').classList.remove('hidden');
            }
        }

        function captureFromCamera() {
            const video = document.getElementById('cameraVideo');
            if (!video || !video.srcObject) {
                alert('Kamera tidak aktif');
                return;
            }
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth || 224;
            canvas.height = video.videoHeight || 224;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const dataUrl = canvas.toDataURL('image/png');

            previewImage.src = dataUrl;
            currentFile = dataURLtoFile(dataUrl, 'camera_capture.png'); // Convert to file

            uploadArea.style.display = 'none';
            previewContainer.classList.remove('hidden');
            stopCamera();
        }

        // AI model variables
        let aiModel = null;
        let aiLabels = [];
        let maxPredictions = 0;

        async function loadAIModel() {
            const modelURL = "/model/model.json";
            const metadataURL = "/model/metadata.json";

            try {
                aiModel = await tmImage.load(modelURL, metadataURL);
                maxPredictions = aiModel.getTotalClasses();
                console.log("Teachable Machine Model loaded!");
                aiLabels = aiModel.getClassLabels();
            } catch (error) {
                console.error("Gagal memuat model:", error);
            }
        }

        async function analyzeImage() {
            const btn = event.target;
            btn.disabled = true;
            btn.textContent = 'Menganalisis...';

            if (!aiModel) {
                await loadAIModel();
                if (!aiModel) {
                    alert("Model AI gagal dimuat. Silakan refresh.");
                    btn.disabled = false;
                    btn.textContent = 'Analisis';
                    return;
                }
            }

            const img = new Image();
            img.src = previewImage.src;
            img.onload = async () => {
                try {
                    const prediction = await aiModel.predict(img);

                    let maxProb = 0;
                    let bestClass = "Tidak diketahui";

                    for (let i = 0; i < maxPredictions; i++) {
                        if (prediction[i].probability > maxProb) {
                            maxProb = prediction[i].probability;
                            bestClass = prediction[i].className;
                        }
                    }

                    const labelMap = {
                        'Botol': 'Botol Plastik',
                        'Kertas': 'Kertas/Karton',
                        'Aluminium': 'Aluminium',
                        'Besi & Logam': 'Logam'
                    };

                    const displayLabel = labelMap[bestClass] || bestClass;

                    // Store result for submission
                    aiResultData = {
                        className: bestClass, // Original label
                        displayName: displayLabel,
                        confidence: maxProb
                    };

                    // ESTIMATION ONLY
                    // We removed the random mock values to avoid user confusion
                    // The backend says "Verified by Admin" for weight
                    document.getElementById('resultType').textContent = displayLabel;
                    document.getElementById('resultWeight').textContent = "Menunggu Admin";
                    document.getElementById('resultPoints').textContent = "Menunggu Admin";
                    document.getElementById('resultCarbon').textContent = "-";

                    resultContainer.classList.remove('hidden');
                    noResultContainer.classList.add('hidden');
                } catch (error) {
                    console.error("Error prediction:", error);
                    alert("Gagal menganalisis gambar.");
                } finally {
                    btn.disabled = false;
                    btn.textContent = 'Analisis';
                }
            };
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadAIModel();
            checkNotifications();
        });

        async function submitAnalysis() {
            if (!currentFile) {
                alert('File foto tidak ditemukan!');
                return;
            }
            if (!aiResultData) {
                alert('Silakan lakukan analisis terlebih dahulu!');
                return;
            }

            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Mengirim...';

            const formData = new FormData();
            formData.append('image', currentFile);

            // Format detected_items to match backend expectation (JSON array)
            const detectedItemsFunc = [{
                name: aiResultData.displayName,
                confidence: (aiResultData.confidence * 100).toFixed(1)
            }];
            formData.append('detected_items', JSON.stringify(detectedItemsFunc));

            // Confidence score (0-100)
            formData.append('ai_confidence', Math.round(aiResultData.confidence * 100));

            try {
                const response = await fetch("{{ route('user.scan.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                if (response.redirected) {
                    window.location.href = response.url;
                    return;
                }

                // If JSON response (e.g. error) or manual handling
                const result = await response.json();

                if (response.ok) {
                    // This part handles if your controller returns JSON instead of redirect
                    alert('Upload berhasil!');
                    window.location.href = "/scan/" + result.data.id; // Fallback redirect
                } else {
                    throw new Error(result.message || 'Gagal mengupload scan');
                }

            } catch (error) {
                console.error('Upload Error:', error);
                alert('Terjadi kesalahan saat menyimpan: ' + error.message);
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }

        // Notification System
        async function checkNotifications() {
            try {
                const res = await fetch("{{ route('notifications.data') }}");
                const data = await res.json();
                const dot = document.getElementById('notificationDot');
                if (dot) {
                    if (data.count > 0) {
                        dot.classList.remove('hidden');
                    } else {
                        dot.classList.add('hidden');
                    }
                }
                return data;
            } catch (e) {
                console.error('Failed to check notifications', e);
                return null;
            }
        }

        async function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('hidden');

            if (!dropdown.classList.contains('hidden')) {
                const list = document.getElementById('dropdownList');
                list.innerHTML = '<div class="p-4 text-center text-gray-500 text-sm">Memuat...</div>';

                const data = await checkNotifications();
                if (data && data.notifications.length > 0) {
                    list.innerHTML = data.notifications.map(n => `
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition cursor-pointer" onclick="markRead('${n.id}')">
                            <div class="flex items-start gap-3">
                                <div class="${n.data.status === 'approved' ? 'text-green-500' : 'text-red-500'} mt-1">
                                    ${n.data.status === 'approved' ? '✅' : '❌'}
                                </div>
                                <div>
                                    <p class="text-sm text-gray-800 dark:text-gray-200">${n.data.message}</p>
                                    <p class="text-xs text-gray-400 mt-1">${new Date(n.created_at).toLocaleString('id-ID')}</p>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    list.innerHTML = '<div class="p-4 text-center text-gray-500 text-sm">Tidak ada notifikasi baru</div>';
                }
            }
        }

        async function markAllRead() {
            await fetch("{{ route('notifications.readAll') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            });
            checkNotifications();
            document.getElementById('dropdownList').innerHTML = '<div class="p-4 text-center text-gray-500 text-sm">Tidak ada notifikasi baru</div>';
        }
    </script>

    <style>
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #4b5563;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
    </style>
</body>

</html>