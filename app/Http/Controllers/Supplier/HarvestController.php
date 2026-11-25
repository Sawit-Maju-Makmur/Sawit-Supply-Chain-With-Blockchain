<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Harvest;
use App\Models\Lahan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller QR Code Blockchain Hasil Panen (PBI: Pemasok).
 */
class HarvestController extends Controller
{
    // ─── Index: daftar semua panen milik pemasok ─────────────────────────────

    public function index(Request $request): View
    {
        $supplier = $request->user()->ensureSupplier();

        $harvests = Harvest::where('supplier_id', $supplier->id)
            ->with('lahan')
            ->latest()
            ->paginate(10);

        return view('supplier.harvests.index', compact('harvests', 'supplier'));
    }

    // ─── Create ───────────────────────────────────────────────────────────────

    public function create(Request $request): View
    {
        $supplier = $request->user()->ensureSupplier();

        $lahans = Lahan::where('supplier_id', $supplier->id)
            ->orderBy('nama_kebun')
            ->get();

        return view('supplier.harvests.create', compact('supplier', 'lahans'));
    }

    // ─── Store: simpan panen & catat ke blockchain ────────────────────────────

    public function store(Request $request): RedirectResponse
    {
        $supplier = $request->user()->ensureSupplier();

        $validated = $request->validate([
            'lahan_id'        => ['required', 'integer', 'exists:lahans,id'],
            'jenis_komoditas' => ['required', 'string', 'max:100'],
            'jumlah_kg'       => ['required', 'numeric', 'min:0.01', 'max:999999'],
            'tanggal_panen'   => ['required', 'date', 'before_or_equal:today'],
            'keterangan'      => ['nullable', 'string', 'max:500'],
        ]);

        // Pastikan lahan milik supplier ini
        abort_unless(
            Lahan::where('id', $validated['lahan_id'])
                ->where('supplier_id', $supplier->id)
                ->exists(),
            403,
            'Lahan tidak ditemukan atau bukan milik Anda.'
        );

        $harvest = Harvest::create([
            ...$validated,
            'supplier_id'      => $supplier->id,
            'blockchain_status' => 'pending',
        ]);

        // Simulasi pencatatan blockchain (dapat diganti dengan job/queue)
        $harvest->recordToBlockchain();

        return redirect()
            ->route('pemasok.harvests.show', $harvest)
            ->with('success', 'Hasil panen berhasil dicatat dan QR Code telah diterbitkan.');
    }

    // ─── Show: detail + tampilkan QR Code ────────────────────────────────────

    public function show(Request $request, Harvest $harvest): View
    {
        $supplier = $request->user()->ensureSupplier();
        abort_unless($harvest->supplier_id === $supplier->id, 404);

        $harvest->load('lahan', 'supplier');

        return view('supplier.harvests.show', compact('harvest'));
    }

    // ─── Verify (public): scan QR → lihat detail tanpa login ─────────────────

    public function verify(string $token): View
    {
        $harvest = Harvest::where('qr_token', $token)
            ->with('lahan', 'supplier')
            ->firstOrFail();

        return view('supplier.harvests.verify', compact('harvest'));
    }

    // ─── Download QR Code image (SVG data-url) ────────────────────────────────

    public function downloadQr(Request $request, Harvest $harvest)
    {
        $supplier = $request->user()->ensureSupplier();
        abort_unless($harvest->supplier_id === $supplier->id, 404);

        // QR Code dihasilkan client-side via qrcodejs di view,
        // endpoint ini hanya fallback / print page
        return redirect()->route('pemasok.harvests.show', $harvest);
    }
}
