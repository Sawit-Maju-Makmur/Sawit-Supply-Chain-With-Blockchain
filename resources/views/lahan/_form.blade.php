@php
    $v = fn($f) => old($f, $lahan->$f ?? '');
    $polygonCoordinates = old('polygon_coordinates', $lahan->polygon_coordinates ?? '');
    if (is_array($polygonCoordinates)) {
        $polygonCoordinates = json_encode($polygonCoordinates);
    }
@endphp

@if ($errors->any())
    <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
    </div>
@endif

<div class="grid md:grid-cols-2 gap-4">
    <div class="md:col-span-2">
        <label class="block mb-1 font-medium">Nama Kebun *</label>
        <input type="text" name="nama_kebun" value="{{ $v('nama_kebun') }}" placeholder="Contoh: Kebun Blok A" class="w-full border rounded p-2" required>
    </div>
    <div class="md:col-span-2">
        <label class="block mb-1 font-medium">Lokasi (desa, kecamatan, kabupaten) *</label>
        <input type="text" name="lokasi" value="{{ $v('lokasi') }}" placeholder="Contoh: Desa Makmur, Kec. Siak, Kab. Siak" class="w-full border rounded p-2" required>
    </div>
    <div>
        <label class="block mb-1">Latitude</label>
        <input type="text" name="latitude" id="input-latitude" value="{{ $v('latitude') }}" placeholder="-0.7893" class="w-full border rounded p-2">
    </div>
    <div>
        <label class="block mb-1">Longitude</label>
        <input type="text" name="longitude" id="input-longitude" value="{{ $v('longitude') }}" placeholder="113.9213" class="w-full border rounded p-2">
    </div>

    {{-- ── MAP SECTION ─────────────────────────────────────────── --}}
    <div class="md:col-span-2">
        <label class="block mb-1" for="polygon-map">Lokasi & Batas Poligon Lahan</label>
        <p class="mb-2 text-sm text-gray-600">
            Gunakan <strong>Mode Titik Lokasi</strong> untuk menandai titik pusat lahan, atau <strong>Mode Batas Poligon</strong>
            untuk memetakan batas lahan (minimal 3 titik). Kedua fitur bersifat opsional.
        </p>

        {{-- Mode Toggle --}}
        <div class="mb-2 flex flex-wrap gap-2">
            <button type="button" id="mode-pin"
                class="inline-flex items-center gap-1 rounded px-3 py-1.5 text-sm font-medium border transition-colors">
                📍 Mode Titik Lokasi (Pin)
            </button>
            <button type="button" id="mode-polygon"
                class="inline-flex items-center gap-1 rounded px-3 py-1.5 text-sm font-medium border transition-colors">
                🔲 Mode Batas Poligon Area
            </button>
        </div>

        {{-- Map Container --}}
        <div id="polygon-map" class="rounded border"></div>

        {{-- Geolocation Toolbar --}}
        <div class="mt-2 flex flex-wrap items-center gap-2 text-sm">
            <button type="button" id="use-location"
                class="rounded bg-green-700 text-white px-3 py-1.5 hover:bg-green-800 transition-colors">
                📡 Gunakan lokasi saya
            </button>
            <span id="location-status" class="text-gray-600" aria-live="polite">Lokasi belum diakses.</span>
        </div>

        {{-- Hidden polygon_coordinates input (backend-compatible, do NOT rename) --}}
        <input
            type="hidden"
            name="polygon_coordinates"
            id="polygon-coordinates"
            value="{{ $polygonCoordinates }}"
        >

        {{-- Polygon Status & Controls --}}
        <div class="mt-2 flex flex-wrap items-center gap-3 text-sm">
            <span id="polygon-status" class="text-gray-600" aria-live="polite">Belum ada titik batas.</span>
            <button type="button" id="polygon-undo"
                class="rounded border px-3 py-1 hover:bg-gray-50 transition-colors">
                ↩ Hapus Titik Terakhir
            </button>
            <button type="button" id="polygon-reset"
                class="rounded border px-3 py-1 hover:bg-gray-50 transition-colors">
                🗑 Reset Peta
            </button>
        </div>
    </div>
    {{-- ── END MAP SECTION ──────────────────────────────────────── --}}

    <div>
        <label class="block mb-1">Luas Lahan (hektar) *</label>
        <input type="number" step="0.01" name="luas_ha" value="{{ $v('luas_ha') }}" class="w-full border rounded p-2">
    </div>
    <div>
        <label class="block mb-1">Status Lahan *</label>
        <select name="status_lahan" class="w-full border rounded p-2">
            <option value="">-- Pilih --</option>
            @foreach (\App\Models\Lahan::STATUS as $key => $label)
                <option value="{{ $key }}" @selected($v('status_lahan') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">
        <label class="block mb-1">No. Dokumen Kepemilikan</label>
        <input type="text" name="no_dokumen" value="{{ $v('no_dokumen') }}" class="w-full border rounded p-2">
    </div>
    <div class="md:col-span-2">
        <label class="block mb-1">Keterangan</label>
        <textarea name="keterangan" rows="3" class="w-full border rounded p-2">{{ $v('keterangan') }}</textarea>
    </div>
</div>

<div class="mt-6 flex gap-3">
    <button class="bg-green-700 text-white px-4 py-2 rounded">Simpan</button>
    <a href="{{ route('lahan.index') }}" class="px-4 py-2 rounded border">Batal</a>
</div>

@push('styles')
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""
    >
    <style>
        #polygon-map {
            height: 360px;
            z-index: 0;
        }

        /* Mode toggle button styles */
        .map-mode-active {
            background-color: #15803d; /* green-700 */
            color: #ffffff;
            border-color: #15803d;
        }
        .map-mode-inactive {
            background-color: #f9fafb; /* gray-50 */
            color: #374151; /* gray-700 */
            border-color: #d1d5db; /* gray-300 */
        }
        .map-mode-inactive:hover {
            background-color: #f3f4f6;
        }
    </style>
@endpush

@push('scripts')
    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""
    ></script>
    <script>
        (() => {
            // ── KONSTANTA & STATE ─────────────────────────────────────
            const DEFAULT_LAT  = -0.5897;
            const DEFAULT_LNG  = 101.3431;
            const DEFAULT_ZOOM = 12;

            let mode = 'pin'; // 'pin' | 'polygon'

            // ── DOM REFS ──────────────────────────────────────────────
            const latInput     = document.getElementById('input-latitude');
            const lngInput     = document.getElementById('input-longitude');
            const polyInput    = document.getElementById('polygon-coordinates');
            const polyStatus   = document.getElementById('polygon-status');
            const locStatus    = document.getElementById('location-status');
            const btnPin       = document.getElementById('mode-pin');
            const btnPoly      = document.getElementById('mode-polygon');
            const form         = polyInput.closest('form');

            // ── MAP INIT ──────────────────────────────────────────────
            const inputLat = Number(latInput?.value);
            const inputLng = Number(lngInput?.value);
            const hasValidInitialCoords =
                latInput?.value.trim() !== '' &&
                lngInput?.value.trim() !== '' &&
                Number.isFinite(inputLat) && inputLat >= -90 && inputLat <= 90 &&
                Number.isFinite(inputLng) && inputLng >= -180 && inputLng <= 180;
            const initLat  = hasValidInitialCoords ? inputLat : DEFAULT_LAT;
            const initLng  = hasValidInitialCoords ? inputLng : DEFAULT_LNG;
            const initZoom = hasValidInitialCoords ? 15 : DEFAULT_ZOOM;

            const map = L.map('polygon-map').setView([initLat, initLng], initZoom);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Pastikan peta render sempurna dalam layout grid
            setTimeout(() => map.invalidateSize(), 0);
            window.addEventListener('load', () => map.invalidateSize());

            // ── PIN MARKER ────────────────────────────────────────────
            let pinMarker = null;

            /**
             * Tempatkan / pindahkan pin marker ke koordinat [lat, lng],
             * sekaligus perbarui nilai input latitude & longitude.
             */
            function placePinMarker(lat, lng) {
                _movePinMarker(lat, lng, true);
            }

            /**
             * Pindahkan pin marker TANPA menulis ulang input
             * (digunakan saat input → peta agar tidak terjadi loop).
             */
            function movePinMarkerSilent(lat, lng) {
                _movePinMarker(lat, lng, false);
            }

            function _movePinMarker(lat, lng, updateInputs) {
                if (pinMarker) map.removeLayer(pinMarker);

                const draggable = (mode === 'pin');
                const tooltip   = (mode === 'pin') ? 'Titik Lokasi Lahan' : 'Titik Tengah Poligon';

                pinMarker = L.marker([lat, lng], { draggable })
                    .addTo(map)
                    .bindTooltip(tooltip);

                if (draggable) {
                    pinMarker.on('drag', () => {
                        const pos = pinMarker.getLatLng();
                        setLatLngInputs(pos.lat, pos.lng);
                    });
                }

                if (updateInputs) setLatLngInputs(lat, lng);
            }

            function setLatLngInputs(lat, lng) {
                if (latInput) latInput.value = parseFloat(lat).toFixed(6);
                if (lngInput) lngInput.value = parseFloat(lng).toFixed(6);
            }

            function clearPinMarker() {
                if (pinMarker) {
                    map.removeLayer(pinMarker);
                    pinMarker = null;
                }
            }

            function clearLatLngInputs() {
                if (latInput) latInput.value = '';
                if (lngInput) lngInput.value = '';
            }

            // ── POLYGON ───────────────────────────────────────────────
            const points  = []; // array of [lat, lng]
            const markers = []; // array of L.circleMarker
            let polygon   = null;

            function drawPolygon() {
                if (polygon) { map.removeLayer(polygon); polygon = null; }
                if (points.length >= 2) {
                    polygon = L.polygon(points, {
                        color: '#15803d',
                        fillColor: '#86efac',
                        fillOpacity: 0.4,
                        weight: 3
                    }).addTo(map);
                }
            }

            function calcCentroid() {
                if (!points.length) return null;
                const sumLat = points.reduce((s, p) => s + p[0], 0);
                const sumLng = points.reduce((s, p) => s + p[1], 0);
                return [sumLat / points.length, sumLng / points.length];
            }

            /**
             * Sinkronkan hidden input polygon_coordinates, perbarui status,
             * dan (jika ada titik) set lat/lng ke centroid poligon.
             */
            function syncPolygon() {
                polyInput.value = points.length ? JSON.stringify(points) : '';

                if (points.length === 0) {
                    polyStatus.textContent = 'Belum ada titik batas.';
                } else if (points.length < 3) {
                    polyStatus.textContent = `${points.length} titik; tambahkan minimal ${3 - points.length} titik lagi.`;
                } else {
                    polyStatus.textContent = `${points.length} titik; poligon terbentuk.`;
                }

                // Perbarui lat/lng ke centroid & pindahkan marker pusat
                const centroid = calcCentroid();
                if (centroid) {
                    setLatLngInputs(centroid[0], centroid[1]);
                    movePinMarkerSilent(centroid[0], centroid[1]);
                }
            }

            function addPolygonPoint(lat, lng) {
                const point = [
                    parseFloat(parseFloat(lat).toFixed(6)),
                    parseFloat(parseFloat(lng).toFixed(6))
                ];
                points.push(point);

                const marker = L.circleMarker(point, {
                    radius: 6,
                    color: '#15803d',
                    fillColor: '#22c55e',
                    fillOpacity: 0.9,
                    weight: 2
                }).addTo(map);
                marker.bindTooltip(`Titik ${points.length}: ${point[0]}, ${point[1]}`);
                markers.push(marker);

                drawPolygon();
                syncPolygon();
            }

            // ── MAP CLICK ─────────────────────────────────────────────
            map.on('click', e => {
                if (mode === 'pin') {
                    placePinMarker(e.latlng.lat, e.latlng.lng);
                } else {
                    addPolygonPoint(e.latlng.lat, e.latlng.lng);
                }
            });

            // ── MODE TOGGLE ───────────────────────────────────────────
            function setMode(newMode) {
                mode = newMode;

                // Perbarui style tombol
                if (mode === 'pin') {
                    btnPin.className  = btnPin.className.replace(/map-mode-\w+/g, '') + ' map-mode-active';
                    btnPoly.className = btnPoly.className.replace(/map-mode-\w+/g, '') + ' map-mode-inactive';
                } else {
                    btnPoly.className = btnPoly.className.replace(/map-mode-\w+/g, '') + ' map-mode-active';
                    btnPin.className  = btnPin.className.replace(/map-mode-\w+/g, '') + ' map-mode-inactive';
                }

                // Jika pin marker ada, perbarui draggable-nya sesuai mode
                if (pinMarker) {
                    const latLng = pinMarker.getLatLng();
                    movePinMarkerSilent(latLng.lat, latLng.lng);
                }
            }

            btnPin.addEventListener('click',  () => setMode('pin'));
            btnPoly.addEventListener('click', () => setMode('polygon'));

            // ── UNDO / RESET ──────────────────────────────────────────
            document.getElementById('polygon-undo').addEventListener('click', () => {
                const marker = markers.pop();
                if (marker) {
                    map.removeLayer(marker);
                    points.pop();
                    drawPolygon();
                    syncPolygon();
                    if (points.length === 0) {
                        clearPinMarker();
                        clearLatLngInputs();
                    }
                }
            });

            document.getElementById('polygon-reset').addEventListener('click', () => {
                markers.forEach(m => map.removeLayer(m));
                markers.length = 0;
                points.length  = 0;
                drawPolygon();
                syncPolygon();
                clearPinMarker();
                clearLatLngInputs();
            });

            // ── GEOLOCATION ───────────────────────────────────────────
            function setLocStatus(msg, isError = false) {
                locStatus.textContent = msg;
                locStatus.classList.toggle('text-red-600', isError);
                locStatus.classList.toggle('text-gray-600', !isError);
            }

            document.getElementById('use-location').addEventListener('click', () => {
                if (!navigator.geolocation) {
                    setLocStatus('Browser Anda tidak mendukung akses lokasi.', true);
                    return;
                }

                setLocStatus('Mencari lokasi Anda...');
                navigator.geolocation.getCurrentPosition(
                    ({ coords }) => {
                        const lat = coords.latitude;
                        const lng = coords.longitude;

                        // Gunakan pin marker untuk lokasi user
                        placePinMarker(lat, lng);
                        map.setView([lat, lng], 15);
                        setLocStatus(`Lokasi Anda: ${lat.toFixed(6)}, ${lng.toFixed(6)}`);
                    },
                    err => {
                        const msgs = {
                            1: 'Akses lokasi ditolak. Izinkan akses lokasi di browser Anda.',
                            2: 'Lokasi saat ini tidak tersedia.',
                            3: 'Waktu untuk mengambil lokasi habis.'
                        };
                        setLocStatus(msgs[err.code] ?? 'Gagal mengambil lokasi Anda.', true);
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 }
                );
            });

            // ── TWO-WAY SYNC: INPUT → MAP ─────────────────────────────
            function onLatLngInputChange() {
                const lat = parseFloat(latInput?.value);
                const lng = parseFloat(lngInput?.value);

                if (
                    Number.isFinite(lat) && Number.isFinite(lng) &&
                    lat >= -90  && lat <= 90 &&
                    lng >= -180 && lng <= 180
                ) {
                    const zoom = map.getZoom() < 10 ? 13 : map.getZoom();
                    map.setView([lat, lng], zoom);

                    // Pindahkan pin marker ke koordinat baru (tanpa update input → no loop)
                    movePinMarkerSilent(lat, lng);
                }
            }

            latInput?.addEventListener('input', onLatLngInputChange);
            lngInput?.addEventListener('input', onLatLngInputChange);

            // ── EDIT PAGE PRE-FILL ────────────────────────────────────
            // 1. Pulihkan pin marker jika lat/lng sudah ada (halaman Edit)
            const savedLat = parseFloat(latInput?.value);
            const savedLng = parseFloat(lngInput?.value);
            if (
                latInput?.value && lngInput?.value &&
                Number.isFinite(savedLat) && Number.isFinite(savedLng)
            ) {
                movePinMarkerSilent(savedLat, savedLng);
            }

            // 2. Pulihkan poligon jika polygon_coordinates sudah ada di DB
            try {
                const previousPoints = JSON.parse(polyInput.value || '[]');
                if (Array.isArray(previousPoints) && previousPoints.length) {
                    previousPoints.forEach(pt => {
                        if (
                            Array.isArray(pt) && pt.length === 2 &&
                            Number.isFinite(Number(pt[0])) &&
                            Number.isFinite(Number(pt[1]))
                        ) {
                            addPolygonPoint(Number(pt[0]), Number(pt[1]));
                        }
                    });
                    if (polygon) map.fitBounds(polygon.getBounds());
                }
            } catch (err) {
                console.error('Gagal memuat koordinat batas lahan.', err);
                polyStatus.textContent = 'Koordinat batas lahan tidak dapat dimuat.';
            }

            // ── FORM SUBMIT GUARD ─────────────────────────────────────
            form.addEventListener('submit', e => {
                // Pastikan polygon_coordinates sinkron sebelum submit
                syncPolygon();

                if (points.length > 0 && points.length < 3) {
                    e.preventDefault();
                    alert(
                        'Tambahkan minimal 3 titik untuk membentuk poligon, ' +
                        'atau klik "Reset Peta" untuk menyimpan tanpa poligon.'
                    );
                }
            });

            // ── INISIALISASI AKHIR ────────────────────────────────────
            setMode('pin');      // set tampilan tombol mode default
            syncPolygon();       // set teks status awal
        })();
    </script>
@endpush
