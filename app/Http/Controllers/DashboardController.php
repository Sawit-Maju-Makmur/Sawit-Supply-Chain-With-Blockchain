<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Lahan;
use App\Models\LandArea;
use App\Models\Plot;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function supplier(Request $request): View
    {
        $user = $request->user();
        $supplier = $user->ensureSupplier();
        $lahanQuery = $supplier->lahans();
        $documents = Document::query()
            ->where(function ($query) use ($supplier) {
                $query->whereHas('lahan', fn ($q) => $q->where('supplier_id', $supplier->id))
                      ->orWhereHas('plot', fn ($q) => $q->where('supplier_id', $supplier->id));
            });

        return view('dashboards.pemasok', [
            'supplier' => $supplier,
            'lahanCount' => $lahanQuery->count(),
            'plotCount' => $lahanQuery->count(),
            'landAreaCount' => LandArea::query()->where('user_id', $user->id)->count(),
            'documentCount' => (clone $documents)->count(),
            'pendingDocumentCount' => (clone $documents)->where('status', 'pending')->count(),
            'recentDocuments' => (clone $documents)->with(['lahan', 'plot'])->latest()->take(5)->get(),
            'recentLahans' => $lahanQuery->latest()->take(3)->get(),
            'recentPlots' => $lahanQuery->latest()->take(3)->get(),
            'recentLandAreas' => LandArea::query()->where('user_id', $user->id)->latest()->take(3)->get(),
        ]);
    }

    public function officer(): View
    {
        return view('dashboards.officer', $this->systemSummary());
    }

    public function management(): View
    {
        return view('dashboards.management', $this->systemSummary());
    }

    public function exporter(): View
    {
        return view('dashboards.eksportir', $this->systemSummary());
    }

    private function systemSummary(): array
    {
        $lahanCount = Lahan::query()->count();
        if ($lahanCount === 0) {
            $lahanCount = Plot::query()->count();
        }

        return [
            'supplierCount' => Supplier::query()->count(),
            'activeSupplierCount' => Supplier::operable()->count(),
            'lahanCount' => $lahanCount,
            'plotCount' => $lahanCount,
            'documentCount' => Document::query()->count(),
            'pendingDocumentCount' => Document::query()->where('status', 'pending')->count(),
            'rejectedDocumentCount' => Document::query()->where('status', 'rejected')->count(),
            'recentSuppliers' => Supplier::query()->latest()->take(5)->get(),
            'recentDocuments' => Document::query()->with(['lahan.supplier', 'plot.supplier'])->latest()->take(5)->get(),
        ];
    }
}
