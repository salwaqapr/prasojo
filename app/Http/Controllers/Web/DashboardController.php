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
           FILTER BULAN & TAHUN
        ====================== */
        $filter = function ($query) use ($bulan, $tahun) {
            if ($tahun) {
                $query->whereYear('tanggal', $tahun);
            }
            if ($bulan) {
                $query->whereMonth('tanggal', $bulan);
            }
        };

        /* ======================
           DATA KAS
        ====================== */
        $kasPemasukan   = Kas::where($filter)->sum('pemasukan');
        $kasPengeluaran = Kas::where($filter)->sum('pengeluaran');
        $kasSaldo       = $kasPemasukan - $kasPengeluaran;

        /* ======================
           DATA INVENTARIS
        ====================== */
        $inventarisPemasukan   = Inventaris::where($filter)->sum('pemasukan');
        $inventarisPengeluaran = Inventaris::where($filter)->sum('pengeluaran');
        $inventarisSaldo       = $inventarisPemasukan - $inventarisPengeluaran;

        /* ======================
           DATA SOSIAL
        ====================== */
        $sosialPemasukan   = Sosial::where($filter)->sum('pemasukan');
        $sosialPengeluaran = Sosial::where($filter)->sum('pengeluaran');
        $sosialSaldo       = $sosialPemasukan - $sosialPengeluaran;

        /* ======================
           BULAN & TAHUN DARI DATA (GABUNGAN)
        ====================== */
        $bulanList = collect()
            ->merge(Kas::selectRaw('MONTH(tanggal) as bulan')->pluck('bulan'))
            ->merge(Inventaris::selectRaw('MONTH(tanggal) as bulan')->pluck('bulan'))
            ->merge(Sosial::selectRaw('MONTH(tanggal) as bulan')->pluck('bulan'))
            ->unique()
            ->sort()
            ->values();

        $tahunList = collect()
            ->merge(Kas::selectRaw('YEAR(tanggal) as tahun')->pluck('tahun'))
            ->merge(Inventaris::selectRaw('YEAR(tanggal) as tahun')->pluck('tahun'))
            ->merge(Sosial::selectRaw('YEAR(tanggal) as tahun')->pluck('tahun'))
            ->unique()
            ->sortDesc()
            ->values();

        return view('pages.dashboard', compact(
            'bulan',
            'tahun',
            'bulanList',
            'tahunList',
            'kasPemasukan',
            'kasPengeluaran',
            'kasSaldo',
            'inventarisPemasukan',
            'inventarisPengeluaran',
            'inventarisSaldo',
            'sosialPemasukan',
            'sosialPengeluaran',
            'sosialSaldo'
        ));
    }
}
