
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('surat_counters', function (Blueprint $table) {
            // ubah kolom last_number jadi BIGINT UNSIGNED
            $table->unsignedBigInteger('last_number')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_counters', function (Blueprint $table) {
            // rollback ke INT (opsional, sesuaikan dengan tipe lama)
            $table->integer('last_number')->change();
        });
    }
};
