<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Kas;
use App\Models\Inventaris;
use App\Models\Sosial;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // KAS
        $kasPemasukan = Kas::sum('pemasukan');
        $kasPengeluaran = Kas::sum('pengeluaran');
        $kasSaldo = $kasPemasukan - $kasPengeluaran;

        // INVENTARIS
        $inventarisPemasukan = Inventaris::sum('pemasukan');
        $inventarisPengeluaran = Inventaris::sum('pengeluaran');
        $inventarisSaldo = Inventaris::orderBy('id','desc')->value('saldo') ?? 0;

        // SOSIAL
        $sosialPemasukan = Sosial::sum('pemasukan');
        $sosialPengeluaran = Sosial::sum('pengeluaran');
        $sosialSaldo = Sosial::orderBy('id','desc')->value('saldo') ?? 0;

        return view('pages.dashboard', [
            'pageTitle' => 'Dashboard',

            // kas
            'kasPemasukan' => $kasPemasukan,
            'kasPengeluaran' => $kasPengeluaran,
            'kasSaldo' => $kasSaldo,

            // inventaris
            'inventarisPemasukan' => $inventarisPemasukan,
            'inventarisPengeluaran' => $inventarisPengeluaran,
            'inventarisSaldo' => $inventarisSaldo,

            // sosial
            'sosialPemasukan' => $sosialPemasukan,
            'sosialPengeluaran' => $sosialPengeluaran,
            'sosialSaldo' => $sosialSaldo,
        ]);
    }
}
