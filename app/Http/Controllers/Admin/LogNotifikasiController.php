<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogNotifikasiController extends Controller
{
    public function index()
    {
        return view('admin.log-notifikasi');
    }

    public function getLogs()
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            
            if (!File::exists($logFile)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File log tidak ditemukan'
                ]);
            }

            $logContent = File::get($logFile);
            $lines = explode("\n", $logContent);
            
            $notifikasiLogs = [];
            
            foreach ($lines as $line) {
                if (strpos($line, 'Notifikasi untuk') !== false) {
                    // Parse log line
                    $parsed = $this->parseLogLine($line);
                    if ($parsed) {
                        $notifikasiLogs[] = $parsed;
                    }
                }
            }
            
            // Sort by timestamp (newest first)
            usort($notifikasiLogs, function($a, $b) {
                return strtotime($b['timestamp']) - strtotime($a['timestamp']);
            });
            
            return response()->json([
                'success' => true,
                'logs' => $notifikasiLogs
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function clearLogs()
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            
            if (File::exists($logFile)) {
                // Backup log file
                $backupFile = storage_path('logs/laravel.backup.' . date('Y-m-d-H-i-s') . '.log');
                File::copy($logFile, $backupFile);
                
                // Clear log file
                File::put($logFile, '');
                
                return response()->json([
                    'success' => true,
                    'message' => 'Log berhasil dihapus dan di-backup'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'File log tidak ditemukan'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    private function parseLogLine($line)
    {
        // Format: [2025-08-12 04:32:01] local.INFO: Notifikasi untuk [nama]: [pesan]
        $pattern = '/\[([^\]]+)\]\s+(\w+)\.(\w+):\s+Notifikasi untuk\s+([^:]+):\s+(.+)/';
        
        if (preg_match($pattern, $line, $matches)) {
            return [
                'timestamp' => $matches[1],
                'environment' => $matches[2],
                'level' => $matches[3],
                'nama_pemohon' => trim($matches[4]),
                'message' => trim($matches[5])
            ];
        }
        
        return null;
    }

    public function downloadLog()
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            
            if (!File::exists($logFile)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File log tidak ditemukan'
                ]);
            }

            $filename = 'laravel-log-' . date('Y-m-d-H-i-s') . '.log';
            
            return response()->download($logFile, $filename);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}
