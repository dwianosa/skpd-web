<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SuratPersetujuanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SuratController;
use App\Http\Controllers\Admin\JenisSuratController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tracking', [HomeController::class, 'tracking'])->name('tracking');
Route::post('/track', [HomeController::class, 'trackByNomor'])->name('track.nomor');
Route::get('/cek-status/{id}', [HomeController::class, 'cekStatus'])->name('cek.status');

// Surat Routes
Route::get('/ajukan-surat', [SuratPersetujuanController::class, 'create'])->name('surat.create');
Route::post('/ajukan-surat', [SuratPersetujuanController::class, 'store'])->name('surat.store');
Route::get('/success/{id}', [SuratPersetujuanController::class, 'success'])->name('surat.success');

// Test routes untuk debugging
Route::get('/test-success/{id}', [HomeController::class, 'testSuccess'])->name('test.success');
Route::post('/test-tracking', [HomeController::class, 'testTracking'])->name('test.tracking');

// Test email notification
Route::get('/test-email', function() {
    try {
        // Test basic email
        Mail::raw('Test email dari SKPD Kominfo Bukittinggi', function($message) {
            $message->to('test@example.com')
                   ->subject('Test Email Notification');
        });
        
        return response()->json([
            'status' => 'success',
            'message' => 'Test email berhasil dikirim (disimpan di log)',
            'log_location' => 'storage/logs/laravel.log'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Gagal mengirim email: ' . $e->getMessage()
        ]);
    }
})->name('test.email');

// Test email dengan template
Route::get('/test-email-template', function() {
    try {
        // Ambil surat pertama untuk test
        $surat = \App\Models\SuratPersetujuan::with('jenisSurat')->first();
        
        if (!$surat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada data surat untuk testing'
            ]);
        }
        
        // Test email dengan template
        Mail::to('test@example.com')->send(new \App\Mail\SuratStatusMail($surat));
        
        return response()->json([
            'status' => 'success',
            'message' => 'Test email template berhasil dikirim',
            'surat_id' => $surat->id,
            'status' => $surat->status,
            'log_location' => 'storage/logs/laravel.log'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Gagal mengirim email template: ' . $e->getMessage()
        ]);
    }
})->name('test.email.template');

// Auth Routes
Auth::routes();

// Admin Routes (Protected)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export-docx', [DashboardController::class, 'exportDocx'])->name('dashboard.export-docx');
    Route::get('/dashboard/export-html', [DashboardController::class, 'exportHtml'])->name('dashboard.export-html');
    Route::get('/test-phpword', [DashboardController::class, 'testPhpWord'])->name('test-phpword');
    Route::resource('surat', SuratController::class);
    Route::get('surat/{id}/export-docx', [SuratController::class, 'exportDocx'])->name('surat.export-docx');
    Route::resource('jenis-surat', JenisSuratController::class);
    Route::get('/demo-notifikasi', function() {
        return view('admin.demo-notifikasi');
    })->name('demo-notifikasi');
    
    // Demo Email Notification
    Route::get('/demo-email', function() {
        $totalSurat = \App\Models\SuratPersetujuan::count();
        $pendingSurat = \App\Models\SuratPersetujuan::where('status', 'pending')->count();
        $processedSurat = \App\Models\SuratPersetujuan::whereIn('status', ['disetujui', 'ditolak'])->count();
        
        return view('admin.demo-email', compact('totalSurat', 'pendingSurat', 'processedSurat'));
    })->name('demo.email');
    
    // Log Notifikasi Routes
    Route::get('/log-notifikasi', [App\Http\Controllers\Admin\LogNotifikasiController::class, 'index'])->name('log-notifikasi');
    Route::get('/log-notifikasi/api', [App\Http\Controllers\Admin\LogNotifikasiController::class, 'getLogs'])->name('log-notifikasi.api');
    Route::post('/log-notifikasi/clear', [App\Http\Controllers\Admin\LogNotifikasiController::class, 'clearLogs'])->name('log-notifikasi.clear');
    Route::get('/log-notifikasi/download', [App\Http\Controllers\Admin\LogNotifikasiController::class, 'downloadLog'])->name('log-notifikasi.download');
});

// Test route for PhpWord
Route::get('/test-phpword-simple', function() {
    try {
        if (class_exists('PhpOffice\PhpWord\PhpWord')) {
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $section = $phpWord->addSection();
            $section->addText('Test Document from Laravel Route');
            
            $tempFile = tempnam(sys_get_temp_dir(), 'test_docx');
            $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save($tempFile);
            
            return response()->json([
                'success' => true,
                'message' => 'PhpWord working in Laravel',
                'temp_file' => $tempFile
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'PhpWord class not found'
            ]);
        }
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
});

// Public test route for PhpWord (no auth required)
Route::get('/test-phpword-public', function() {
    try {
        // Test 1: Check if class exists
        $classExists = class_exists('PhpOffice\PhpWord\PhpWord');
        
        // Test 2: Check if we can create instance
        $canCreate = false;
        try {
            $instance = new \PhpOffice\PhpWord\PhpWord();
            $canCreate = true;
        } catch (\Throwable $e) {
            $canCreate = false;
        }
        
        // Test 3: Check if IOFactory exists
        $ioFactoryExists = class_exists('PhpOffice\PhpWord\IOFactory');
        
        // Test 4: Check autoload file
        $autoloadExists = file_exists(base_path('vendor/autoload.php'));
        
        // Test 5: Try manual autoload
        $manualAutoload = false;
        if ($autoloadExists) {
            require_once base_path('vendor/autoload.php');
            $manualAutoload = class_exists('PhpOffice\PhpWord\PhpWord');
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
                'extensions' => get_loaded_extensions(),
                'zip_extension_loaded' => extension_loaded('zip'),
                'ziparchive_exists' => class_exists('ZipArchive'),
                'php_ini_path' => php_ini_loaded_file(),
                'php_ini_scanned' => php_ini_scanned_files()
            ]
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});