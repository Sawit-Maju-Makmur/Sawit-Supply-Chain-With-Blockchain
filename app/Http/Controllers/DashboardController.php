<?php

namespace App\Http\Controllers;

use App\Models\Document;
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
        $supplier = Supplier::query()->where('user_id', $user->id)->first()
            ?? Supplier::query()->where('email', $user->email)->first();
        $plotQuery = $supplier?->plots();
        $documents = Document::query()
            ->whereHas('plot', fn ($query) => $query->where('supplier_id', $supplier?->id));

        return view('dashboards.pemasok', [
            'supplier' => $supplier,
            'plotCount' => $plotQuery?->count() ?? 0,
            'landAreaCount' => LandArea::query()->where('user_id', $user->id)->count(),
            'documentCount' => (clone $documents)->count(),
            'pendingDocumentCount' => (clone $documents)->where('status', 'pending')->count(),
            'recentDocuments' => (clone $documents)->with('plot')->latest()->take(5)->get(),
            'recentPlots' => $plotQuery?->latest()->take(3)->get() ?? collect(),
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
        return [
            'supplierCount' => Supplier::query()->count(),
            'activeSupplierCount' => Supplier::operable()->count(),
            'plotCount' => Plot::query()->count(),
            'documentCount' => Document::query()->count(),
            'pendingDocumentCount' => Document::query()->where('status', 'pending')->count(),
            'rejectedDocumentCount' => Document::query()->where('status', 'rejected')->count(),
            'recentSuppliers' => Supplier::query()->latest()->take(5)->get(),
            'recentDocuments' => Document::query()->with('plot.supplier')->latest()->take(5)->get(),
        ];
    }
}
