<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Sosial;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SosialWebController extends Controller
{
    public function index()
    {
        // Urut berdasarkan ID ascending supaya tampilannya konsisten
        $sosial = Sosial::orderBy('id', 'asc')->get();
        return view('pages.sosial', ['pageTitle'=>'Sosial','sosial'=>$sosial]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'subjek' => 'required',
            'pemasukan' => 'nullable|numeric',
            'pengeluaran' => 'nullable|numeric'
        ]);

        $saldoTerakhir = Sosial::orderBy('id','desc')->value('saldo') ?? 0;
        $saldoBaru = $saldoTerakhir + ($data['pemasukan'] ?? 0) - ($data['pengeluaran'] ?? 0);

        $sosial = Sosial::create([
            'tanggal' => $data['tanggal'],
            'subjek' => $data['subjek'],
            'pemasukan' => $data['pemasukan'] ?? 0,
            'pengeluaran' => $data['pengeluaran'] ?? 0,
            'saldo' => $saldoBaru
        ]);

        return response()->json($sosial);
    }

    public function update(Request $request, $id)
    {
        $sosial = Sosial::findOrFail($id);

        $data = $request->validate([
            'tanggal' => 'required|date',
            'subjek' => 'required',
            'pemasukan' => 'nullable|numeric',
            'pengeluaran' => 'nullable|numeric'
        ]);

        // Update baris ini
        $sosial->update([
            'tanggal' => $data['tanggal'],
            'subjek' => $data['subjek'],
            'pemasukan' => $data['pemasukan'] ?? 0,
            'pengeluaran' => $data['pengeluaran'] ?? 0,
        ]);

        // Recalculate saldo semua baris urut ID ascending
        $runningSaldo = 0;
        $allSosial = Sosial::orderBy('id', 'asc')->get();
        foreach ($allSosial as $item) {
            $runningSaldo += $item->pemasukan - $item->pengeluaran;
            $item->saldo = $runningSaldo;
            $item->save();
        }

        return response()->json($sosial);
    }

    public function destroy($id)
    {
        Sosial::destroy($id);

        // Recalculate saldo semua baris urut ID ascending
        $runningSaldo = 0;
        $allSosial = Sosial::orderBy('id', 'asc')->get();
        foreach ($allSosial as $item) {
            $runningSaldo += $item->pemasukan - $item->pengeluaran;
            $item->saldo = $runningSaldo;
            $item->save();
        }

        return response()->json(['message'=>'Data berhasil dihapus']);
    }

    public function pdf()
    {
        $data = Sosial::orderBy('id')->get();

        return Pdf::loadView('pdf.pdf', [
            'data'  => $data,
            'jenis' => 'sosial'
        ])->download('laporan-sosial.pdf');
    }

}
