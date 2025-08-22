<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuratPersetujuan;
use App\Models\JenisSurat;

class AddMoreSuratSeeder extends Seeder
{
    public function run()
    {
        $jenisSurat = JenisSurat::all();
        
        foreach ($jenisSurat as $jenis) {
            for ($i = 0; $i < rand(2, 5); $i++) {
                SuratPersetujuan::create([
                    'nomor_surat' => 'SKPD/' . time() . rand(1000, 9999) . '/' . date('m/Y') . '/KOMINFO-BTG',
                    'jenis_surat_id' => $jenis->id,
                    'nama_pemohon' => 'Pemohon ' . $jenis->nama_surat . ' ' . rand(1, 100),
                    'nik' => '123456789012345' . rand(1, 9),
                    'alamat' => 'Alamat ' . $jenis->nama_surat,
                    'no_telp' => '08123456789' . rand(1, 9),
                    'email' => 'test' . rand(1, 100) . '@example.com',
                    'keperluan' => 'Keperluan untuk ' . $jenis->nama_surat,
                    'tanggal_pengajuan' => now(),
                    'status' => ['pending', 'disetujui', 'ditolak'][rand(0, 2)],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        
        $this->command->info('Surat dengan jenis berbeda berhasil dibuat!');
    }
}
