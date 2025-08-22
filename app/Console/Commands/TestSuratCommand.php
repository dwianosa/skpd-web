<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SuratPersetujuan;

class TestSuratCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:surat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test surat functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Surat Functionality...');
        
        // Check if surat exists
        $surat = SuratPersetujuan::first();
        
        if ($surat) {
            $this->info('Found surat: ' . $surat->nomor_surat);
            $this->info('ID: ' . $surat->id);
            $this->info('Status: ' . $surat->status);
            $this->info('URL: ' . route('surat.success', $surat->id));
            $this->info('Tracking URL: ' . route('cek.status', $surat->nomor_surat));
            
            // Test if view exists
            if (view()->exists('surat.success')) {
                $this->info('View surat.success exists');
            } else {
                $this->error('View surat.success does not exist');
            }
            
            // Test tracking
            $this->info('');
            $this->info('=== TESTING TRACKING ===');
            $this->info('You can test tracking with:');
            $this->info('1. Go to: http://localhost:8000/tracking');
            $this->info('2. Enter nomor surat: ' . $surat->nomor_surat);
            $this->info('3. Or test API directly: http://localhost:8000/cek-status/' . urlencode($surat->nomor_surat));
            
        } else {
            $this->error('No surat found in database');
            $this->info('Run: php artisan db:seed --class=TestSuratSeeder');
        }
        
        return 0;
    }
}
