<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('iuran_payments', function (Blueprint $table) {
      $table->id();

      $table->foreignId('member_id')
        ->constrained('iuran_members')
        ->cascadeOnDelete();

      $table->date('tanggal');
      $table->unsignedTinyInteger('bulan'); // 1-12
      $table->unsignedSmallInteger('tahun'); // 2026 dst
      $table->unsignedBigInteger('nominal'); // rupiah (tanpa Rp)

      $table->timestamps();

      // biar 1 anggota 1 bulan 1 tahun tidak dobel (kalau mau dipaksa unik)
      // kalau kamu mau boleh dobel, hapus unique ini.
      $table->unique(['member_id', 'bulan', 'tahun']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('iuran_payments');
  }
};
