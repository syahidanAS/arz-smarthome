<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SmartHome Panel') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        <div>
            <h2 class="text-xl mb-4 text-gray-300">Control Devices</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @php
                    $switches = [
                        ['label' => 'Living Room', 'desc' => 'Living Room Lamp', 'relay' => '2', 'color' => 'green'],
                        ['label' => 'Bedroom', 'desc' => 'Bedroom Lamp', 'relay' => '3', 'color' => 'blue'],
                        ['label' => 'Terace', 'desc' => 'Terace Lamp', 'relay' => '4', 'color' => 'yellow']
                    ];
                @endphp

                @foreach($switches as $s)
                    <div class="bg-gray-900 p-5 rounded-2xl border border-gray-800">
                        <h3>{{ $s['label'] }}</h3>
                        <p class="text-gray-500 text-sm mb-4">{{ $s['desc'] }}</p>

                        <label class="relative inline-flex items-center cursor-pointer">
                               <input type="checkbox"
                                class="sr-only peer lamp-switch"
                                data-relay="{{ $s['relay'] }}"
                                {{ ($relays[$s['relay']] ?? 'off') == 'on' ? 'checked' : '' }}>

                            <!-- Track -->
                            <div class="w-12 h-7 bg-gray-700 rounded-full
                                        peer-checked:bg-green-500
                                        transition-colors"></div>

                            <!-- Knob -->
                            <div class="absolute left-1 top-1 w-5 h-5 bg-white rounded-full
                                        transition-transform
                                        peer-checked:translate-x-5"></div>

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
         async function fetchSensorData() {
        try {
            const res = await fetch('/sensors');
            if (!res.ok) throw new Error('Failed to fetch sensor data');
            const data = await res.json();

            // Update DOM
            document.getElementById('temperature').textContent = data.temperature ?? '--';
            document.getElementById('humidity').textContent = data.humidity ?? '--';
            document.getElementById('pressure').textContent = data.pressure ?? '--';
            document.getElementById('bmp-temp').textContent = data.bmp_temp ?? '--';

            // Optional: update bar width (0-100%) misal
            if (data.temperature) document.getElementById('temperature-bar').style.width = Math.min(data.temperature, 100) + '%';
            if (data.humidity) document.getElementById('humidity-bar').style.width = Math.min(data.humidity, 100) + '%';

        } catch(err) {
            console.error(err);
        }
    }

    // Fetch data pertama kali
    fetchSensorData();

    // Polling tiap 3 detik
    setInterval(fetchSensorData, 3000);
    </script>

    <script>
        $(document).ready(function () {
            const url = '{{ env("APP_ENV") }}' === 'local' ? 'http://localhost:8000/switch-action' : "{{ route('switch-action') }}";
            const token = $('meta[name="csrf-token"]').attr('content');

            $('.lamp-switch').on('change', function () {
                let el = $(this);
                let relay = el.data('relay');
                let isChecked = el.is(':checked');

                $.ajax({
                    url: url,
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({
                        relay: relay,
                        action: isChecked ? 'on' : 'off'
                    }),
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    success: function () {
                        localStorage.setItem('switch-' + relay, isChecked);
                    },
                    error: function () {
                        el.prop('checked', !isChecked);
                    }
                });
            });
        });
    </script>
</body>

</html>
