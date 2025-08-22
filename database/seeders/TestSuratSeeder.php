<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SuratPersetujuan;
use App\Models\JenisSurat;

class TestSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get jenis surat
        $jenisSurat = JenisSurat::first();
        
        if (!$jenisSurat) {
            $this->command->error('Tidak ada jenis surat! Jalankan JenisSuratSeeder terlebih dahulu.');
            return;
        }

        // Create multiple test surat with different statuses
        $suratData = [
            [
                'nama_pemohon' => 'Ahmad Rizki',
                'status' => 'pending',
                'keperluan' => 'Untuk keperluan usaha warnet di Bukittinggi'
            ],
            [
                'nama_pemohon' => 'Siti Nurhaliza',
                'status' => 'disetujui',
                'keperluan' => 'Untuk keperluan usaha rental komputer'
            ],
            [
                'nama_pemohon' => 'Budi Santoso',
                'status' => 'ditolak',
                'keperluan' => 'Untuk keperluan usaha game online',
                'catatan' => 'Dokumen tidak lengkap, mohon lengkapi KTP dan NPWP'
            ]
        ];

        foreach ($suratData as $index => $data) {
            $timestamp = time() + $index;
            $surat = SuratPersetujuan::create([
                'nomor_surat' => 'SKDP/' . $timestamp . '/08/2025/KOMINFO-BTG',
                'jenis_surat_id' => $jenisSurat->id,
                'nama_pemohon' => $data['nama_pemohon'],
                'nik' => '123456789012345' . $index,
                'alamat' => 'Jl. Test No. ' . ($index + 1) . ', Bukittinggi',
                'no_telp' => '08123456789' . $index,
                'email' => 'test' . $timestamp . '@example.com',
                'keperluan' => $data['keperluan'],
                'tanggal_pengajuan' => now()->subDays($index),
                'status' => $data['status'],
                'catatan' => $data['catatan'] ?? null,
                'file_pendukung' => null
            ]);

            $this->command->info('Surat ' . ($index + 1) . ' dibuat:');
            $this->command->info('  Nomor: ' . $surat->nomor_surat);
            $this->command->info('  Pemohon: ' . $surat->nama_pemohon);
            $this->command->info('  Status: ' . $surat->status);
            $this->command->info('');
        }

        $this->command->info('=== SURAT UNTUK TESTING ===');
        $this->command->info('1. Pending: SKDP/' . (time()) . '/08/2025/KOMINFO-BTG');
        $this->command->info('2. Disetujui: SKDP/' . (time() + 1) . '/08/2025/KOMINFO-BTG');
        $this->command->info('3. Ditolak: SKDP/' . (time() + 2) . '/08/2025/KOMINFO-BTG');
        $this->command->info('');
        $this->command->info('Silakan test tracking dengan nomor-nomor di atas!');
    }
}
