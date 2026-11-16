<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Plot;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SupplierDocumentController extends Controller
{
    public function index(Request $request): View
    {
        $supplier = $this->supplierFor($request->user());
        $plots = $supplier->plots()->with('documents')->latest()->get();

        return view('supplier.documents.index', compact('supplier', 'plots'));
    }

    public function store(Request $request, Plot $plot): RedirectResponse
    {
        $supplier = $this->supplierFor($request->user());
        abort_unless($plot->supplier_id === $supplier->id, 404);

        $validated = $request->validate([
            'type' => ['required', 'string', 'max:100'],
            'expires_at' => ['nullable', 'date'],
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $file = $request->file('file');
        $path = $file->store('legal-documents/'.$supplier->id, 'local');
        if (! $path) {
            throw new RuntimeException('Berkas legalitas gagal disimpan.');
        }

        $plot->documents()->create([
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
        $supplier = $this->supplierFor($request->user());
        abort_unless($document->plot()->where('supplier_id', $supplier->id)->exists(), 404);

        return $this->downloadDocument($document);
    }

    private function supplierFor(User $user): Supplier
    {
        return Supplier::query()->where('user_id', $user->id)->first()
            ?? Supplier::query()->where('email', $user->email)->firstOrFail();
    }

    private function downloadDocument(Document $document): StreamedResponse
    {
        abort_unless($document->file_path && Storage::disk('local')->exists($document->file_path), 404);

        return Storage::disk('local')->download(
            $document->file_path,
            basename($document->original_filename ?: $document->file_path),
        );
    }
}
