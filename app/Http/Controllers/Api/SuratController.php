<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SuratPersetujuan;
use App\Models\JenisSurat;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SuratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = SuratPersetujuan::with('jenisSurat');
            
            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            // Filter by date range
            if ($request->has('start_date')) {
                $query->whereDate('tanggal_pengajuan', '>=', $request->start_date);
            }
            
            if ($request->has('end_date')) {
                $query->whereDate('tanggal_pengajuan', '<=', $request->end_date);
            }
            
            // Search by nomor surat or nama pemohon
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nomor_surat', 'like', "%{$search}%")
                      ->orWhere('nama_pemohon', 'like', "%{$search}%")
                      ->orWhere('nik', 'like', "%{$search}%");
                });
            }
            
            $surat = $query->orderBy('created_at', 'desc')->paginate(10);
            
            return response()->json([
                'success' => true,
                'message' => 'Data surat berhasil diambil',
                'data' => $surat
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'jenis_surat_id' => 'required|exists:jenis_surat,id',
                'nama_pemohon' => 'required|string|max:255',
                'nik' => 'required|string|max:16',
                'alamat' => 'required|string',
                'no_telp' => 'required|string|max:20',
                'email' => 'nullable|email',
                'keperluan' => 'required|string',
                'file_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
            ]);

            // Handle file upload
            $file_path = null;
            if ($request->hasFile('file_pendukung')) {
                $file = $request->file('file_pendukung');
                $file_path = $file->store('surat_pendukung', 'public');
            }

            $surat = SuratPersetujuan::createWithGeneratedNomor([
                'jenis_surat_id' => $request->jenis_surat_id,
                'nama_pemohon' => $request->nama_pemohon,
                'nik' => $request->nik,
                'alamat' => $request->alamat,
                'no_telp' => $request->no_telp,
                'email' => $request->email,
                'keperluan' => $request->keperluan,
                'file_pendukung' => $file_path,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Surat berhasil diajukan',
                'data' => [
                    'id' => $surat->id,
                    'nomor_surat' => $surat->nomor_surat,
                    'nama_pemohon' => $surat->nama_pemohon,
                    'status' => $surat->status,
                    'tanggal_pengajuan' => $surat->tanggal_pengajuan->format('Y-m-d H:i:s')
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengajukan surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $surat = SuratPersetujuan::with('jenisSurat')->find($id);
            
            if (!$surat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Surat tidak ditemukan'
                ], 404);
            }

            $data = [
                'id' => $surat->id,
                'nomor_surat' => $surat->nomor_surat,
                'jenis_surat' => [
                    'id' => $surat->jenisSurat->id,
                    'nama_surat' => $surat->jenisSurat->nama_surat
                ],
                'nama_pemohon' => $surat->nama_pemohon,
                'nik' => $surat->nik,
                'alamat' => $surat->alamat,
                'no_telp' => $surat->no_telp,
                'email' => $surat->email,
                'keperluan' => $surat->keperluan,
                'status' => $surat->status,
                'tanggal_pengajuan' => $surat->tanggal_pengajuan->format('Y-m-d H:i:s'),
                'tanggal_persetujuan' => $surat->tanggal_persetujuan ? $surat->tanggal_persetujuan->format('Y-m-d H:i:s') : null,
                'catatan' => $surat->catatan,
                'file_pendukung' => $surat->file_pendukung ? Storage::url($surat->file_pendukung) : null,
                'created_at' => $surat->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $surat->updated_at->format('Y-m-d H:i:s')
            ];

            return response()->json([
                'success' => true,
                'message' => 'Data surat berhasil diambil',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $surat = SuratPersetujuan::find($id);
            
            if (!$surat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Surat tidak ditemukan'
                ], 404);
            }

            $request->validate([
                'status' => 'required|in:pending,disetujui,ditolak',
                'catatan' => 'nullable|string'
            ]);

            $updateData = [
                'status' => $request->status,
                'catatan' => $request->catatan
            ];

            // Set tanggal persetujuan jika status disetujui
            if ($request->status === 'disetujui') {
                $updateData['tanggal_persetujuan'] = now();
            }

            $surat->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Status surat berhasil diperbarui',
                'data' => [
                    'id' => $surat->id,
                    'nomor_surat' => $surat->nomor_surat,
                    'status' => $surat->status,
                    'catatan' => $surat->catatan,
                    'tanggal_persetujuan' => $surat->tanggal_persetujuan ? $surat->tanggal_persetujuan->format('Y-m-d H:i:s') : null
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $surat = SuratPersetujuan::find($id);
            
            if (!$surat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Surat tidak ditemukan'
                ], 404);
            }

            // Delete file if exists
            if ($surat->file_pendukung) {
                Storage::disk('public')->delete($surat->file_pendukung);
            }

            $surat->delete();

            return response()->json([
                'success' => true,
                'message' => 'Surat berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dashboard statistics
     */
    public function dashboard(): JsonResponse
    {
        try {
            $totalSurat = SuratPersetujuan::count();
            $suratPending = SuratPersetujuan::where('status', 'pending')->count();
            $suratDisetujui = SuratPersetujuan::where('status', 'disetujui')->count();
            $suratDitolak = SuratPersetujuan::where('status', 'ditolak')->count();
            $suratBulanIni = SuratPersetujuan::whereMonth('created_at', now()->month)->count();

            // Statistik per jenis surat
            $statistikJenis = JenisSurat::withCount(['suratPersetujuan as total_surat'])
                ->orderBy('total_surat', 'desc')
                ->get();

            // Chart data (6 bulan terakhir)
            $chartData = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $count = SuratPersetujuan::whereYear('created_at', $date->year)
                                        ->whereMonth('created_at', $date->month)
                                        ->count();
                
                $chartData[] = [
                    'bulan' => $date->format('M Y'),
                    'jumlah' => $count
                ];
            }

            $data = [
                'total_surat' => $totalSurat,
                'surat_pending' => $suratPending,
                'surat_disetujui' => $suratDisetujui,
                'surat_ditolak' => $suratDitolak,
                'surat_bulan_ini' => $suratBulanIni,
                'statistik_jenis' => $statistikJenis,
                'chart_data' => $chartData
            ];

            return response()->json([
                'success' => true,
                'message' => 'Data dashboard berhasil diambil',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data dashboard',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
