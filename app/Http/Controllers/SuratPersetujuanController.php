<?php
namespace App\Http\Controllers;

use App\Models\SuratPersetujuan;
use App\Models\JenisSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\SuratSubmissionMail;

class SuratPersetujuanController extends Controller
{
    public function create()
    {
        $jenisSurat = JenisSurat::where('aktif', true)->get();
        return view('surat.create', compact('jenisSurat'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surat,id',
            'nama_pemohon' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:15',
            'email' => 'nullable|email',
            'keperluan' => 'required|string',
            'file_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        // Handle file upload
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $validated['file_pendukung'] = $filename;
        }

        // Create with safe nomor_surat generator
        $surat = SuratPersetujuan::createWithGeneratedNomor($validated);

        // Kirim email konfirmasi pengajuan ke pemohon
        if ($surat->email) {
            try {
                $this->sendSubmissionConfirmation($surat);
                \Log::info("Email konfirmasi pengajuan berhasil dikirim ke: {$surat->email}");
            } catch (\Exception $e) {
                \Log::error("Gagal mengirim email konfirmasi: " . $e->getMessage());
            }
        }

        // Log for debugging
        \Log::info('Surat created: ' . $surat->nomor_surat . ' with ID: ' . $surat->id);
        \Log::info('Redirecting to: ' . route('surat.success', $surat->id));

        return redirect()->route('surat.success', $surat->id)
            ->with('success', 'Surat berhasil diajukan! Nomor surat: ' . $surat->nomor_surat . '. Email konfirmasi telah dikirim.');
    }

    public function success($id)
    {
        try {
            // Log for debugging
            \Log::info('Success method called with ID: ' . $id);
            
            $surat = SuratPersetujuan::with('jenisSurat')->find($id);
            
            if (!$surat) {
                \Log::error('Surat not found with ID: ' . $id);
                abort(404, 'Surat tidak ditemukan');
            }
            
            \Log::info('Surat found: ' . $surat->nomor_surat);
            return view('surat.success', compact('surat'));
        } catch (\Exception $e) {
            \Log::error('Error in success method: ' . $e->getMessage());
            abort(500, 'Terjadi kesalahan saat memuat halaman sukses');
        }
    }

    /**
     * Kirim email konfirmasi pengajuan surat
     */
    private function sendSubmissionConfirmation($surat)
    {
        try {
            Mail::to($surat->email)->send(new SuratSubmissionMail($surat));
            return true;
        } catch (\Exception $e) {
            \Log::error("Error sending submission confirmation: " . $e->getMessage());
            throw $e;
        }
    }
}