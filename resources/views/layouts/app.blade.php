<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-slate-900 text-white">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-800 p-4">
        @auth
            @if(auth()->user()->role === 'admin')
                @include('partials.sidebar-admin')
            @else
                @include('partials.sidebar-user')
            @endif
        @endauth
    </aside>

    <!-- Content -->
    <main class="flex-1 p-6">
        @yield('content')
    </main>

</div>

<!-- Debug banner (visible in local/dev only) -->
@php $env = config('app.env'); @endphp
@if($env === 'local' || $env === 'development' || request()->getHost() === '127.0.0.1')
    <div id="devDebug" style="position:fixed;right:12px;bottom:12px;background:rgba(0,0,0,0.6);color:#fff;padding:8px 12px;border-radius:8px;font-size:13px;z-index:99999;box-shadow:0 6px 18px rgba(0,0,0,0.3);">
        <div id="devDebugStatus">Debug: memeriksa...</div>
        <div style="margin-top:6px;text-align:right"><button id="devDebugBtn" style="background:#10b981;border:none;padding:4px 8px;border-radius:6px;color:#fff;cursor:pointer;font-size:12px">Run startCamera()</button></div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', ()=>{
            const status = document.getElementById('devDebugStatus');
            const btn = document.getElementById('devDebugBtn');
            status.textContent = (typeof startCamera === 'function') ? 'startCamera() tersedia' : 'startCamera() TIDAK tersedia';
            btn.addEventListener('click', ()=>{
                try { if(typeof startCamera === 'function'){ startCamera(); } else { alert('startCamera() tidak tersedia pada halaman ini.'); } }
                catch(err){ alert('Error menjalankan startCamera(): '+err.message); console.error(err); }
            });
        });
    </script>

    {{-- Global camera test modal (local only) --}}
    @if(app()->environment('local') || request()->getHost() === '127.0.0.1')
    <div id="globalCamera" style="position:fixed;left:12px;bottom:12px;z-index:99999">
        <button id="openCameraTest" style="background:#0369a1;color:#fff;padding:8px 10px;border-radius:8px;border:none;font-size:13px;cursor:pointer">Camera Test</button>
    </div>
    <div id="cameraModal" style="display:none;position:fixed;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:100000;align-items:center;justify-content:center;">
        <div style="width:640px;max-width:95%;background:#111827;padding:16px;border-radius:8px;color:#fff;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                <strong>Camera Test</strong>
                <button id="closeCameraModal" style="background:#ef4444;border:none;padding:6px 8px;border-radius:6px;color:#fff;cursor:pointer">Close</button>
            </div>
            <video id="globalCameraVideo" autoplay playsinline style="width:100%;background:#000;border-radius:6px"></video>
            <div style="display:flex;gap:8px;margin-top:8px;justify-content:flex-end;">
                <button id="globalCapture" style="background:#10b981;border:none;padding:8px 10px;border-radius:6px;color:#fff;cursor:pointer">Capture</button>
                <a id="globalDownload" style="display:none;background:#0ea5e9;padding:8px 10px;border-radius:6px;color:#fff;text-decoration:none;">Download</a>
            </div>
        </div>
    </div>
    <script>
        (function(){
            const openBtn = document.getElementById('openCameraTest');
            const modal = document.getElementById('cameraModal');
            const closeBtn = document.getElementById('closeCameraModal');
            const video = document.getElementById('globalCameraVideo');
            const captureBtn = document.getElementById('globalCapture');
            const downloadLink = document.getElementById('globalDownload');
            let stream = null;

            openBtn.addEventListener('click', async ()=>{
                modal.style.display = 'flex';
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' }, audio: false });
                    video.srcObject = stream;
                } catch (err) {
                    alert('Gagal mengakses kamera: ' + (err.message || err));
                    modal.style.display = 'none';
                }
            });

            closeBtn.addEventListener('click', ()=>{
                modal.style.display = 'none';
                if (stream) { stream.getTracks().forEach(t=>t.stop()); stream = null; }
                video.srcObject = null;
                downloadLink.style.display = 'none';
            });

            captureBtn.addEventListener('click', ()=>{
                if (!video || !video.videoWidth) { alert('Kamera belum siap'); return; }
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video,0,0,canvas.width,canvas.height);
                const dataUrl = canvas.toDataURL('image/png');
                downloadLink.href = dataUrl;
                downloadLink.download = 'capture.png';
                downloadLink.style.display = 'inline-block';
                downloadLink.textContent = 'Download Capture';
            });
        })();
    </script>
    @endif

</body>
</html>
