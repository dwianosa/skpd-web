<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jenis_surat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_surat');
            $table->boolean('aktif')->default(1); // ✅ kolom aktif
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jenis_surat');
    }
};
