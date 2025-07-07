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
        Schema::create('survey_answers', function (Blueprint $table) {
            $table->id();
            $table->string('nomorHp');
            $table->string('frekuensi');
            $table->string('pengeluaran');
            $table->string('alasan');
            $table->string('promo');
            $table->string('kesulitan');
            $table->string('jaringan');
            $table->string('info_promo');
            $table->string('pindah');
            $table->string('pindah_lainnya')->nullable(); // jika ada
            $table->string('kembali');
            $table->timestamps();
        });

    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_answers');
    }
};
