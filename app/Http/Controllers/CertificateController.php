<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LandCertificate;

class CertificateController extends Controller
{
    // Menampilkan daftar dokumen
    public function index()
    {
        $certificates = LandCertificate::all();
        return view('certificates.index', compact('certificates'));
    }

    // Menampilkan form unggah dokumen
    public function create()
    {
        return view('certificates.create');
    }

    // Memproses unggahan dokumen
    public function store(Request $request)
    {
        $request->validate([
            'farmer_id' => 'required|string',
            'certification_type' => 'required|in:ISPO,RSPO,SKGR,Lainnya',
            'document' => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $path = $request->file('document')->store('legalitas_lahan', 'public');

        LandCertificate::create([
            'farmer_id' => $request->farmer_id,
            'document_name' => $request->file('document')->getClientOriginalName(),
            'file_path' => $path,
            'certification_type' => $request->certification_type,
        ]);

        return redirect()->route('certificates.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    // Menghapus dokumen
    public function destroy($id)
    {
        $certificate = LandCertificate::findOrFail($id);

        if (Storage::disk('public')->exists($certificate->file_path)) {
            Storage::disk('public')->delete($certificate->file_path);
        }

        $certificate->delete();
        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}
