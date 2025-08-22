<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SuratPersetujuan;
use Illuminate\Http\JsonResponse;

class TrackingController extends Controller
{
    /**
     * Track surat by nomor surat
     */
    public function track($nomor_surat): JsonResponse
    {
        try {
            $surat = SuratPersetujuan::with('jenisSurat')
                ->where('nomor_surat', $nomor_surat)
                ->first();

            if (!$surat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Surat tidak ditemukan',
                    'data' => null
                ], 404);
            }

            // Format status
            $statusText = '';
            $statusIcon = '';
            
            switch($surat->status) {
                case 'pending':
                    $statusText = 'Sedang Diproses';
                    $statusIcon = 'fas fa-hourglass-half';
                    break;
                case 'disetujui':
                    $statusText = 'Disetujui';
                    $statusIcon = 'fas fa-check-circle';
                    break;
                case 'ditolak':
                    $statusText = 'Ditolak';
                    $statusIcon = 'fas fa-times-circle';
                    break;
                default:
                    $statusText = 'Tidak Diketahui';
                    $statusIcon = 'fas fa-question-circle';
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
                'status_text' => $statusText,
                'status_icon' => $statusIcon,
                'tanggal_pengajuan' => $surat->tanggal_pengajuan->format('Y-m-d H:i:s'),
                'tanggal_persetujuan' => $surat->tanggal_persetujuan ? $surat->tanggal_persetujuan->format('Y-m-d H:i:s') : null,
                'catatan' => $surat->catatan,
                'created_at' => $surat->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $surat->updated_at->format('Y-m-d H:i:s')
            ];

            return response()->json([
                'success' => true,
                'message' => 'Data surat berhasil ditemukan',
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat melacak surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
