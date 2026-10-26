<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pemetaan Batas Lahan - Sawit Maju Makmur</title>
    <!-- Leaflet CSS CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <style>
        :root {
            color-scheme: light;
            --primary: #15803d;
            --primary-hover: #166534;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --border: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            background: var(--bg);
            color: var(--text-main);
            line-height: 1.5;
        }

        .page-container {
            max-width: 980px;
            margin: 32px auto;
            padding: 0 16px;
        }

        .card {
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border);
            padding: 28px;
        }

        .header {
            margin-bottom: 24px;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 1.625rem;
            font-weight: 700;
            color: #14532d;
        }

        p.description {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .alert-success {
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-error {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert-error ul {
            margin: 6px 0 0;
            padding-left: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        input[type="text"]:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(21, 128, 61, 0.15);
        }

        .map-wrapper {
            position: relative;
            margin-bottom: 16px;
        }

        #map {
            height: 480px;
            width: 100%;
            border-radius: 10px;
            border: 1px solid var(--border);
            z-index: 1;
        }

        .map-status-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 24px;
        }

        button {
            border: 0;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        button[type="submit"] {
            background: var(--primary);
            color: #ffffff;
        }

        button[type="submit"]:hover {
            background: var(--primary-hover);
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #334155;
        }

        .btn-secondary:hover {
            background: #cbd5e1;
        }

        .btn-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-danger:hover {
            background: #fecaca;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <div class="card">
            <div class="header">
                <h1>Pemetaan Batas Lahan</h1>
                <p class="description">
                    Tentukan batas kebun kelapa sawit dengan menandai titik-titik koordinat pada peta interaktif di bawah ini. Minimal butuh 3 titik untuk membentuk poligon area.
                </p>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <strong>Periksa kembali form Anda:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('land-areas.store') }}" id="land-area-form">
                @csrf

                <div class="form-group">
                    <label for="name">Nama Lahan / Blok Kebun</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Contoh: Kebun Sawit Plasma Blok B"
                        value="{{ old('name') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Peta Batas Koordinat Lahan</label>
                    <div class="map-wrapper">
                        <div id="map"></div>
                    </div>

                    <!-- Hidden input penampung data koordinat format JSON -->
                    <input type="hidden" name="polygon_coordinates" id="coordinates" value="{{ old('polygon_coordinates') }}">

                    <div class="map-status-bar">
                        <div>
                            Status Titik:
                            <span id="point-badge" class="badge badge-warning">0 Titik (Belum Membentuk Poligon)</span>
                        </div>
                        <div>
                            <em>Panduan: Klik peta untuk membuat titik batas lahan.</em>
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" id="btn-submit">Simpan Batas Lahan</button>
                    <button type="button" class="btn-secondary" id="btn-undo">Hapus Titik Terakhir</button>
                    <button type="button" class="btn-danger" id="btn-reset">Reset Titik</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Leaflet JS CDN -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Inisialisasi peta Leaflet berpusat di wilayah perkebunan kelapa sawit Riau
        const defaultLat = -0.5897;
        const defaultLng = 101.3431;
        const defaultZoom = 12;

        const map = L.map('map').setView([defaultLat, defaultLng], defaultZoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const coordinatesInput = document.getElementById('coordinates');
        const pointBadge = document.getElementById('point-badge');
        const btnUndo = document.getElementById('btn-undo');
        const btnReset = document.getElementById('btn-reset');
        const form = document.getElementById('land-area-form');

        let points = [];
        const markers = [];
        let polygon = null;

        // Muat titik sebelumnya jika ada (misal setelah validasi gagal)
        try {
            const previous = JSON.parse(coordinatesInput.value || '[]');
            if (Array.isArray(previous) && previous.length > 0) {
                previous.forEach(function (pt) {
                    if (Array.isArray(pt) && pt.length >= 2) {
                        addPoint(pt[0], pt[1], false);
                    }
                });
                syncCoordinates();
                if (polygon) {
                    map.fitBounds(polygon.getBounds());
                }
            }
        } catch (e) {
            coordinatesInput.value = '';
        }

        updateStatus();

        // Event klik pada peta untuk menambahkan koordinat poligon
        map.on('click', function (e) {
            addPoint(e.latlng.lat, e.latlng.lng, true);
        });

        // Hapus titik terakhir (Undo)
        btnUndo.addEventListener('click', function () {
            if (points.length === 0) return;

            points.pop();
            const lastMarker = markers.pop();
            if (lastMarker) {
                map.removeLayer(lastMarker);
            }

            drawPolygon();
            syncCoordinates();
            updateStatus();
        });

        // Reset seluruh titik
        btnReset.addEventListener('click', function () {
            if (points.length === 0) return;

            clearMapLayers();
            points = [];
            syncCoordinates();
            updateStatus();
        });

        // Validasi dan sinkronisasi sebelum submit form
        form.addEventListener('submit', function (e) {
            syncCoordinates();

            if (points.length < 3) {
                e.preventDefault();
                alert('Silakan klik minimal 3 titik koordinat pada peta untuk membentuk area poligon batas lahan.');
            }
        });

        function addPoint(lat, lng, sync) {
            const point = [parseFloat(lat.toFixed(6)), parseFloat(lng.toFixed(6))];
            points.push(point);

            const marker = L.circleMarker(point, {
                radius: 6,
                color: '#15803d',
                fillColor: '#22c55e',
                fillOpacity: 0.9,
                weight: 2
            }).addTo(map);

            marker.bindTooltip(`Titik ${points.length}: ${point[0]}, ${point[1]}`, {
                direction: 'top'
            });

            markers.push(marker);
            drawPolygon();

            if (sync) {
                syncCoordinates();
                updateStatus();
            }
        }

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
            coordinatesInput.value = points.length > 0 ? JSON.stringify(points) : '';
        }

        function clearMapLayers() {
            markers.forEach(function (marker) {
                map.removeLayer(marker);
            });
            markers.length = 0;

            if (polygon) {
                map.removeLayer(polygon);
                polygon = null;
            }
        }

        function updateStatus() {
            const count = points.length;
            if (count === 0) {
                pointBadge.className = 'badge badge-warning';
                pointBadge.textContent = '0 Titik (Belum Ada Poligon)';
            } else if (count < 3) {
                pointBadge.className = 'badge badge-warning';
                pointBadge.textContent = `${count} Titik (Butuh minimal 3 titik)`;
            } else {
                pointBadge.className = 'badge badge-success';
                pointBadge.textContent = `${count} Titik (Poligon Terbentuk)`;
            }
        }
    </script>
</body>
</html>
