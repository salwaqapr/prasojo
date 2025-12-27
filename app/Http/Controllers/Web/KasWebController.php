<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Kas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KasWebController extends Controller
{
    public function index()
    {
        $kas = Kas::orderBy('id', 'asc')->get();

        $totalPemasukan = $kas->sum('pemasukan');
        $totalPengeluaran = $kas->sum('pengeluaran');
        $saldo = $totalPemasukan - $totalPengeluaran;

        return view('pages.kas', [
            'pageTitle' => 'Kas',
            'kas' => $kas,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldo' => $saldo
        ]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'subjek' => 'required',
            'pemasukan' => 'nullable|numeric',
            'pengeluaran' => 'nullable|numeric'
        ]);

        $saldoTerakhir = Kas::orderBy('id','desc')->value('saldo') ?? 0;
        $saldoBaru = $saldoTerakhir + ($data['pemasukan'] ?? 0) - ($data['pengeluaran'] ?? 0);

        $kas = Kas::create([
            'tanggal' => $data['tanggal'],
            'subjek' => $data['subjek'],
            'pemasukan' => $data['pemasukan'] ?? 0,
            'pengeluaran' => $data['pengeluaran'] ?? 0,
            'saldo' => $saldoBaru
        ]);

        return response()->json($kas);
    }

    public function update(Request $request, $id)
    {
        $kas = Kas::findOrFail($id);

        $data = $request->validate([
            'tanggal' => 'required|date',
            'subjek' => 'required',
            'pemasukan' => 'nullable|numeric',
            'pengeluaran' => 'nullable|numeric'
        ]);

        // Update baris ini
        $kas->update([
            'tanggal' => $data['tanggal'],
            'subjek' => $data['subjek'],
            'pemasukan' => $data['pemasukan'] ?? 0,
            'pengeluaran' => $data['pengeluaran'] ?? 0,
        ]);

        // Recalculate saldo semua baris urut ID ascending
        $runningSaldo = 0;
        $allKas = Kas::orderBy('id', 'asc')->get();
        foreach ($allKas as $item) {
            $runningSaldo += $item->pemasukan - $item->pengeluaran;
            $item->saldo = $runningSaldo;
            $item->save();
        }

        return response()->json($kas);
    }

    public function destroy($id)
    {
        Kas::destroy($id);

        // Recalculate saldo semua baris urut ID ascending
        $runningSaldo = 0;
        $allKas = Kas::orderBy('id', 'asc')->get();
        foreach ($allKas as $item) {
            $runningSaldo += $item->pemasukan - $item->pengeluaran;
            $item->saldo = $runningSaldo;
            $item->save();
        }

        return response()->json(['message'=>'Data berhasil dihapus']);
    }

    public function pdf()
    {
        $data = Kas::orderBy('id')->get();

        return Pdf::loadView('pdf.pdf', [
            'data'  => $data,
            'jenis' => 'kas'
        ])->download('laporan-kas.pdf');
    }
}
