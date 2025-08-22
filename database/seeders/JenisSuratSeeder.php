<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisSurat;

class JenisSuratSeeder extends Seeder
{
    public function run()
    {
        $jenisSurat = [
            [
                'nama_surat' => 'Surat Persetujuan Izin Usaha Warnet',
                'aktif' => true
            ],
            [
                'nama_surat' => 'Surat Persetujuan Izin Usaha Rental Komputer',
                'aktif' => true
            ],
            [
                'nama_surat' => 'Surat Persetujuan Izin Usaha Jasa Fotocopy',
                'aktif' => true
            ],
            [
                'nama_surat' => 'Surat Persetujuan Izin Usaha Game Online',
                'aktif' => true
            ],
            [
                'nama_surat' => 'Surat Persetujuan Izin Usaha Jasa Internet',
                'aktif' => true
            ]
        ];

        foreach ($jenisSurat as $jenis) {
            JenisSurat::create($jenis);
        }

        $this->command->info('Jenis surat berhasil ditambahkan!');
    }
}