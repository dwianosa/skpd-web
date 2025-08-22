<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuratPersetujuan;
use App\Models\JenisSurat;
use App\Models\User;
use Carbon\Carbon;

class DashboardTestSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada jenis surat
        $jenisSurat = JenisSurat::first();
        if (!$jenisSurat) {
            $jenisSurat = JenisSurat::create([
                'nama_surat' => 'Surat Keterangan SKPD',
                'aktif' => true
            ]);
        }

        // Buat beberapa surat test dengan tanggal berbeda untuk chart
        $suratData = [
            // 6 bulan yang lalu
            ['tanggal' => Carbon::now()->subMonths(5), 'status' => 'disetujui'],
            ['tanggal' => Carbon::now()->subMonths(5), 'status' => 'disetujui'],
            ['tanggal' => Carbon::now()->subMonths(5), 'status' => 'ditolak'],
            
            // 5 bulan yang lalu
            ['tanggal' => Carbon::now()->subMonths(4), 'status' => 'pending'],
            ['tanggal' => Carbon::now()->subMonths(4), 'status' => 'disetujui'],
            ['tanggal' => Carbon::now()->subMonths(4), 'status' => 'disetujui'],
            ['tanggal' => Carbon::now()->subMonths(4), 'status' => 'disetujui'],
            
            // 4 bulan yang lalu
            ['tanggal' => Carbon::now()->subMonths(3), 'status' => 'pending'],
            ['tanggal' => Carbon::now()->subMonths(3), 'status' => 'pending'],
            ['tanggal' => Carbon::now()->subMonths(3), 'status' => 'disetujui'],
            
            // 3 bulan yang lalu
            ['tanggal' => Carbon::now()->subMonths(2), 'status' => 'disetujui'],
            ['tanggal' => Carbon::now()->subMonths(2), 'status' => 'disetujui'],
            ['tanggal' => Carbon::now()->subMonths(2), 'status' => 'disetujui'],
            ['tanggal' => Carbon::now()->subMonths(2), 'status' => 'ditolak'],
            
            // 2 bulan yang lalu
            ['tanggal' => Carbon::now()->subMonths(1), 'status' => 'pending'],
            ['tanggal' => Carbon::now()->subMonths(1), 'status' => 'pending'],
            ['tanggal' => Carbon::now()->subMonths(1), 'status' => 'disetujui'],
            ['tanggal' => Carbon::now()->subMonths(1), 'status' => 'disetujui'],
            ['tanggal' => Carbon::now()->subMonths(1), 'status' => 'disetujui'],
            
            // Bulan ini
            ['tanggal' => Carbon::now(), 'status' => 'pending'],
            ['tanggal' => Carbon::now(), 'status' => 'pending'],
            ['tanggal' => Carbon::now(), 'status' => 'pending'],
            ['tanggal' => Carbon::now(), 'status' => 'disetujui'],
            ['tanggal' => Carbon::now(), 'status' => 'disetujui'],
            ['tanggal' => Carbon::now(), 'status' => 'ditolak'],
        ];

        foreach ($suratData as $data) {
            SuratPersetujuan::create([
                'nomor_surat' => 'SKPD/' . time() . rand(100, 999) . '/' . $data['tanggal']->format('m/Y') . '/KOMINFO-BTG',
                'jenis_surat_id' => $jenisSurat->id,
                'nama_pemohon' => 'Pemohon Test ' . rand(1, 100),
                'nik' => '123456789012345' . rand(1, 9),
                'alamat' => 'Alamat Test ' . rand(1, 100),
                'no_telp' => '08123456789' . rand(1, 9),
                'email' => 'test' . rand(1, 100) . '@example.com',
                'keperluan' => 'Keperluan test untuk dashboard',
                'tanggal_pengajuan' => $data['tanggal'],
                'status' => $data['status'],
                'created_at' => $data['tanggal'],
                'updated_at' => $data['tanggal'],
            ]);
        }

        $this->command->info('Dashboard test data berhasil dibuat!');
        $this->command->info('Total surat: ' . SuratPersetujuan::count());
        $this->command->info('Surat pending: ' . SuratPersetujuan::where('status', 'pending')->count());
        $this->command->info('Surat disetujui: ' . SuratPersetujuan::where('status', 'disetujui')->count());
        $this->command->info('Surat ditolak: ' . SuratPersetujuan::where('status', 'ditolak')->count());
    }
}
