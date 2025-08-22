<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class JenisSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $jenisSurat = JenisSurat::where('aktif', true)->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Data jenis surat berhasil diambil',
                'data' => $jenisSurat
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data jenis surat',
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
            $jenisSurat = JenisSurat::find($id);
            
            if (!$jenisSurat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jenis surat tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Data jenis surat berhasil diambil',
                'data' => $jenisSurat
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data jenis surat',
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
                'nama_surat' => 'required|string|max:255',
            ]);

            $jenisSurat = JenisSurat::create([
                'nama_surat' => $request->nama_surat,
                'aktif' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jenis surat berhasil ditambahkan',
                'data' => $jenisSurat
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan jenis surat',
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
            $jenisSurat = JenisSurat::find($id);
            
            if (!$jenisSurat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jenis surat tidak ditemukan'
                ], 404);
            }

            $request->validate([
                'nama_surat' => 'required|string|max:255',
            ]);

            $jenisSurat->update([
                'nama_surat' => $request->nama_surat,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jenis surat berhasil diperbarui',
                'data' => $jenisSurat
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui jenis surat',
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
            $jenisSurat = JenisSurat::find($id);
            
            if (!$jenisSurat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jenis surat tidak ditemukan'
                ], 404);
            }

            $jenisSurat->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jenis surat berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus jenis surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
