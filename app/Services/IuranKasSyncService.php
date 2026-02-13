<?php

namespace App\Services;

use App\Models\Kas;
use App\Models\IuranPayment;
use Carbon\Carbon;

class IuranKasSyncService
{
  public static function syncMonthlyKas(int $bulan, int $tahun): void
  {
    $total = IuranPayment::where('bulan', $bulan)
      ->where('tahun', $tahun)
      ->sum('nominal');

    $bulanNama = [
      1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
      5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
      9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    $subjek = "Iuran bulan {$bulanNama[$bulan]} {$tahun}";
    $tanggal = Carbon::create($tahun, $bulan, 1)->endOfMonth()->format('Y-m-d');

    // ambil semua baris kas yang punya subjek sama (antisipasi duplikat)
    $allKas = Kas::where('subjek', $subjek)->orderBy('id', 'asc')->get();

    if ($total > 0) {
      if ($allKas->count() > 0) {
        // pakai baris pertama sebagai "utama"
        $main = $allKas->first();
        $main->update([
          'tanggal' => $tanggal,
          'pemasukan' => $total,
          'pengeluaran' => 0,
        ]);

        // hapus sisanya kalau ada duplikat
        $dupes = $allKas->skip(1);
        if ($dupes->count() > 0) {
          Kas::whereIn('id', $dupes->pluck('id'))->delete();
        }
      } else {
        Kas::create([
          'tanggal' => $tanggal,
          'subjek' => $subjek,
          'pemasukan' => $total,
          'pengeluaran' => 0,
        ]);
      }
    } else {
      // total 0 => hapus semua baris kas iuran bulan tsb
      if ($allKas->count() > 0) {
        Kas::whereIn('id', $allKas->pluck('id'))->delete();
      }
    }
  }
}
