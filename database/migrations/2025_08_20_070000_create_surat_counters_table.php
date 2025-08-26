<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('surat_counters', function (Blueprint $table) {
            $table->id();
            $table->string('tahun', 4);
            $table->string('bulan', 2);
            $table->unsignedInteger('last_number')->default(0);
            $table->timestamps();

            $table->unique(['tahun', 'bulan'], 'surat_counters_tahun_bulan_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('surat_counters');
    }
};







