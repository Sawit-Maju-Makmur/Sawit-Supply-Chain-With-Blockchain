<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Requests\Management\UpdateSupplierStatusRequest;
use App\Models\Document;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    private function filtered(Request $request)
    {
        return Supplier::query()
            ->search($request->query('q'))
            ->ofType($request->query('type'))
            ->ofStatus($request->query('status'));
    }

    private function canManage(Request $request): bool
    {
        return EnsureUserHasRole::roleOf($request->user()) === 'management'
            || (method_exists($request->user(), 'hasRole') && $request->user()->hasRole('management'));
    }

    // AC1 dan AC2: daftar pemasok dengan pencarian, filter, dan pagination
    public function index(Request $request)
    {
        $suppliers = $this->filtered($request)
            ->withCount('plots')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('management.suppliers.index', [
            'suppliers' => $suppliers,
            'types' => Supplier::TYPES,
            'statuses' => Supplier::STATUSES,
            'filters' => array_filter($request->only('q', 'type', 'status')),
            'canManage' => $this->canManage($request),
        ]);
    }

    // AC3: detail pemasok (data, lahan, ringkasan dokumen, riwayat status)
    public function show(Request $request, Supplier $supplier)
    {
        $supplier->load(['plots', 'statusLogs.actor']);

        $documentSummary = Document::query()
            ->whereIn('plot_id', $supplier->plots()->select('plots.id'))
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('management.suppliers.show', [
            'supplier' => $supplier,
            'documentSummary' => $documentSummary,
            'canManage' => $this->canManage($request),
        ]);
    }

    // AC4: aktifkan atau nonaktifkan pemasok, alasan wajib saat menonaktifkan, tercatat di log
    public function updateStatus(UpdateSupplierStatusRequest $request, Supplier $supplier)
    {
        $new = $request->validated('status');

        if ($supplier->status === $new) {
            return back()->with('error', 'Pemasok sudah berstatus '.strtolower(Supplier::STATUSES[$new]).'.');
        }

        $reason = $request->validated('reason');

        DB::transaction(function () use ($supplier, $new, $reason, $request) {
            $from = $supplier->status;

            $supplier->update([
                'status' => $new,
                'deactivation_reason' => $new === Supplier::STATUS_INACTIVE ? $reason : null,
                'deactivated_at' => $new === Supplier::STATUS_INACTIVE ? now() : null,
            ]);

            $supplier->statusLogs()->create([
                'user_id' => $request->user()->id,
                'from_status' => $from,
                'to_status' => $new,
                'reason' => $reason,
            ]);
        });

        return back()->with('success', $new === Supplier::STATUS_ACTIVE
            ? 'Pemasok berhasil diaktifkan.'
            : 'Pemasok berhasil dinonaktifkan dan tercatat di riwayat.');
    }

    // AC6: export mengikuti filter yang sedang dipakai (CSV pemisah titik koma, terbuka di Excel)
    public function export(Request $request)
    {
        $query = $this->filtered($request)->withCount('plots')->orderBy('name');
        $filename = 'data-pemasok-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8
            fputcsv($out, ['Nama', 'Tipe', 'Kabupaten', 'Jumlah lahan', 'Status', 'Email', 'Telepon', 'Tanggal daftar'], ';');

            foreach ($query->cursor() as $s) {
                fputcsv($out, array_map([$this, 'safe'], [
                    $s->name, $s->type_label, $s->regency, $s->plots_count,
                    $s->status_label, $s->email, $s->phone, optional($s->created_at)->format('d/m/Y'),
                ]), ';');
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /** Cegah CSV injection: sel yang diawali = + - @ diberi tanda petik. */
    private function safe($value): string
    {
        $value = (string) $value;

        return preg_match('/^[=+\-@\t\r]/', $value) ? "'".$value : $value;
    }
}

