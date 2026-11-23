<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Lahan;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Validation\Rule; 

class SupplierDocumentController extends Controller
{
    public function index(Request $request): View
    {
        $supplier = $request->user()->ensureSupplier();
        $lahans = $supplier->lahans()->with('documents')->latest()->get();

        return view('supplier.documents.index', compact('supplier', 'lahans'));
    }

    public function store(Request $request, Lahan $lahan): RedirectResponse
    {
        $supplier = $request->user()->ensureSupplier();
        abort_unless($lahan->supplier_id === $supplier->id, 404);

        $validated = $request->validate([
            'type' => ['required', Rule::in(array_keys(Document::TYPES))],
            'expires_at' => ['nullable', 'date'],
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $file = $request->file('file');
        $path = $file->store('legal-documents/'.$supplier->id, 'local');
        if (! $path) {
            throw new RuntimeException('Berkas legalitas gagal disimpan.');
        }

        $lahan->documents()->create([
            'type' => $validated['type'],
            'expires_at' => $validated['expires_at'] ?? null,
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'status' => 'pending',
        ]);

        return redirect()->route('pemasok.documents.index')
            ->with('success', 'Dokumen berhasil dikirim dan menunggu pemeriksaan.');
    }

    public function download(Request $request, Document $document): StreamedResponse
    {
        $supplier = $request->user()->ensureSupplier();
        abort_unless($this->ownedBy($document, $supplier), 404);
        abort_unless($document->file_path && Storage::disk('local')->exists($document->file_path), 404);

        return Storage::disk('local')->download(
            $document->file_path,
            basename($document->original_filename ?: $document->file_path),
        );
    }

    public function destroy(Request $request, Document $document): RedirectResponse
    {
        $supplier = $request->user()->ensureSupplier();
        abort_unless($this->ownedBy($document, $supplier), 404);

        if ($document->status === 'approved') {
            return back()->with('error', 'Dokumen yang sudah disetujui tidak dapat dihapus.');
        }

        $path = $document->file_path;
        $document->delete();

        if ($path) {
            Storage::disk('local')->delete($path);
        }

        return back()->with('success', 'Dokumen dihapus. Silakan unggah dokumen pengganti.');
    }

    private function ownedBy(Document $document, Supplier $supplier): bool
    {
        return $document->lahan?->supplier_id === $supplier->id
            || $document->plot?->supplier_id === $supplier->id;
    }
}