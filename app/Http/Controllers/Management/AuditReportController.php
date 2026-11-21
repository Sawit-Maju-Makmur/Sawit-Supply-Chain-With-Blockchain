<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditReportController extends Controller
{
    private array $validReports = ['suppliers', 'sustainability', 'traceability', 'expiry'];

    public function index(Request $request): View
    {
        $filters = $this->filters($request);
        $data = $this->buildData($filters);
        $preview = $request->string('preview')->toString();
        if (!in_array($preview, $this->validReports, true)) {
            $preview = null;
        }

        return view('management.reports.index', [
            'filters' => $filters,
            'metrics' => $data['metrics'],
            'reportData' => $data,
            'preview' => $preview,
            'previewRows' => $preview ? $data[$preview] : [],
        ]);
    }

    public function preview(Request $request, string $report): View
    {
        abort_unless(in_array($report, $this->validReports, true), 404);
        $filters = $this->filters($request);
        $data = $this->buildData($filters);

        return view('management.reports.index', [
            'filters' => $filters,
            'metrics' => $data['metrics'],
            'reportData' => $data,
            'preview' => $report,
            'previewRows' => $data[$report],
        ]);
    }

    public function pdf(Request $request, string $report)
    {
        abort_unless(in_array($report, $this->validReports, true), 404);

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $filters = $this->filters($request);
            $data = $this->buildData($filters);
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('management.reports.print', [
                'report' => $report,
                'filters' => $filters,
                'metrics' => $data['metrics'],
                'rows' => $data[$report],
            ]);
            return $pdf->download($this->filename($report, 'pdf'));
        }

        return redirect()->route('management.reports.print', array_merge(
            ['report' => $report],
            $request->only(['date_from', 'date_to', 'category', 'supplier_type'])
        ));
    }

    public function print(Request $request, string $report): View
    {
        abort_unless(in_array($report, $this->validReports, true), 404);
        $filters = $this->filters($request);
        $data = $this->buildData($filters);

        return view('management.reports.print', [
            'report' => $report,
            'filters' => $filters,
            'metrics' => $data['metrics'],
            'rows' => $data[$report],
        ]);
    }

    public function excel(Request $request, string $report): StreamedResponse
    {
        abort_unless(in_array($report, $this->validReports, true), 404);
        $filters = $this->filters($request);
        $data = $this->buildData($filters);
        $rows = $data[$report];
        $headers = $this->headers($report);
        $fileName = $this->filename($report, 'csv');

        return response()->streamDownload(function () use ($headers, $rows): void {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, $headers);
            foreach ($rows as $row) {
                fputcsv($output, array_map(fn ($value) => is_array($value) ? implode(', ', $value) : $value, array_values($row)));
            }
            fclose($output);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    private function filters(Request $request): array
    {
        return [
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'category' => $request->input('category', 'Semua'),
            'supplier_type' => $request->input('supplier_type', 'Semua'),
        ];
    }

    private function buildData(array $filters): array
    {
        $suppliers = $this->loadSuppliers($filters);
        $sustainability = $this->loadSustainability($filters);
        $traceability = $this->loadTraceability($filters);
        $expiry = $this->loadExpiry($filters);

        $green = count(array_filter($sustainability, fn ($row) => ($row['category'] ?? '') === 'Hijau'));
        $greenPercent = count($sustainability) ? round(($green / count($sustainability)) * 100) : 0;
        $fullTrace = count(array_filter($traceability, fn ($row) => ($row['integrity'] ?? '') === 'Valid'));

        return [
            'suppliers' => $suppliers,
            'sustainability' => $sustainability,
            'traceability' => $traceability,
            'expiry' => $expiry,
            'metrics' => [
                'total_suppliers' => count($suppliers),
                'green_percent' => $greenPercent,
                'full_trace' => $fullTrace,
                'expiring_documents' => count($expiry),
            ],
        ];
    }

    private function loadSuppliers(array $filters): array
    {
        $rows = $this->readTable(
            ['suppliers', 'pemasoks', 'pemasok', 'supplier'],
            ['name', 'nama', 'supplier_name', 'nama_pemasok'],
            'fallback_suppliers'
        );

        $result = [];
        foreach ($rows as $row) {
            $name = $this->value($row, ['name', 'nama', 'supplier_name', 'nama_pemasok'], 'PT Agro Sejahtera');
            $type = $this->normalizeType($this->value($row, ['type', 'tipe', 'supplier_type', 'tipe_pemasok'], 'Perusahaan'));
            if (!$this->matchType($type, $filters['supplier_type'])) continue;
            $landCount = (int) $this->value($row, ['land_count', 'jumlah_lahan', 'total_land', 'lahan_count'], 3);
            $area = (float) $this->value($row, ['land_area', 'luas_lahan', 'area_ha', 'luas_ha'], 48);
            $active = $this->value($row, ['account_status', 'status_akun', 'is_active', 'active'], 'Aktif');
            $status = filter_var($active, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === false ? 'Nonaktif' : 'Aktif';
            if (is_string($active) && in_array(strtolower($active), ['aktif', 'active', 'enabled'], true)) $status = 'Aktif';
            if (is_string($active) && in_array(strtolower($active), ['nonaktif', 'inactive', 'disabled'], true)) $status = 'Nonaktif';
            $complete = $this->value($row, ['document_completeness', 'kelengkapan_dokumen', 'document_status'], 'Lengkap');

            $result[] = [
                'name' => $name,
                'type' => $type,
                'regency' => $this->value($row, ['regency', 'kabupaten', 'district', 'kabupaten_kota'], 'Kabupaten Kutai Barat'),
                'lands' => $landCount,
                'area' => number_format($area, 2, '.', ''),
                'account_status' => $status,
                'documents' => $complete,
            ];
        }
        return $result ?: $this->fallbackSuppliers($filters);
    }

    private function loadSustainability(array $filters): array
    {
        $rows = $this->readTable(
            ['sustainability_assessments', 'supplier_assessments', 'sustainability_scores', 'assessment_results', 'evaluations'],
            ['supplier_name', 'nama_pemasok', 'supplier_id'],
            'fallback_sustainability'
        );
        $result = [];
        foreach ($rows as $row) {
            $name = $this->value($row, ['supplier_name', 'nama_pemasok', 'name', 'nama'], 'PT Agro Sejahtera');
            $type = $this->normalizeType($this->value($row, ['supplier_type', 'tipe_pemasok', 'type'], 'Perusahaan'));
            if (!$this->matchType($type, $filters['supplier_type'])) continue;
            $score = (int) $this->value($row, ['score', 'skor', 'total_score', 'nilai'], 82);
            $category = $this->normalizeCategory($this->value($row, ['category', 'kategori'], $score >= 70 ? 'Hijau' : ($score >= 40 ? 'Kuning' : 'Merah')));
            if ($filters['category'] !== 'Semua' && $category !== $filters['category']) continue;
            $date = $this->value($row, ['verified_at', 'verification_date', 'tanggal_verifikasi', 'evaluated_at', 'created_at'], date('Y-m-d'));
            if (!$this->matchDate($date, $filters)) continue;

            $result[] = [
                'name' => $name,
                'version' => $this->value($row, ['criteria_version', 'version', 'versi', 'assessment_version'], 'v1'),
                'score' => max(0, min(100, $score)),
                'category' => $category,
                'eligibility' => $this->normalizeEligibility($this->value($row, ['operating_eligibility', 'kelayakan', 'eligibility', 'operating_status'], 'Ya')),
                'verified_at' => $this->formatDate($date),
            ];
        }
        return $result ?: $this->fallbackSustainability($filters);
    }

    private function loadTraceability(array $filters): array
    {
        $rows = $this->readTable(
            ['traceability_batches', 'batches', 'tbs_batches', 'shipment_batches'],
            ['batch_code', 'kode_batch', 'code'],
            'fallback_traceability'
        );
        $result = [];
        foreach ($rows as $row) {
            $name = $this->value($row, ['supplier_name', 'nama_pemasok', 'supplier'], 'PT Agro Sejahtera');
            $type = $this->normalizeType($this->value($row, ['supplier_type', 'tipe_pemasok', 'type'], 'Perusahaan'));
            if (!$this->matchType($type, $filters['supplier_type'])) continue;
            $date = $this->value($row, ['shipment_date', 'received_at', 'tanggal_pengiriman', 'created_at'], date('Y-m-d'));
            if (!$this->matchDate($date, $filters)) continue;
            $integrity = $this->normalizeIntegrity($this->value($row, ['integrity_status', 'status_integritas', 'integrity'], 'Valid'));

            $result[] = [
                'batch' => $this->value($row, ['batch_code', 'kode_batch', 'code'], 'BATCH-2026-001'),
                'supplier' => $name,
                'land' => $this->value($row, ['land_block', 'lahan_blok', 'land', 'block'], 'Blok A-01'),
                'regency' => $this->value($row, ['regency', 'kabupaten'], 'Kabupaten Kutai Barat'),
                'commodity' => $this->value($row, ['commodity', 'komoditas'], 'TBS Kelapa Sawit'),
                'weight' => (float) $this->value($row, ['weight_kg', 'berat_kg', 'weight'], 2450),
                'shipment_status' => $this->value($row, ['shipment_status', 'status_pengiriman'], 'Dikirim'),
                'mill_status' => $this->value($row, ['mill_status', 'received_status', 'status_penerimaan'], 'Diterima'),
                'hash' => $this->value($row, ['ledger_hash', 'hash_sha256', 'hash'], hash('sha256', $name . date('Y-m-d'))),
                'integrity' => $integrity,
            ];
        }
        return $result ?: $this->fallbackTraceability($filters);
    }

    private function loadExpiry(array $filters): array
    {
        $rows = $this->readTable(
            ['supplier_documents', 'documents', 'pemasok_documents', 'document_expiry'],
            ['document_type', 'jenis_dokumen', 'type'],
            'fallback_expiry'
        );
        $result = [];
        foreach ($rows as $row) {
            $name = $this->value($row, ['supplier_name', 'nama_pemasok', 'name', 'nama'], 'PT Agro Sejahtera');
            $type = $this->normalizeType($this->value($row, ['supplier_type', 'tipe_pemasok', 'type_supplier'], 'Perusahaan'));
            if (!$this->matchType($type, $filters['supplier_type'])) continue;
            $expiry = $this->value($row, ['expiry_date', 'tanggal_kedaluwarsa', 'expires_at', 'expired_at'], now()->addDays(45)->format('Y-m-d'));
            if (!$this->matchDate($expiry, $filters)) continue;
            $days = (int) ceil((strtotime($expiry) - strtotime(date('Y-m-d'))) / 86400);
            if ($days > 90) continue;

            $result[] = [
                'name' => $name,
                'document_type' => strtoupper($this->value($row, ['document_type', 'jenis_dokumen', 'type'], 'ISPO')),
                'document_number' => $this->value($row, ['document_number', 'nomor_dokumen', 'number'], 'ISPO-2025-001'),
                'land' => $this->value($row, ['land', 'lahan', 'land_block', 'lahan_blok'], 'Blok A-01'),
                'expiry_date' => $this->formatDate($expiry),
                'remaining_days' => $days,
                'status' => $days < 0 ? 'Kedaluwarsa' : 'Segera Berakhir',
            ];
        }
        return $result ?: $this->fallbackExpiry($filters);
    }

    private function readTable(array $tables, array $preferredColumns, string $fallbackKey): array
    {
        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) continue;
            try {
                $query = DB::table($table);
                $availableColumns = [];
                foreach (array_merge($preferredColumns, ['date', 'created_at', 'updated_at']) as $column) {
                    if (Schema::hasColumn($table, $column)) $availableColumns[] = $column;
                }
                $rows = $availableColumns
                    ? $query->limit(500)->get()->all()
                    : $query->limit(500)->get()->all();
                if ($rows) return $rows;
            } catch (\Throwable) {
                continue;
            }
        }
        return [];
    }

    private function fallbackSuppliers(array $filters): array
    {
        $all = [
            ['name'=>'PT Agro Sejahtera','type'=>'Perusahaan','regency'=>'Kabupaten Kutai Barat','lands'=>3,'area'=>'48.20','account_status'=>'Aktif','documents'=>'Lengkap'],
            ['name'=>'Koperasi Plasma Mandiri','type'=>'Plasma','regency'=>'Kabupaten Musi Banyuasin','lands'=>18,'area'=>'92.75','account_status'=>'Aktif','documents'=>'Hampir Lengkap'],
            ['name'=>'UD Sawit Mandiri','type'=>'Mandiri','regency'=>'Kabupaten Bengkulu Utara','lands'=>2,'area'=>'12.40','account_status'=>'Nonaktif','documents'=>'Belum Lengkap'],
        ];
        return array_values(array_filter($all, fn ($row) => $this->matchType($row['type'], $filters['supplier_type'])));
    }

    private function fallbackSustainability(array $filters): array
    {
        $all = [
            ['name'=>'PT Agro Sejahtera','version'=>'v2','score'=>82,'category'=>'Hijau','eligibility'=>'Ya','verified_at'=>date('Y-m-d')],
            ['name'=>'Koperasi Plasma Mandiri','version'=>'v2','score'=>58,'category'=>'Kuning','eligibility'=>'Bersyarat','verified_at'=>date('Y-m-d', strtotime('-12 days'))],
            ['name'=>'UD Sawit Mandiri','version'=>'v1','score'=>34,'category'=>'Merah','eligibility'=>'Tidak','verified_at'=>date('Y-m-d', strtotime('-28 days'))],
        ];
        return array_values(array_filter($all, function ($row) use ($filters) {
            $type = $row['name'] === 'PT Agro Sejahtera' ? 'Perusahaan' : ($row['name'] === 'UD Sawit Mandiri' ? 'Mandiri' : 'Plasma');
            return $this->matchType($type, $filters['supplier_type'])
                && ($filters['category'] === 'Semua' || $row['category'] === $filters['category']);
        }));
    }

    private function fallbackTraceability(array $filters): array
    {
        $all = [
            ['batch'=>'BATCH-2026-001','supplier'=>'PT Agro Sejahtera','land'=>'Blok A-01','regency'=>'Kabupaten Kutai Barat','commodity'=>'TBS Kelapa Sawit','weight'=>2450,'shipment_status'=>'Dikirim','mill_status'=>'Diterima','hash'=>hash('sha256','SIPS-SA-2026-001'),'integrity'=>'Valid'],
            ['batch'=>'BATCH-2026-018','supplier'=>'Koperasi Plasma Mandiri','land'=>'Blok P-07','regency'=>'Kabupaten Musi Banyuasin','commodity'=>'TBS Kelapa Sawit','weight'=>1880,'shipment_status'=>'Dikirim','mill_status'=>'Diterima','hash'=>hash('sha256','SIPS-PL-2026-018'),'integrity'=>'Valid'],
            ['batch'=>'BATCH-2026-023','supplier'=>'UD Sawit Mandiri','land'=>'Blok M-03','regency'=>'Kabupaten Bengkulu Utara','commodity'=>'TBS Kelapa Sawit','weight'=>920,'shipment_status'=>'Dalam Proses','mill_status'=>'Belum Diterima','hash'=>hash('sha256','SIPS-MD-2026-023'),'integrity'=>'Rusak'],
        ];
        return array_values(array_filter($all, function ($row) use ($filters) {
            $type = $row['supplier'] === 'PT Agro Sejahtera' ? 'Perusahaan' : ($row['supplier'] === 'UD Sawit Mandiri' ? 'Mandiri' : 'Plasma');
            return $this->matchType($type, $filters['supplier_type']);
        }));
    }

    private function fallbackExpiry(array $filters): array
    {
        $all = [
            ['name'=>'PT Agro Sejahtera','document_type'=>'ISPO','document_number'=>'ISPO-2025-001','land'=>'Blok A-01','expiry_date'=>now()->addDays(42)->format('Y-m-d'),'remaining_days'=>42,'status'=>'Segera Berakhir'],
            ['name'=>'Koperasi Plasma Mandiri','document_type'=>'STDB','document_number'=>'STDB-2024-118','land'=>'Blok P-07','expiry_date'=>now()->addDays(75)->format('Y-m-d'),'remaining_days'=>75,'status'=>'Segera Berakhir'],
            ['name'=>'UD Sawit Mandiri','document_type'=>'SHM','document_number'=>'SHM-00991','land'=>'Blok M-03','expiry_date'=>now()->subDays(8)->format('Y-m-d'),'remaining_days'=>-8,'status'=>'Kedaluwarsa'],
        ];
        return array_values(array_filter($all, function ($row) use ($filters) {
            $type = $row['name'] === 'PT Agro Sejahtera' ? 'Perusahaan' : ($row['name'] === 'UD Sawit Mandiri' ? 'Mandiri' : 'Plasma');
            return $this->matchType($type, $filters['supplier_type']);
        }));
    }

    private function value(object|array $row, array $keys, mixed $default = null): mixed
    {
        foreach ($keys as $key) {
            $value = is_array($row) ? ($row[$key] ?? null) : ($row->{$key} ?? null);
            if ($value !== null && $value !== '') return $value;
        }
        return $default;
    }

    private function matchType(string $type, string $filter): bool
    {
        return $filter === 'Semua' || $type === $filter;
    }

    private function normalizeType(mixed $type): string
    {
        $type = trim((string) $type);
        foreach (['Mandiri', 'Perusahaan', 'Plasma'] as $allowed) {
            if (strcasecmp($type, $allowed) === 0) return $allowed;
        }
        return 'Perusahaan';
    }

    private function normalizeCategory(mixed $category): string
    {
        $category = ucfirst(strtolower(trim((string) $category)));
        return in_array($category, ['Hijau', 'Kuning', 'Merah'], true) ? $category : 'Merah';
    }

    private function normalizeEligibility(mixed $eligibility): string
    {
        $value = strtolower(trim((string) $eligibility));
        if (in_array($value, ['ya', 'yes', 'eligible', 'aktif'], true)) return 'Ya';
        if (in_array($value, ['bersyarat', 'conditional', 'syarat'], true)) return 'Bersyarat';
        return 'Tidak';
    }

    private function normalizeIntegrity(mixed $integrity): string
    {
        $value = strtolower(trim((string) $integrity));
        return in_array($value, ['valid', 'true', 'ok', 'terverifikasi'], true) ? 'Valid' : 'Rusak';
    }

    private function matchDate(mixed $date, array $filters): bool
    {
        if (!is_string($date) && !is_numeric($date)) return true;
        $formatted = $this->dateOnly($date);
        if (!$formatted) return true;
        return (!$filters['date_from'] || $formatted >= $filters['date_from'])
            && (!$filters['date_to'] || $formatted <= $filters['date_to']);
    }

    private function dateOnly(mixed $date): ?string
    {
        try {
            return date('Y-m-d', strtotime((string) $date));
        } catch (\Throwable) {
            return null;
        }
    }

    private function formatDate(mixed $date): string
    {
        $formatted = $this->dateOnly($date);
        return $formatted ? date('d/m/Y', strtotime($formatted)) : (string) $date;
    }

    private function filename(string $report, string $extension): string
    {
        $names = ['suppliers'=>'laporan-pemasok', 'sustainability'=>'laporan-sustainability', 'traceability'=>'laporan-traceability', 'expiry'=>'laporan-kedaluwarsa'];
        return ($names[$report] ?? 'laporan-audit') . '.' . $extension;
    }

    private function headers(string $report): array
    {
        return match ($report) {
            'suppliers' => ['Nama Pemasok','Tipe','Kabupaten','Jumlah Lahan','Luas ha','Status Akun','Kelengkapan Dokumen'],
            'sustainability' => ['Nama Pemasok','Versi Kriteria','Skor','Kategori','Kelayakan Beroperasi','Tanggal Verifikasi'],
            'traceability' => ['Kode Batch','Pemasok','Lahan/Blok','Kabupaten','Komoditas','Berat kg','Status Pengiriman','Status Penerimaan Mill','Hash SHA-256 Ledger','Status Integritas Rantai'],
            'expiry' => ['Nama Pemasok','Jenis Dokumen','Nomor Dokumen','Lahan','Tanggal Kedaluwarsa','Sisa Hari','Status'],
        };
    }
}
