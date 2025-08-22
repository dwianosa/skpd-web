<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    if (!Schema::hasColumn('jenis_surat', 'aktif')) {
        Schema::table('jenis_surat', function (Blueprint $table) {
            $table->boolean('aktif')->default(1);
        });
    }
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_surat', function (Blueprint $table) {
            //
        });
    }
};
