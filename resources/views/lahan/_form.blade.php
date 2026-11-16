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
    <div>
        <label class="block mb-1">Nama Pemasok / Petani *</label>
        <input type="text" name="nama_pemasok" value="{{ $v('nama_pemasok') }}" class="w-full border rounded p-2">
    </div>
    <div>
        <label class="block mb-1">Nama Kebun *</label>
        <input type="text" name="nama_kebun" value="{{ $v('nama_kebun') }}" class="w-full border rounded p-2">
    </div>
    <div class="md:col-span-2">
        <label class="block mb-1">Lokasi (desa, kecamatan, kabupaten) *</label>
        <input type="text" name="lokasi" value="{{ $v('lokasi') }}" class="w-full border rounded p-2">
    </div>
    <div>
        <label class="block mb-1">Latitude</label>
        <input type="text" name="latitude" value="{{ $v('latitude') }}" placeholder="-0.7893" class="w-full border rounded p-2">
    </div>
    <div>
        <label class="block mb-1">Longitude</label>
        <input type="text" name="longitude" value="{{ $v('longitude') }}" placeholder="113.9213" class="w-full border rounded p-2">
    </div>
    <div class="md:col-span-2">
        <label class="block mb-1" for="polygon-map">Batas Poligon Lahan</label>
        <p class="mb-2 text-sm text-gray-600">Klik peta untuk menambahkan minimal 3 titik batas. Fitur ini opsional.</p>
        <div id="polygon-map" class="rounded border"></div>
        <div class="mt-2 flex flex-wrap items-center gap-2 text-sm">
            <button type="button" id="use-location" class="rounded bg-green-700 text-white px-3 py-1.5">Gunakan lokasi saya</button>
            <span id="location-status" class="text-gray-600" aria-live="polite">Lokasi belum diakses.</span>
        </div>
        <input
            type="hidden"
            name="polygon_coordinates"
            id="polygon-coordinates"
            value="{{ $polygonCoordinates }}"
        >
        <div class="mt-2 flex flex-wrap items-center gap-3 text-sm">
            <span id="polygon-status" class="text-gray-600" aria-live="polite">Belum ada titik batas.</span>
            <button type="button" id="polygon-undo" class="rounded border px-3 py-1">Hapus titik terakhir</button>
            <button type="button" id="polygon-reset" class="rounded border px-3 py-1">Reset titik</button>
        </div>
    </div>
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
            const map = L.map('polygon-map').setView([-0.5897, 101.3431], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            window.addEventListener('load', () => map.invalidateSize());
            setTimeout(() => map.invalidateSize(), 0);

            const input = document.getElementById('polygon-coordinates');
            const status = document.getElementById('polygon-status');
            const locationStatus = document.getElementById('location-status');
            const latitudeInput = document.querySelector('input[name="latitude"]');
            const longitudeInput = document.querySelector('input[name="longitude"]');
            const useLocationButton = document.getElementById('use-location');
            const form = input.closest('form');
            const points = [];
            const markers = [];
            let polygon = null;
            let currentLocationMarker = null;

            function setLocationStatus(message, isError = false) {
                locationStatus.textContent = message;
                locationStatus.classList.toggle('text-red-600', isError);
                locationStatus.classList.toggle('text-gray-600', !isError);
            }

            function applyUserLocation(latitude, longitude) {
                const lat = Number(latitude);
                const lng = Number(longitude);

                if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
                    return;
                }

                if (latitudeInput) latitudeInput.value = lat.toFixed(6);
                if (longitudeInput) longitudeInput.value = lng.toFixed(6);

                map.setView([lat, lng], 15);

                if (currentLocationMarker) {
                    map.removeLayer(currentLocationMarker);
                }

                currentLocationMarker = L.circleMarker([lat, lng], {
                    radius: 8,
                    color: '#1d4ed8',
                    fillColor: '#60a5fa',
                    fillOpacity: 0.9,
                    weight: 2
                }).addTo(map);
                currentLocationMarker.bindTooltip('Lokasi Anda');

                setLocationStatus(`Lokasi Anda: ${lat.toFixed(6)}, ${lng.toFixed(6)}`);
            }

            useLocationButton.addEventListener('click', () => {
                if (!navigator.geolocation) {
                    setLocationStatus('Browser Anda tidak mendukung akses lokasi.', true);
                    return;
                }

                setLocationStatus('Mencari lokasi Anda...');
                navigator.geolocation.getCurrentPosition(
                    ({ coords }) => applyUserLocation(coords.latitude, coords.longitude),
                    error => {
                        const messages = {
                            1: 'Akses lokasi ditolak. Izinkan akses lokasi di browser Anda.',
                            2: 'Lokasi saat ini tidak tersedia.',
                            3: 'Waktu untuk mengambil lokasi habis.'
                        };
                        setLocationStatus(messages[error.code] ?? 'Gagal mengambil lokasi Anda.', true);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 60000
                    }
                );
            });

            function drawPolygon() {
                if (polygon) {
                    map.removeLayer(polygon);
                    polygon = null;
                }

                if (points.length >= 2) {
                    polygon = L.polygon(points, {
                        color: '#15803d',
                        fillColor: '#86efac',
                        fillOpacity: 0.4,
                        weight: 3
                    }).addTo(map);
                }
            }

            function syncCoordinates() {
                input.value = points.length ? JSON.stringify(points) : '';
                status.textContent = points.length === 0
                    ? 'Belum ada titik batas.'
                    : points.length < 3
                        ? `${points.length} titik; tambahkan minimal ${3 - points.length} titik lagi.`
                        : `${points.length} titik; poligon terbentuk.`;
            }

            function addPoint(latitude, longitude) {
                const point = [Number(latitude.toFixed(6)), Number(longitude.toFixed(6))];
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
                syncCoordinates();
            }

            map.on('click', event => addPoint(event.latlng.lat, event.latlng.lng));

            document.getElementById('polygon-undo').addEventListener('click', () => {
                const marker = markers.pop();
                if (marker) {
                    map.removeLayer(marker);
                    points.pop();
                    drawPolygon();
                    syncCoordinates();
                }
            });

            document.getElementById('polygon-reset').addEventListener('click', () => {
                markers.forEach(marker => map.removeLayer(marker));
                markers.length = 0;
                points.length = 0;
                drawPolygon();
                syncCoordinates();
            });

            form.addEventListener('submit', event => {
                syncCoordinates();
                if (points.length > 0 && points.length < 3) {
                    event.preventDefault();
                    alert('Tambahkan minimal 3 titik untuk membentuk poligon, atau reset titik untuk menyimpan tanpa poligon.');
                }
            });

            try {
                const previousPoints = JSON.parse(input.value || '[]');
                if (Array.isArray(previousPoints)) {
                    previousPoints.forEach(point => {
                        if (
                            Array.isArray(point)
                            && point.length === 2
                            && Number.isFinite(Number(point[0]))
                            && Number.isFinite(Number(point[1]))
                        ) {
                            addPoint(Number(point[0]), Number(point[1]));
                        }
                    });
                }
                if (polygon) {
                    map.fitBounds(polygon.getBounds());
                }
            } catch (error) {
                console.error('Gagal memuat koordinat batas lahan.', error);
                status.textContent = 'Koordinat batas lahan tidak dapat dimuat.';
            }

            syncCoordinates();
        })();
    </script>
@endpush