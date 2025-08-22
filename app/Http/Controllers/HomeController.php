<?php
namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\SuratPersetujuan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $jenisSurat = JenisSurat::where('aktif', true)->get();
        return view('welcome', compact('jenisSurat'));
    }

    public function tracking()
    {
        return view('tracking');
    }

    public function cekStatus($id)
    {
        $surat = SuratPersetujuan::with('jenisSurat')->find($id);
        
        if (!$surat) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Surat tidak ditemukan'
            ]);
        }

        return response()->json([
            'status' => 'found',
            'data' => $surat
        ]);
    }

    public function trackByNomor(Request $request)
    {
        $nomor_surat = $request->input('nomor_surat');
        
        if (!$nomor_surat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nomor surat harus diisi'
            ]);
        }
        
        $surat = SuratPersetujuan::where('nomor_surat', $nomor_surat)
                                ->with('jenisSurat')
                                ->first();
        
        if (!$surat) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Nomor surat tidak ditemukan'
            ]);
        }

        return response()->json([
            'status' => 'found',
            'data' => $surat
        ]);
    }
}