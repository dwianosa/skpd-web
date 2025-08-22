<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratPersetujuan;
use App\Models\JenisSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SuratStatusMail;

class SuratController extends Controller
{
    public function index()
    {
        $surat = SuratPersetujuan::with('jenisSurat')
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);
        
        return view('admin.surat.index', compact('surat'));
    }
    
    public function show($id)
    {
        $surat = SuratPersetujuan::with('jenisSurat')->findOrFail($id);
        return view('admin.surat.show', compact('surat'));
    }
    
    public function edit($id)
    {
        $surat = SuratPersetujuan::with('jenisSurat')->findOrFail($id);
        return view('admin.surat.edit', compact('surat'));
    }
    
    public function update(Request $request, $id)
    {
        $surat = SuratPersetujuan::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak',
            'catatan' => 'nullable|string',
            'tanggal_persetujuan' => 'nullable|date'
        ]);

        // Update status dan catatan
        $surat->update([
            'status' => $validated['status'],
            'catatan' => $validated['catatan'],
            'tanggal_persetujuan' => $validated['status'] === 'disetujui' ? now() : null
        ]);

        // Kirim notifikasi berdasarkan status
        $this->sendNotification($surat);
        
        return redirect()->route('admin.surat.index')
            ->with('success', 'Status surat berhasil diperbarui!');
    }
    
    public function destroy($id)
    {
        $surat = SuratPersetujuan::findOrFail($id);
        $surat->delete();
        
        return redirect()->route('admin.surat.index')
            ->with('success', 'Surat berhasil dihapus!');
    }
    
    private function sendNotification($surat)
    {
        $status = $surat->status;
        $nomorSurat = $surat->nomor_surat;
        $namaPemohon = $surat->nama_pemohon;
        
        switch ($status) {
            case 'disetujui':
                $message = "Selamat! Surat Anda dengan nomor {$nomorSurat} telah DISETUJUI. Silakan ambil surat di kantor Kominfo Bukittinggi.";
                break;
            case 'ditolak':
                $message = "Mohon maaf, surat Anda dengan nomor {$nomorSurat} DITOLAK. Silakan perbaiki sesuai catatan yang diberikan.";
                break;
            case 'pending':
                $message = "Surat Anda dengan nomor {$nomorSurat} sedang dalam proses verifikasi. Kami akan menghubungi Anda segera.";
                break;
        }

        // Log notifikasi
        \Log::info("Notifikasi untuk {$namaPemohon}: {$message}");
        
        // Kirim email notifikasi jika ada email
        if ($surat->email) {
            try {
                Mail::to($surat->email)->send(new SuratStatusMail($surat));
                \Log::info("Email notifikasi berhasil dikirim ke: {$surat->email}");
            } catch (\Exception $e) {
                \Log::error("Gagal mengirim email notifikasi: " . $e->getMessage());
            }
        }
        
        // TODO: Implementasi notifikasi SMS/WhatsApp
        // Contoh: $this->sendWhatsAppNotification($surat);
    }

    public function dashboard()
    {
        $stats = [
            'total' => SuratPersetujuan::count(),
            'pending' => SuratPersetujuan::where('status', 'pending')->count(),
            'disetujui' => SuratPersetujuan::where('status', 'disetujui')->count(),
            'ditolak' => SuratPersetujuan::where('status', 'ditolak')->count(),
        ];

        $recentSurat = SuratPersetujuan::with('jenisSurat')
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get();

        return view('admin.dasboard', compact('stats', 'recentSurat'));
    }

	public function exportDocx($id)
	{
		try {
			$surat = SuratPersetujuan::with('jenisSurat')->findOrFail($id);

			if ($surat->status !== 'disetujui') {
				return back()->with('error', 'Surat belum disetujui, tidak dapat dicetak sebagai DOCX.');
			}

			if (!class_exists('ZipArchive')) {
				\Log::error('ZipArchive extension not found for surat export DOCX');
				return back()->with('error', 'Ekstensi PHP ZipArchive tidak tersedia. Aktifkan ekstensi zip di php.ini Laragon Anda.');
			}

			if (!class_exists(\PhpOffice\PhpWord\PhpWord::class)) {
				\Log::error('PhpWord class not found for surat export DOCX');
				return back()->with('error', 'PhpWord library tidak tersedia. Jalankan: composer require phpoffice/phpword');
			}

			try {
				$phpWord = new \PhpOffice\PhpWord\PhpWord();
			} catch (\Throwable $e) {
				\Log::error('Failed to create PhpWord instance for surat export: ' . $e->getMessage());
				return back()->with('error', 'Gagal membuat instance PhpWord: ' . $e->getMessage());
			}

			$section = $phpWord->addSection();

			// Styles
			$phpWord->addTitleStyle(1, ['bold' => true, 'size' => 16], ['alignment' => 'center']);
			$headerStyle = ['bold' => true, 'size' => 12];
			$labelStyle = ['bold' => true];

			$section->addText('PEMERINTAH KOTA BUKITTINGGI', ['bold' => true, 'size' => 14], ['alignment' => 'center']);
			$section->addText('DINAS KOMUNIKASI DAN INFORMATIKA', ['bold' => true, 'size' => 14], ['alignment' => 'center']);
			$section->addTextBreak(1);
			$section->addTitle('SURAT PERSETUJUAN', 1);
			$section->addTextBreak(1);

			$section->addText('Nomor: ' . ($surat->nomor_surat ?? '-'), $headerStyle, ['alignment' => 'center']);
			$section->addTextBreak(1);

			$section->addText('Data Pemohon', $headerStyle);
			$section->addText('Nama Pemohon: ' . $surat->nama_pemohon);
			$section->addText('NIK: ' . $surat->nik);
			$section->addText('Alamat: ' . $surat->alamat);
			$section->addText('No. Telepon: ' . $surat->no_telp);
			$section->addText('Email: ' . ($surat->email ?: '-'));
			$section->addTextBreak(1);

			$section->addText('Detail Surat', $headerStyle);
			$section->addText('Jenis Surat: ' . ($surat->jenisSurat->nama_surat ?? '-'));
			$section->addText('Tanggal Pengajuan: ' . ($surat->tanggal_pengajuan ? $surat->tanggal_pengajuan->format('d M Y') : '-'));
			$section->addText('Tanggal Persetujuan: ' . ($surat->tanggal_persetujuan ? $surat->tanggal_persetujuan->format('d M Y') : '-'));
			$section->addText('Keperluan:');
			$section->addText($surat->keperluan ?: '-', [], ['spaceAfter' => 240]);
			$section->addTextBreak(1);

			$section->addText('Catatan:', $labelStyle);
			$section->addText($surat->catatan ?: '-', [], ['spaceAfter' => 240]);
			$section->addTextBreak(2);

			$section->addText('Bukittinggi, ' . now()->format('d M Y'), [], ['alignment' => 'right']);
			$section->addText('Kepala Dinas Kominfo Bukittinggi', [], ['alignment' => 'right']);
			$section->addTextBreak(3);
			$section->addText('(________________________)', [], ['alignment' => 'right']);

			$filename = 'surat-persetujuan-' . ($surat->nomor_surat ? preg_replace('/[^A-Za-z0-9_-]+/', '-', $surat->nomor_surat) : $surat->id) . '.docx';
			$tempFile = tempnam(sys_get_temp_dir(), 'docx');

			$writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
			$writer->save($tempFile);

			\Log::info('DOCX surat export successful: ' . $filename);
			return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
		} catch (\Throwable $e) {
			\Log::error('DOCX surat export failed: ' . $e->getMessage());
			\Log::error('Stack trace: ' . $e->getTraceAsString());
			return back()->with('error', 'Gagal membuat DOCX: ' . $e->getMessage());
		}
	}
}