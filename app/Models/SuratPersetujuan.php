<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SuratPersetujuan extends Model
{
    use HasFactory;

    protected $table = 'surat_persetujuan';
    
    protected $fillable = [
        'nomor_surat',
        'jenis_surat_id',
        'nama_pemohon',
        'nik',
        'alamat',
        'no_telp',
        'email',
        'keperluan',
        'tanggal_pengajuan',
        'tanggal_persetujuan',
        'status',
        'catatan',
        'file_pendukung',
        'user_id', // kalau ingin simpan pemohon (opsional)
    ];

    protected $casts = [
        'tanggal_pengajuan'   => 'date',
        'tanggal_persetujuan' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */
    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | NOMOR SURAT GENERATOR
    |--------------------------------------------------------------------------
    */
    public static function createWithGeneratedNomor(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $tahun = date('Y');
            $bulan = date('m');

            // Pastikan baris counter (tahun, bulan) ada tanpa mereset last_number
            DB::table('surat_counters')->insertOrIgnore([
                'tahun' => $tahun,
                'bulan' => $bulan,
                'last_number' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Lock row counter agar aman saat banyak request bersamaan
            $counter = DB::table('surat_counters')
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->lockForUpdate()
                ->first();

            // Ambil nomor terbesar yang sudah ada di data surat bulan ini (robust via SQL)
            $maxFromData = (int) DB::table('surat_persetujuan')
                ->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $bulan)
                ->selectRaw("MAX(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(nomor_surat, '/', 2), '/', -1) AS UNSIGNED)) as maxnum")
                ->value('maxnum');

            // Tentukan nomor baru berbasis counter tersimpan dan data eksisting
            $base       = max(((int) ($counter->last_number ?? 0)), $maxFromData);
            $nextNumber = $base + 1;

            // Update counter ke nilai terbaru
            DB::table('surat_counters')
                ->where('id', $counter->id)
                ->update(['last_number' => $nextNumber, 'updated_at' => now()]);

            // Set atribut default
            $attributes['nomor_surat'] = sprintf('SKDP/%03d/%s/%s/KOMINFO-BTG', $nextNumber, $bulan, $tahun);
            $attributes['tanggal_pengajuan'] = $attributes['tanggal_pengajuan'] ?? now();
            $attributes['status'] = $attributes['status'] ?? 'pending';

            return self::create($attributes);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR
    |--------------------------------------------------------------------------
    */
    public function getNomorSuratFormatAttribute()
    {
        return $this->nomor_surat ?: '-';
    }

    /*
    |--------------------------------------------------------------------------
    | EVENT BOOT (opsional)
    |--------------------------------------------------------------------------
    | Kalau mau supaya otomatis generate nomor_surat setiap kali create() biasa,
    | aktifkan event ini. Jadi ga perlu panggil createWithGeneratedNomor() manual.
    */
    protected static function booted()
    {
        static::creating(function ($surat) {
            if (empty($surat->nomor_surat)) {
                $surat = self::createWithGeneratedNomor($surat->getAttributes());
                return false; // stop default create()
            }
        });
    }
}
