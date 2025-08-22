<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratPersetujuan;
use App\Models\JenisSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use ZipArchive; // Added for ZipArchive extension check

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik dasar
        $totalSurat = SuratPersetujuan::count();
        $suratPending = SuratPersetujuan::where('status', 'pending')->count();
        $suratDisetujui = SuratPersetujuan::where('status', 'disetujui')->count();
        $suratDitolak = SuratPersetujuan::where('status', 'ditolak')->count();
        
        // Surat bulan ini
        $suratBulanIni = SuratPersetujuan::whereMonth('created_at', Carbon::now()->month)
                                         ->whereYear('created_at', Carbon::now()->year)
                                         ->count();
        
        // Surat terbaru yang perlu diproses (pending)
        $suratTerbaru = SuratPersetujuan::with('jenisSurat')
                                        ->where('status', 'pending')
                                        ->orderBy('created_at', 'desc')
                                        ->limit(5)
                                        ->get();
        
        // Statistik per jenis surat
        $statistikJenis = JenisSurat::select('jenis_surat.nama_surat', DB::raw('COUNT(surat_persetujuan.id) as total_surat'))
                                    ->leftJoin('surat_persetujuan', 'jenis_surat.id', '=', 'surat_persetujuan.jenis_surat_id')
                                    ->groupBy('jenis_surat.id', 'jenis_surat.nama_surat')
                                    ->orderBy('total_surat', 'desc')
                                    ->get();
        
        // Data chart untuk 6 bulan terakhir
        $chartData = $this->getChartData();
        
        return view('admin.dasboard', compact(
            'totalSurat',
            'suratPending', 
            'suratDisetujui',
            'suratDitolak',
            'suratBulanIni',
            'suratTerbaru',
            'statistikJenis',
            'chartData'
        ));
    }
    
    private function getChartData()
    {
        $months = [];
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');
            
            $count = SuratPersetujuan::whereMonth('created_at', $date->month)
                                    ->whereYear('created_at', $date->year)
                                    ->count();
            $data[] = $count;
        }
        
        return [
            'labels' => $months,
            'data' => $data
        ];
    }
    
    public function getStats()
    {
        $totalSurat = SuratPersetujuan::count();
        $suratPending = SuratPersetujuan::where('status', 'pending')->count();
        $suratDisetujui = SuratPersetujuan::where('status', 'disetujui')->count();
        $suratDitolak = SuratPersetujuan::where('status', 'ditolak')->count();
        $suratBulanIni = SuratPersetujuan::whereMonth('created_at', Carbon::now()->month)
                                         ->whereYear('created_at', Carbon::now()->year)
                                         ->count();
        $chartData = $this->getChartData();
        
        return response()->json([
            'success' => true,
            'data' => [
                'totalSurat' => $totalSurat,
                'suratPending' => $suratPending,
                'suratDisetujui' => $suratDisetujui,
                'suratDitolak' => $suratDitolak,
                'suratBulanIni' => $suratBulanIni,
                'chartData' => $chartData
            ]
        ]);
    }

    public function exportDocx()
    {
        try {
            // Debug: Log PHP version and extensions
            \Log::info('PHP Version: ' . PHP_VERSION);
            \Log::info('Loaded extensions: ' . implode(', ', get_loaded_extensions()));
            
            // Check if ZipArchive extension is available
            if (!class_exists('ZipArchive')) {
                \Log::error('ZipArchive extension not found');
                return back()->with('error', 'Ekstensi PHP ZipArchive tidak tersedia. Silakan aktifkan ekstensi zip di php.ini Laragon Anda. Atau gunakan export HTML sebagai alternatif.');
            }

            // Debug: Check if we can create ZipArchive instance
            try {
                $zip = new ZipArchive();
                \Log::info('ZipArchive instance created successfully');
            } catch (\Throwable $e) {
                \Log::error('Failed to create ZipArchive instance: ' . $e->getMessage());
                return back()->with('error', 'Gagal membuat instance ZipArchive: ' . $e->getMessage());
            }

            // Test if PhpWord class exists
            if (!class_exists(\PhpOffice\PhpWord\PhpWord::class)) {
                \Log::error('PhpWord class not found. Checking autoload...');
                
                // Try to manually include autoload
                if (file_exists(base_path('vendor/autoload.php'))) {
                    require_once base_path('vendor/autoload.php');
                    \Log::info('Manually included autoload.php');
                    
                    // Check again
                    if (!class_exists(\PhpOffice\PhpWord\PhpWord::class)) {
                        \Log::error('PhpWord still not found after manual autoload');
                        return back()->with('error', 'PhpWord library tidak tersedia. Jalankan: composer require phpoffice/phpword');
                    }
                } else {
                    \Log::error('Autoload file not found at: ' . base_path('vendor/autoload.php'));
                    return back()->with('error', 'Composer autoload file tidak ditemukan. Jalankan: composer install');
                }
            }

            // Test if we can create PhpWord instance
            try {
                $phpWord = new \PhpOffice\PhpWord\PhpWord();
                \Log::info('PhpWord instance created successfully');
            } catch (\Throwable $e) {
                \Log::error('Failed to create PhpWord instance: ' . $e->getMessage());
                return back()->with('error', 'Gagal membuat instance PhpWord: ' . $e->getMessage());
            }

            $totalSurat = SuratPersetujuan::count();
            $suratPending = SuratPersetujuan::where('status', 'pending')->count();
            $suratDisetujui = SuratPersetujuan::where('status', 'disetujui')->count();
            $suratDitolak = SuratPersetujuan::where('status', 'ditolak')->count();
            $suratBulanIni = SuratPersetujuan::whereMonth('created_at', Carbon::now()->month)
                                             ->whereYear('created_at', Carbon::now()->year)
                                             ->count();
            $chartData = $this->getChartData();

            $section = $phpWord->addSection();

            $section->addTitle('Laporan Dashboard SKPD Kominfo Bukittinggi', 1);
            $section->addText('Tanggal: ' . now()->format('d M Y H:i'));
            $section->addTextBreak(1);

            $section->addText('Ringkasan Statistik', ['bold' => true, 'size' => 14]);
            $section->addListItem("Total Surat: {$totalSurat}");
            $section->addListItem("Surat Pending: {$suratPending}");
            $section->addListItem("Surat Disetujui: {$suratDisetujui}");
            $section->addListItem("Surat Ditolak: {$suratDitolak}");
            $section->addListItem("Surat Bulan Ini: {$suratBulanIni}");

            $section->addTextBreak(1);
            $section->addText('Statistik 6 Bulan Terakhir', ['bold' => true, 'size' => 14]);
            if (!empty($chartData['labels'])) {
                foreach ($chartData['labels'] as $idx => $label) {
                    $jumlah = $chartData['data'][$idx] ?? 0;
                    $section->addText("- {$label}: {$jumlah} surat");
                }
            }

            $filename = 'laporan-dashboard-skpd-' . now()->format('Ymd_His') . '.docx';
            $tempFile = tempnam(sys_get_temp_dir(), 'docx');

            $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save($tempFile);

            \Log::info('DOCX export successful: ' . $filename);
            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            \Log::error('DOCX export failed: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Gagal membuat DOCX: ' . $e->getMessage());
        }
    }

    // Alternative method that doesn't require ZipArchive
    public function exportHtml()
    {
        try {
            $totalSurat = SuratPersetujuan::count();
            $suratPending = SuratPersetujuan::where('status', 'pending')->count();
            $suratDisetujui = SuratPersetujuan::where('status', 'disetujui')->count();
            $suratDitolak = SuratPersetujuan::where('status', 'ditolak')->count();
            $suratBulanIni = SuratPersetujuan::whereMonth('created_at', Carbon::now()->month)
                                             ->whereYear('created_at', Carbon::now()->year)
                                             ->count();
            $chartData = $this->getChartData();

            $html = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Laporan Dashboard SKPD Kominfo Bukittinggi</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
                    .title { font-size: 24px; font-weight: bold; color: #333; margin: 0; }
                    .subtitle { font-size: 14px; color: #666; margin: 5px 0; }
                    .stats { margin: 20px 0; }
                    .stat-item { margin: 10px 0; padding: 10px; background: #f9f9f9; border-left: 4px solid #007bff; }
                    .stat-label { font-weight: bold; color: #333; }
                    .stat-value { color: #007bff; font-size: 18px; }
                    .chart-section { margin: 20px 0; }
                    .chart-item { margin: 5px 0; padding: 5px 0; border-bottom: 1px solid #eee; }
                    .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; border-top: 1px solid #ddd; padding-top: 15px; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1 class="title">LAPORAN DASHBOARD SKPD</h1>
                    <p class="subtitle">Kominfo Bukittinggi</p>
                    <p class="subtitle">Tanggal: ' . now()->format('d M Y H:i') . '</p>
                </div>
                
                <div class="stats">
                    <h2>Ringkasan Statistik</h2>
                    <div class="stat-item">
                        <span class="stat-label">Total Surat:</span>
                        <span class="stat-value">' . $totalSurat . '</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Surat Pending:</span>
                        <span class="stat-value">' . $suratPending . '</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Surat Disetujui:</span>
                        <span class="stat-value">' . $suratDisetujui . '</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Surat Ditolak:</span>
                        <span class="stat-value">' . $suratDitolak . '</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Surat Bulan Ini:</span>
                        <span class="stat-value">' . $suratBulanIni . '</span>
                    </div>
                </div>
                
                <div class="chart-section">
                    <h2>Statistik 6 Bulan Terakhir</h2>';

            if (!empty($chartData['labels'])) {
                foreach ($chartData['labels'] as $idx => $label) {
                    $jumlah = $chartData['data'][$idx] ?? 0;
                    $html .= '
                    <div class="chart-item">
                        <strong>' . $label . ':</strong> ' . $jumlah . ' surat
                    </div>';
                }
            }

            $html .= '
                </div>
                
                <div class="footer">
                    <p>Dicetak dari Dashboard Admin SKPD Kominfo Bukittinggi</p>
                    <p>Halaman 1 dari 1</p>
                </div>
            </body>
            </html>';

            $filename = 'laporan-dashboard-skpd-' . now()->format('Ymd_His') . '.html';
            
            return response($html)
                ->header('Content-Type', 'text/html')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (\Throwable $e) {
            \Log::error('HTML export failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat HTML: ' . $e->getMessage());
        }
    }

    // Test method untuk debugging
    public function testPhpWord()
    {
        try {
            // Test 1: Check if class exists
            $classExists = class_exists(\PhpOffice\PhpWord\PhpWord::class);
            
            // Test 2: Check if we can create instance
            $canCreate = false;
            $instance = null;
            try {
                $instance = new \PhpOffice\PhpWord\PhpWord();
                $canCreate = true;
            } catch (\Throwable $e) {
                $canCreate = false;
            }
            
            // Test 3: Check if IOFactory exists
            $ioFactoryExists = class_exists(\PhpOffice\PhpWord\IOFactory::class);
            
            // Test 4: Check autoload file
            $autoloadExists = file_exists(base_path('vendor/autoload.php'));
            
            // Test 5: Try manual autoload
            $manualAutoload = false;
            if ($autoloadExists) {
                require_once base_path('vendor/autoload.php');
                $manualAutoload = class_exists(\PhpOffice\PhpWord\PhpWord::class);
            }
            
            return response()->json([
                'success' => true,
                'tests' => [
                    'PhpWord_class_exists' => $classExists,
                    'can_create_instance' => $canCreate,
                    'IOFactory_exists' => $ioFactoryExists,
                    'autoload_file_exists' => $autoloadExists,
                    'manual_autoload_works' => $manualAutoload,
                    'php_version' => PHP_VERSION,
                    'extensions' => get_loaded_extensions()
                ]
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}