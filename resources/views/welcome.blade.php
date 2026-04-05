<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SmartHome Panel') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-950 text-white">
    <div class="min-h-screen p-6 max-w-7xl mx-auto space-y-6">

        <!-- HEADER -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-semibold">ARZ Smarthome System</h1>
                <p class="text-gray-400">All control is in your hands</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-3">
                <div class="bg-green-500/10 text-green-400 px-4 py-2 rounded-xl text-sm">🟢 All system normal</div>
                <div class="bg-gray-800 px-4 py-2 rounded-xl text-sm">{{ now()->format('H:i') }}</div>
            </div>
        </div>

        <!-- GREETING -->
        @php
            $hour = now()->hour;
            if ($hour >= 5 && $hour < 12) {
                $greeting = 'Good Morning';
                $icon = '🌅';
                $color = 'from-orange-500 to-yellow-500';
            } elseif ($hour >= 12 && $hour < 15) {
                $greeting = 'Good Afternoon';
                $icon = '☀️';
                $color = 'from-yellow-500 to-orange-500';
            } elseif ($hour >= 15 && $hour < 18) {
                $greeting = 'Good Evening';
                $icon = '🌇';
                $color = 'from-orange-600 to-red-500';
            } else {
                $greeting = 'Good Night';
                $icon = '🌙';
                $color = 'from-indigo-600 to-blue-800';
            }
        @endphp
        <div class="bg-gradient-to-r {{ $color }} rounded-2xl p-6 shadow-lg flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold">{{ $greeting }}</h2>
                <p class="text-white/80 mt-1">Hope you have a great day </p>
            </div>
            <div class="text-5xl">{{ $icon }}</div>
        </div>

        <!-- MAIN GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- SUHU & KELEMBAPAN -->
            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div
                    class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-6 shadow-lg hover:scale-[1.02] transition">
                    <div class="flex justify-between">
                        <h3>Temprature</h3><span class="text-blue-200 text-sm">Realtime</span>
                    </div>
                    <div class="mt-6 text-5xl font-bold">
                        <p id="temperature">--</p>
                    </div>
                    <p id="temperature-status" class="text-blue-200 mt-2">--</p>
                    <div class="mt-6 w-full bg-blue-300/30 h-2 rounded-full">
                        <div id="temperature-bar" class="bg-white h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
                <div
                    class="bg-gradient-to-br from-cyan-600 to-teal-600 rounded-2xl p-6 shadow-lg hover:scale-[1.02] transition">
                    <div class="flex justify-between">
                        <h3>Humidity</h3><span class="text-cyan-200 text-sm">Realtime</span>
                    </div>
                    <div class="mt-6 text-5xl font-bold">
                        <p id="humidity">--</p>
                    </div>
                    <p id="humidity-status" class="text-cyan-200 mt-2">--</p>
                    <div class="mt-6 w-full bg-cyan-300/30 h-2 rounded-full">
                        <div id="humidity-bar" class="bg-white h-2 rounded-full" style="width: 0%"></div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-600 to-indigo-800 rounded-2xl p-6 shadow-lg">
                    <div class="flex justify-between">
                        <h3>Air Pressure</h3><span class="text-purple-200 text-sm">BMP180</span>
                    </div>
                    <div class="mt-6 text-5xl font-bold">
                        <p id="pressure">--</p>
                    </div>
                    <p id="pressure-status" class="text-purple-200 mt-2">--</p>
                </div>

                <div class="bg-gradient-to-br from-pink-600 to-red-600 rounded-2xl p-6 shadow-lg">
                    <div class="flex justify-between">
                        <h3>BMP Temp</h3><span class="text-pink-200 text-sm">BMP180</span>
                    </div>
                    <div class="mt-6 text-5xl font-bold">
                        <p id="bmp-temp">--</p>
                    </div>
                    <p id="bmp-temp-status" class="text-pink-200 mt-2">--</p>
                </div>
            </div>

            <!-- CCTV -->
            <div class="bg-gray-900 rounded-2xl p-4 border border-gray-800 flex flex-col">
                <div class="flex justify-between items-center mb-3">
                    <h3>CCTV</h3><span class="text-red-400 text-sm animate-pulse">● Live</span>
                </div>
                <div class="relative rounded-xl overflow-hidden h-56 bg-black">
                    <video id="player" onclick="openModal()" class="w-full h-full object-cover cursor-pointer" autoplay
                        muted></video>
                    <div class="absolute bottom-2 left-2 text-xs bg-black/60 px-2 py-1 rounded">Terace</div>
                </div>
                <div class="flex justify-between mt-4 text-sm text-gray-400">
                    <button onclick="openModal()" class="hover:text-white">⛶ Fullscreen</button>
                </div>
            </div>
        </div>

        <!-- SWITCH -->
        <!-- SWITCH -->
        <div>
            <h2 class="text-xl mb-4 text-gray-300">Control Devices</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @php
                    $switches = [
                        ['label' => 'Living Room', 'desc' => 'Living Room Lamp', 'relay' => '4', 'color' => 'green'],
                        ['label' => 'Terace', 'desc' => 'Terace Lamp', 'relay' => '3', 'color' => 'yellow'],
                        ['label' => 'Comfort Mode', 'desc' => 'Activate / Deactivate Comfort Mode', 'relay' => 'comfort', 'color' => 'blue'],
                    ];
                @endphp
                @foreach($switches as $s)
                    <div
                        class="bg-gray-900 p-5 rounded-2xl border border-gray-800 hover:border-{{ $s['color'] }}-500 transition">
                        <h3>{{ $s['label'] }}</h3>
                        <p class="text-gray-500 text-sm mb-4">{{ $s['desc'] }}</p>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer lamp-switch" data-relay="{{ $s['relay'] }}">
                            <div class="w-12 h-7 bg-gray-700 rounded-full peer-checked:bg-{{ $s['color'] }}-500"></div>
                            <div
                                class="absolute left-1 top-1 w-5 h-5 bg-white rounded-full transition peer-checked:translate-x-5">
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- MODAL FULLSCREEN -->
    <div id="cctvModal" class="fixed inset-0 bg-black/90 hidden z-50 flex items-center justify-center">
        <button onclick="closeModal()" class="absolute top-5 right-5 text-white text-3xl">✕</button>
        <video id="modalPlayer" class="w-full h-full object-contain" autoplay muted controls></video>
    </div>

    <script>
        // =================== WEBSOCKET ===================
        const wsUrl = "{{ $ws_ip }}";
        const temperatureEl = document.getElementById('temperature');
        const humidityEl = document.getElementById('humidity');
        const tempBarEl = document.getElementById('temperature-bar');
        const humBarEl = document.getElementById('humidity-bar');
        const tempStatusEl = document.getElementById('temperature-status');
        const humStatusEl = document.getElementById('humidity-status');
        const pressureEl = document.getElementById('pressure');
        const pressureStatusEl = document.getElementById('pressure-status');

        const bmpTempEl = document.getElementById('bmp-temp');
        const bmpTempStatusEl = document.getElementById('bmp-temp-status');

        let ws = null;
        function connectWS() {
            if (ws && ws.readyState === WebSocket.OPEN) return;
            ws = new WebSocket(wsUrl);
            ws.onopen = () => console.log('WS connected');
            ws.onmessage = e => {
                try {
                    const d = JSON.parse(e.data);
                    temperatureEl.innerText = `${d.temperature.toFixed(1)}°C`;
                    humidityEl.innerText = `${d.humidity.toFixed(1)}%`;
                    tempBarEl.style.width = Math.min((d.temperature / 50) * 100, 100) + '%';
                    humBarEl.style.width = Math.min(d.humidity, 100) + '%';
                    tempStatusEl.innerText = d.temperature < 20 ? 'Cold' : d.temperature <= 30 ? 'Comfortable' : 'Hot';
                    humStatusEl.innerText = d.humidity < 30 ? 'Dry' : d.humidity <= 60 ? 'Normal' : 'Humid';
                    pressureEl.innerText = `${d.pressure.toFixed(2)} hPa`;
                    bmpTempEl.innerText = `${d.bmp_temperature.toFixed(1)}°C`;

                    // Pressure status
                    if (d.pressure < 1000) {
                        pressureStatusEl.innerText = "Low (Rainy)";
                    } else if (d.pressure <= 1013) {
                        pressureStatusEl.innerText = "Normal";
                    } else {
                        pressureStatusEl.innerText = "High (Clear)";
                    }

                    // BMP Temp status
                    bmpTempStatusEl.innerText =
                        d.bmp_temperature < 20 ? 'Cold' :
                            d.bmp_temperature <= 30 ? 'Comfortable' :
                                'Hot';
                } catch (err) { console.error(err); }
            };
            ws.onerror = console.error;
            ws.onclose = () => { console.log('Reconnect WS 3s'); setTimeout(connectWS, 3000); }
        }
        connectWS();

        // =================== LAMP SWITCH ===================
        document.querySelectorAll('.lamp-switch').forEach(cb => {
            cb.addEventListener('change', async e => {
                const isChecked = e.target.checked;
                const relay = e.target.dataset.relay;
                let reqs = [];

                if (relay === 'comfort') {
                    // ON → Activate Comfort, OFF → Deactivate Comfort
                    if (isChecked) {
                        reqs.push({ relay: 2, action: 'on' });
                        reqs.push({ relay: 4, action: 'off' });
                    } else {
                        reqs.push({ relay: 2, action: 'off' });
                        reqs.push({ relay: 4, action: 'on' });
                    }
                } else if (relay) {
                    reqs.push({ relay, action: isChecked ? 'on' : 'off' });
                }

                try {
                    const results = await Promise.all(reqs.map(r =>
                        fetch('{{ route("switch-action") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(r)
                        }).then(res => res.json())
                    ));
                    if (!results.every(r => r.status === 'success')) throw new Error('Some request failed');
                } catch (err) {
                    console.error(err);
                    e.target.checked = !isChecked; // rollback switch
                }
            });
        });

        // =================== CCTV ===================
        const video = document.getElementById('player');
        const modal = document.getElementById('cctvModal');
        const modalVideo = document.getElementById('modalPlayer');
        let playlist = [], currentIndex = 0, preloaded = new Set();

        async function loadPlaylist() {
            try {
                const res = await fetch('/cctv/list'); playlist = await res.json();
                playlist = playlist.slice(-5); currentIndex = 0; playCurrent();
            } catch (err) { console.error(err); }
        }
        function playCurrent() {
            if (!playlist[currentIndex]) return;
            const src = '/cctv/file?path=' + encodeURIComponent(playlist[currentIndex]);
            video.src = modalVideo.src = src; video.play(); if (!modal.classList.contains('hidden')) modalVideo.currentTime = video.currentTime;
            preloadNext();
        }
        function preloadNext() {
            if (!playlist[currentIndex + 1] || preloaded.has(currentIndex + 1)) return;
            const l = document.createElement('link'); l.rel = 'preload'; l.as = 'video';
            l.href = '/cctv/file?path=' + encodeURIComponent(playlist[currentIndex + 1]); document.head.appendChild(l);
            preloaded.add(currentIndex + 1);
        }
        video.addEventListener('ended', () => { currentIndex++; currentIndex >= playlist.length ? loadPlaylist() : playCurrent(); });
        function openModal() { modal.classList.remove('hidden'); modalVideo.src = video.src; modalVideo.currentTime = video.currentTime; modalVideo.play(); }
        function closeModal() { modal.classList.add('hidden'); modalVideo.pause(); }
        video.addEventListener('timeupdate', () => { if (!modal.classList.contains('hidden')) modalVideo.currentTime = video.currentTime; });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
        loadPlaylist(); setInterval(loadPlaylist, 10000);

    </script>

    <script>
        // Ambil semua switchsmart
        const lampSwitches = document.querySelectorAll('.lamp-switch');

        // Load state dari localStorage saat halaman dibuka
        lampSwitches.forEach(cb => {
            const relay = cb.dataset.relay;
            const saved = localStorage.getItem(`switch-${relay}`);
            if (saved !== null) cb.checked = saved === 'true';
        });

        lampSwitches.forEach(cb => {
            cb.addEventListener('change', async e => {
                const isChecked = e.target.checked;
                const relay = e.target.dataset.relay;
                let reqs = [];

                // Comfort mode logic
                if (relay === 'comfort') {
                    if (isChecked) {
                        reqs.push({ relay: 2, action: 'on' });
                        reqs.push({ relay: 4, action: 'off' }); // Living Room off
                    } else {
                        reqs.push({ relay: 2, action: 'off' });
                        reqs.push({ relay: 4, action: 'on' }); // Living Room on kembali
                    }
                } else if (relay) {
                    reqs.push({ relay, action: isChecked ? 'on' : 'off' });
                }

                try {
                    const results = await Promise.all(reqs.map(r =>
                        fetch('{{ route("switch-action") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(r)
                        }).then(res => res.json())
                    ));
                    if (!results.every(r => r.status === 'success')) throw new Error('Some request failed');

                    // Simpan state ke localStorage
                    localStorage.setItem(`switch-${relay}`, isChecked);

                    // Jika Comfort Mode, update Living Room switch secara visual dan di localStorage
                    if (relay === 'comfort') {
                        const livingRoomSwitch = document.querySelector('.lamp-switch[data-relay="4"]');
                        if (livingRoomSwitch) {
                            livingRoomSwitch.checked = !isChecked; // off jika Comfort on
                            localStorage.setItem(`switch-4`, !isChecked);
                        }
                    }

                } catch (err) {
                    console.error(err);
                    e.target.checked = !isChecked; // rollback switch
                }
            });
        });
    </script>
</body>

</html>
