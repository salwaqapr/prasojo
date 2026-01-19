<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kas;
use App\Models\Inventaris;
use App\Models\Sosial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ? (int)$request->bulan : null;
        $tahun = $request->tahun ? (int)$request->tahun : null;

        // MODE
        if ($bulan && $tahun) $mode = 'harian';
        elseif ($tahun && !$bulan) $mode = 'bulanan';
        elseif (!$tahun && $bulan) $mode = 'tahunan';
        else $mode = 'bulan_tahun';

        $buildChart = function ($model) use ($bulan, $tahun, $mode) {

            $tahunMarker = null;

            if ($mode === 'harian') {
                $labels = range(1, cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun));

                $data = $model
                    ->selectRaw('DAY(tanggal) as x, SUM(pemasukan) pemasukan')
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
            } elseif ($mode === 'bulanan') {
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
            } elseif ($mode === 'tahunan') {
                $years = $model
                    ->selectRaw('YEAR(tanggal) as y')
                    ->groupBy('y')
                    ->orderBy('y')
                    ->pluck('y');

                $labels = $years->toArray();

                $queryMasuk = $model->selectRaw('YEAR(tanggal) as x, SUM(pemasukan) pemasukan');
                $queryKeluar = $model->selectRaw('YEAR(tanggal) as x, SUM(pengeluaran) pengeluaran');

                if ($bulan) {
                    $queryMasuk->whereMonth('tanggal', $bulan);
                    $queryKeluar->whereMonth('tanggal', $bulan);
                }

                $data = $queryMasuk->groupBy('x')->pluck('pemasukan', 'x')->toArray();
                $keluar = $queryKeluar->groupBy('x')->pluck('pengeluaran', 'x')->toArray();
            } else {
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
                    ->keyBy(fn ($r) => $r->y . '-' . $r->m);

                $keluar = $model
                    ->selectRaw('YEAR(tanggal) as y, MONTH(tanggal) as m, SUM(pengeluaran) pengeluaran')
                    ->groupBy('y', 'm')
                    ->get()
                    ->keyBy(fn ($r) => $r->y . '-' . $r->m);
            }

            $pemasukan = [];
            $pengeluaran = [];
            $saldo = [];

            foreach ($labels as $i => $l) {
                if ($mode === 'bulan_tahun') {
                    $key = $tahunMarker[$i] . '-' . $l;
                    $p = (float)($data[$key]->pemasukan ?? 0);
                    $k = (float)($keluar[$key]->pengeluaran ?? 0);
                } else {
                    $p = (float)($data[$l] ?? 0);
                    $k = (float)($keluar[$l] ?? 0);
                }

                $pemasukan[] = $p;
                $pengeluaran[] = $k;
                $saldo[] = $p - $k;
            }

            // siapkan data recharts
            $series = [];
            foreach ($labels as $i => $l) {
                $series[] = [
                    'x' => $l,
                    'tahun' => $tahunMarker ? $tahunMarker[$i] : null,
                    'pemasukan' => $pemasukan[$i],
                    'pengeluaran' => $pengeluaran[$i],
                    'saldo' => $saldo[$i],
                ];
            }

            return [
                'mode' => $mode,
                'labels' => $labels,
                'tahunMarker' => $tahunMarker,
                'series' => $series,
                'totals' => [
                    'pemasukan' => array_sum($pemasukan),
                    'pengeluaran' => array_sum($pengeluaran),
                    'saldo' => array_sum($saldo),
                ],
            ];
        };

        // available bulan/tahun (sama seperti Blade kamu)
        $dates = DB::query()
            ->fromSub(function ($q) {
                $q->selectRaw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan')
                    ->from('kas')
                    ->whereNotNull('tanggal')
                    ->unionAll(
                        DB::table('sosial')->selectRaw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan')->whereNotNull('tanggal')
                    )
                    ->unionAll(
                        DB::table('inventaris')->selectRaw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan')->whereNotNull('tanggal')
                    );
            }, 'x')
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        $bulanFilter = [];
        $tahunFilter = [];

        foreach ($dates as $d) {
            $bulanFilter[(int)$d->tahun][] = (int)$d->bulan;
            $tahunFilter[(int)$d->bulan][] = (int)$d->tahun;
        }

        $availableTahun = collect($dates)->pluck('tahun')->unique()->values()->map(fn($v)=>(int)$v);
        if ($bulan) $availableTahun = collect($tahunFilter[$bulan] ?? []);

        $availableBulan = collect($dates)->pluck('bulan')->unique()->values()->map(fn($v)=>(int)$v);
        if ($tahun) $availableBulan = collect($bulanFilter[$tahun] ?? []);

        return response()->json([
            'filters' => [
                'bulan' => $bulan,
                'tahun' => $tahun,
                'bulanList' => range(1, 12),
                'tahunList' => range((int)date('Y'), (int)date('Y') - 10),
                'availableBulan' => $availableBulan->values(),
                'availableTahun' => $availableTahun->values(),
            ],
            'sections' => [
                'kas' => $buildChart(new Kas),
                'inventaris' => $buildChart(new Inventaris),
                'sosial' => $buildChart(new Sosial),
            ],
        ]);
    }
}
