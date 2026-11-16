<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentReviewController extends Controller
{
    public function index(): View
    {
        $documents = Document::query()
            ->with(['lahan.supplier', 'plot.supplier', 'reviewedBy'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(20);

        return view('officer.documents.index', compact('documents'));
    }

    public function review(Request $request, Document $document): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['approved', 'rejected'])],
            'review_notes' => ['nullable', 'string', 'max:2000', 'required_if:status,rejected'],
        ], [
            'review_notes.required_if' => 'Alasan wajib diisi saat dokumen ditolak.',
        ]);

        if ($document->status !== 'pending') {
            return back()->with('error', 'Hanya dokumen yang masih menunggu yang dapat diputuskan.');
        }

        if ($validated['status'] === 'approved' && $document->expires_at?->lt(today())) {
            return back()
                ->withErrors(['status' => 'Dokumen yang sudah kedaluwarsa tidak dapat disetujui.'])
                ->withInput();
        }

        if ($validated['status'] === 'approved' && (! $document->file_path || ! Storage::disk('local')->exists($document->file_path))) {
            return back()
                ->withErrors(['status' => 'Berkas dokumen tidak tersedia sehingga tidak dapat disetujui.'])
                ->withInput();
        }

        DB::transaction(function () use ($document, $validated, $request): void {
            $document->update([
                'status' => $validated['status'],
                'reviewed_by' => $request->user()->id,
                'reviewed_at' => now(),
                'review_notes' => isset($validated['review_notes']) ? trim($validated['review_notes']) : null,
            ]);

            if ($validated['status'] !== 'rejected') {
                return;
            }

            $supplier = $document->lahan?->supplier ?? $document->plot?->supplier;
            if (! $supplier || $supplier->status !== Supplier::STATUS_ACTIVE) {
                return;
            }

            $reason = 'Dokumen '.$document->type.' ditolak: '.$validated['review_notes'];
            $supplier->update([
                'status' => Supplier::STATUS_INACTIVE,
                'deactivation_reason' => $reason,
                'deactivated_at' => now(),
            ]);
            $supplier->statusLogs()->create([
                'user_id' => $request->user()->id,
                'from_status' => Supplier::STATUS_ACTIVE,
                'to_status' => Supplier::STATUS_INACTIVE,
                'reason' => $reason,
            ]);
        });

        return back()->with('success', $validated['status'] === 'approved'
            ? 'Dokumen legalitas disetujui.'
            : 'Dokumen legalitas ditolak dan status pemasok aktif dinonaktifkan.');
    }

    public function download(Document $document): StreamedResponse
    {
        abort_unless($document->file_path && Storage::disk('local')->exists($document->file_path), 404);

        return Storage::disk('local')->download(
            $document->file_path,
            basename($document->original_filename ?: $document->file_path),
        );
    }
}
