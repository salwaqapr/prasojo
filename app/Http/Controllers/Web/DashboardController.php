<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Kas;
use App\Models\Inventaris;
use App\Models\Sosial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        /* ======================
           MODE GRAFIK
        ====================== */
        if ($bulan && $tahun) {
            $mode = 'harian';
        } elseif ($tahun && !$bulan) {
            $mode = 'bulanan';
        } elseif (!$tahun && $bulan) {
            $mode = 'tahunan';
        } else {
            // - semua bulan & semua tahun
            $mode = 'bulan_tahun';
        }

        /* ======================
           HELPER QUERY
        ====================== */
        $buildChart = function ($model) use ($bulan, $tahun, $mode) {

            $tahunMarker = null;

            if ($mode === 'harian') {
                $labels = range(1, cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun));

                $data = $model
                    ->selectRaw('DAY(tanggal) as x, SUM(pemasukan) pemasukan, SUM(pengeluaran) pengeluaran')
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->groupBy('x')
                    ->pluck('pemasukan', 'x')
                    ->toArray();

                $keluar = $model
                    ->selectRaw('DAY(tanggal) as x, SUM(pengeluaran) pengeluaran')
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->groupBy('x')
                    ->pluck('pengeluaran', 'x')
                    ->toArray();
            }

            elseif ($mode === 'bulanan') {
                $labels = range(1, 12);

                $data = $model
                    ->selectRaw('MONTH(tanggal) as x, SUM(pemasukan) pemasukan')
                    ->whereYear('tanggal', $tahun)
                    ->groupBy('x')
                    ->pluck('pemasukan', 'x')
                    ->toArray();

                $keluar = $model
                    ->selectRaw('MONTH(tanggal) as x, SUM(pengeluaran) pengeluaran')
                    ->whereYear('tanggal', $tahun)
                    ->groupBy('x')
                    ->pluck('pengeluaran', 'x')
                    ->toArray();
            }

             elseif ($mode === 'tahunan') {
                $years = $model
                    ->selectRaw('YEAR(tanggal) as y')
                    ->groupBy('y')
                    ->orderBy('y')
                    ->pluck('y');

                $labels = $years->toArray();

                $queryMasuk = $model
                    ->selectRaw('YEAR(tanggal) as x, SUM(pemasukan) pemasukan');

                $queryKeluar = $model
                    ->selectRaw('YEAR(tanggal) as x, SUM(pengeluaran) pengeluaran');

                if ($bulan) {
                    $queryMasuk->whereMonth('tanggal', $bulan);
                    $queryKeluar->whereMonth('tanggal', $bulan);
                }

                $data = $queryMasuk
                    ->groupBy('x')
                    ->pluck('pemasukan', 'x')
                    ->toArray();

                $keluar = $queryKeluar
                    ->groupBy('x')
                    ->pluck('pengeluaran', 'x')
                    ->toArray();
            }

            else {
                // ambil tahun unik
                $years = $model
                    ->selectRaw('YEAR(tanggal) as y')
                    ->groupBy('y')
                    ->orderBy('y')
                    ->pluck('y')
                    ->toArray();

                $labels = [];
                $tahunMarker = [];

                foreach ($years as $y) {
                    foreach (range(1, 12) as $m) {
                        $labels[] = $m;
                        $tahunMarker[] = $y;
                    }
                }

                $data = $model
                    ->selectRaw('YEAR(tanggal) as y, MONTH(tanggal) as m, SUM(pemasukan) pemasukan')
                    ->groupBy('y', 'm')
                    ->get()
                    ->keyBy(fn ($r) => $r->y.'-'.$r->m);

                $keluar = $model
                    ->selectRaw('YEAR(tanggal) as y, MONTH(tanggal) as m, SUM(pengeluaran) pengeluaran')
                    ->groupBy('y', 'm')
                    ->get()
                    ->keyBy(fn ($r) => $r->y.'-'.$r->m);
            }

            $pemasukan = [];
            $pengeluaran = [];
            $saldo = [];

            foreach ($labels as $i => $l) {

                if ($mode === 'bulan_tahun') {
                    $key = $tahunMarker[$i].'-'.$l;
                    $p = $data[$key]->pemasukan ?? 0;
                    $k = $keluar[$key]->pengeluaran ?? 0;
                } else {
                    $p = $data[$l] ?? 0;
                    $k = $keluar[$l] ?? 0;
                }

                $pemasukan[] = $p;
                $pengeluaran[] = $k;
                $saldo[] = $p - $k;
            }

            return compact('labels', 'pemasukan', 'pengeluaran', 'saldo', 'tahunMarker');
        };

        $dates = DB::query()
            ->fromSub(function ($q) {
                $q->selectRaw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan')
                    ->from('kas')
                    ->whereNotNull('tanggal')
                ->unionAll(
                    DB::table('sosial')
                        ->selectRaw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan')
                        ->whereNotNull('tanggal')
                )
                ->unionAll(
                    DB::table('inventaris')
                        ->selectRaw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan')
                        ->whereNotNull('tanggal')
                );
            }, 'x')
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();


        $bulanFilter = [];
        $tahunFilter = [];

        foreach ($dates as $d) {
            $bulanFilter[$d->tahun][] = $d->bulan;
            $tahunFilter[$d->bulan][] = $d->tahun;
        }
        
        // default: semua
        $availableTahun = collect($dates)->pluck('tahun')->unique()->values();

        if ($request->filled('bulan')) {
            $availableTahun = collect($tahunFilter[$request->bulan] ?? []);
        }

        $availableBulan = collect($dates)->pluck('bulan')->unique()->values();

        if ($request->filled('tahun')) {
            $availableBulan = collect($bulanFilter[$request->tahun] ?? []);
        }

        return view('pages.dashboard', [
            'kasChart'        => $buildChart(new Kas),
            'inventarisChart' => $buildChart(new Inventaris),
            'sosialChart'     => $buildChart(new Sosial),
            'bulan' => $bulan,
            'tahun' => $tahun,
            'bulanList' => range(1, 12),
            'tahunList' => range(date('Y'), date('Y') - 10),
            'availableBulan' => $availableBulan,
            'availableTahun' => $availableTahun
        ]);
    }
}
