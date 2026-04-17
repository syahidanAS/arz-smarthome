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

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-semibold">ARZ Smarthome System</h1>
            <p class="text-gray-400 text-sm">All control is in your hands</p>
        </div>

        <div class="flex flex-wrap gap-2 md:gap-3">
            <div class="bg-green-500/10 text-green-400 px-3 py-2 md:px-4 rounded-xl text-xs md:text-sm">
                🟢 All system normal
            </div>

            <div class="bg-gray-800 px-3 py-2 md:px-4 rounded-xl text-xs md:text-sm">
                {{ now()->format('H:i') }}
            </div>

            <button id="automationBtn"
                class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 px-3 py-2 md:px-4 rounded-xl text-xs md:text-sm transition cursor-pointer">
                Automation
            </button>

            <button id="logoutBtn"
                class="bg-red-500/10 hover:bg-red-500/20 text-red-400 px-3 py-2 md:px-4 rounded-xl text-xs md:text-sm transition cursor-pointer">
                Logout
            </button>
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

    @include('automation')

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

    <script>
    $(document).ready(function () {

        $('#logoutBtn').on('click', function () {

            if (!confirm('Yakin mau logout?')) return;

            $.ajax({
                url: "{{ route('logout') }}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function () {
                    window.location.href = "/login";
                },
                error: function () {
                    alert('Gagal logout');
                }
            });

        });

    });
</script>

<script>
    const automationModal = document.getElementById('automationModal');
    const automationContent = document.getElementById('automationContent');

    let automationCache = null;

    // =========================
    // OPEN / CLOSE MODAL
    // =========================
    function openAutomation() {
        automationModal.classList.remove('pointer-events-none');

        // tampilkan loading dulu
        showLoading();

        // animasi masuk
        setTimeout(() => {
            automationModal.classList.remove('opacity-0');
            automationContent.classList.remove('-translate-y-5', 'opacity-0');
        }, 50);

        loadAutomation();
    }

    function closeAutomation() {
        automationModal.classList.add('opacity-0');
        automationContent.classList.add('-translate-y-5', 'opacity-0');

        setTimeout(() => {
            automationModal.classList.add('pointer-events-none');
        }, 300);
    }

    document.getElementById('automationBtn').addEventListener('click', openAutomation);

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeAutomation();
    });

    // =========================
    // HELPERS
    // =========================
    function escapeHtml(text) {
        return $('<div>').text(text).html();
    }

    function showLoading() {
        $('#automationList').html(`
            <div class="col-span-2 text-center text-gray-400 animate-pulse">
                Loading automation...
            </div>
        `);
    }

    function showEmpty() {
        $('#automationList').html(`
            <div class="col-span-2 text-center text-gray-500">
                No automation found
            </div>
        `);
    }

    function showError() {
        $('#automationList').html(`
            <div class="col-span-2 text-center text-red-400">
                Failed load automation
            </div>
        `);
    }

    // =========================
    // LOAD DATA
    // =========================
    function loadAutomation(force = false) {

        // pakai cache kalau ada
        if (automationCache && !force) {
            renderAutomation(automationCache);
            return;
        }

        $.ajax({
            url: "{{ route('automations') }}",
            method: 'GET',
            success: function(res) {

                if (res.status !== 'success') {
                    showError();
                    return;
                }

                if (!res.data || res.data.length === 0) {
                    showEmpty();
                    return;
                }

                automationCache = res.data;

                renderAutomation(res.data);
            },
            error: function() {
                showError();
            }
        });
    }

function renderAutomation(data) {

    let html = '';

    data.forEach(item => {

        let statusColor = item.enabled ? 'green' : 'gray';
        let statusText = item.enabled ? 'Active' : 'Disabled';

        html += `
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5
                        hover:scale-[1.02] transition-all duration-300">

                <div class="flex justify-between items-start">

                    <div class="flex items-start gap-3">
                        <input type="checkbox"
                            class="automation-checkbox mt-1"
                            value="${item.id}">

                        <div>
                            <h3 class="text-lg font-semibold">${escapeHtml(item.name)}</h3>
                            <p class="text-gray-400 text-sm">${escapeHtml(item.description ?? '-')}</p>
                        </div>
                    </div>

                    <span class="text-${statusColor}-400 text-xs">
                        ● ${statusText}
                    </span>
                </div>

                <div class="mt-4 space-y-1 text-sm text-gray-400">
                    <p>⏰ ${item.time}</p>
                    <p>📡 ${escapeHtml(item.topic)}</p>
                    <p>💬 ${escapeHtml(item.message)}</p>
                </div>

                <div class="mt-4 flex justify-between items-center">

                    <!-- Toggle -->
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox"
                            class="sr-only peer automation-toggle"
                            data-id="${item.id}"
                            ${item.enabled ? 'checked' : ''}>

                        <div class="w-11 h-6 bg-gray-700 rounded-full
                                    peer-checked:bg-green-500 transition"></div>

                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full
                                    transition peer-checked:translate-x-5"></div>
                    </label>

                    <div class="flex items-center gap-3">

                        <!-- Edit Button -->
                        <button
                            class="text-xs text-blue-400 hover:text-blue-300 edit-automation"
                            data-id="${item.id}"
                            data-time="${item.time}"
                            data-topic="${item.topic}"
                            data-message="${item.message}">
                            ✏️ Edit
                        </button>

                        <div class="text-xs bg-gray-800 px-3 py-1 rounded-lg">
                            ${item.time}
                        </div>

                    </div>
                </div>
            </div>
        `;
    });

    $('#automationList').html(html);
}

    // =========================
    // TOGGLE ENABLE/DISABLE
    // =========================
    $(document).on('change', '.automation-toggle', function () {

        let id = $(this).data('id');
        let enabled = $(this).is(':checked') ? 1 : 0;
        let el = $(this);

        $.ajax({
            url: "{{ route('automations.toggle') }}", // sesuaikan
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                id: id,
                enabled: enabled
            }),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function() {
                // update cache biar konsisten
                if (automationCache) {
                    automationCache = automationCache.map(item => {
                        if (item.id == id) item.enabled = enabled;
                        return item;
                    });
                }
            },
            error: function () {
                // rollback kalau gagal
                el.prop('checked', !enabled);
                alert('Gagal update automation');
            }
        });

    });


    const editModal = document.getElementById('editAutomationModal');
const editContent = document.getElementById('editAutomationContent');

// =========================
// OPEN EDIT MODAL
// =========================
$(document).on('click', '.edit-automation', function () {

    $('#edit-id').val($(this).data('id'));
    $('#edit-time').val($(this).data('time'));
    $('#edit-topic').val($(this).data('topic'));
    $('#edit-message').val($(this).data('message'));

    editModal.classList.remove('pointer-events-none');

    setTimeout(() => {
        editModal.classList.remove('opacity-0');
        editContent.classList.remove('scale-95', 'opacity-0');
    }, 10);
});

// =========================
// CLOSE EDIT MODAL
// =========================
function closeEditModal() {
    editModal.classList.add('opacity-0');
    editContent.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        editModal.classList.add('pointer-events-none');
    }, 300);
}

// =========================
// SAVE EDIT
// =========================
$('#saveEditBtn').on('click', function () {

    let id = $('#edit-id').val();

    let data = {
        id: id,
        time: $('#edit-time').val(),
        topic: $('#edit-topic').val(),
        message: $('#edit-message').val()
    };

    $.ajax({
        url: "{{ route('automations.update') }}",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(data),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function () {

            closeEditModal();

            // refresh ulang
            automationCache = null;
            loadAutomation(true);
        },
        error: function () {
            alert('Gagal update automation');
        }
    });

});


const addModal = document.getElementById('addAutomationModal');
const addContent = document.getElementById('addAutomationContent');

// =========================
// OPEN ADD MODAL
// =========================
$('#addAutomationBtn').on('click', function () {

    // reset form
    $('#add-name').val('');
    $('#add-time').val('');
    $('#add-topic').val('');
    $('#add-message').val('');
    $('#add-description').val('');

    addModal.classList.remove('pointer-events-none');

    setTimeout(() => {
        addModal.classList.remove('opacity-0');
        addContent.classList.remove('scale-95', 'opacity-0');
    }, 10);
});

// =========================
// CLOSE ADD MODAL
// =========================
function closeAddModal() {
    addModal.classList.add('opacity-0');
    addContent.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        addModal.classList.add('pointer-events-none');
    }, 300);
}

// =========================
// SAVE NEW AUTOMATION
// =========================
$('#saveAddBtn').on('click', function () {

    let data = {
        name: $('#add-name').val(),
        time: $('#add-time').val(),
        topic: $('#add-topic').val(),
        message: $('#add-message').val(),
        description: $('#add-description').val()
    };

    // validasi simple
    if (!data.name || !data.time || !data.topic) {
        alert('Name, Time, Topic wajib diisi');
        return;
    }

    $.ajax({
        url: "{{ route('automation.store') }}",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(data),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function () {

            closeAddModal();

            // refresh list
            automationCache = null;
            loadAutomation(true);
        },
        error: function () {
            alert('Gagal tambah automation');
        }
    });

});

function updateDeleteButton() {
    let checked = $('.automation-checkbox:checked').length;

    if (checked > 0) {
        $('#deleteSelectedBtn').removeClass('hidden')
            .text(`🗑 Delete (${checked})`);
    } else {
        $('#deleteSelectedBtn').addClass('hidden');
    }
}

// trigger saat checkbox berubah
$(document).on('change', '.automation-checkbox', updateDeleteButton);



</script>
</body>

</html>
