<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use Illuminate\Http\Request;

class InventarisWebController extends Controller
{
    public function index()
    {
        // Urut berdasarkan ID ascending supaya tampilannya konsisten
        $inventaris = Inventaris::orderBy('id', 'asc')->get();
        return view('pages.inventaris', ['pageTitle'=>'Inventaris','inventaris'=>$inventaris]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'subjek' => 'required',
            'pemasukan' => 'nullable|numeric',
            'pengeluaran' => 'nullable|numeric'
        ]);

        $saldoTerakhir = Inventaris::orderBy('id','desc')->value('saldo') ?? 0;
        $saldoBaru = $saldoTerakhir + ($data['pemasukan'] ?? 0) - ($data['pengeluaran'] ?? 0);

        $inventaris = Inventaris::create([
            'tanggal' => $data['tanggal'],
            'subjek' => $data['subjek'],
            'pemasukan' => $data['pemasukan'] ?? 0,
            'pengeluaran' => $data['pengeluaran'] ?? 0,
            'saldo' => $saldoBaru
        ]);

        return response()->json($inventaris);
    }

    public function update(Request $request, $id)
    {
        $inventaris = Inventaris::findOrFail($id);

        $data = $request->validate([
            'tanggal' => 'required|date',
            'subjek' => 'required',
            'pemasukan' => 'nullable|numeric',
            'pengeluaran' => 'nullable|numeric'
        ]);

        // Update baris ini
        $inventaris->update([
            'tanggal' => $data['tanggal'],
            'subjek' => $data['subjek'],
            'pemasukan' => $data['pemasukan'] ?? 0,
            'pengeluaran' => $data['pengeluaran'] ?? 0,
        ]);

        // Recalculate saldo semua baris urut ID ascending
        $runningSaldo = 0;
        $allInventaris = Inventaris::orderBy('id', 'asc')->get();
        foreach ($allInventaris as $item) {
            $runningSaldo += $item->pemasukan - $item->pengeluaran;
            $item->saldo = $runningSaldo;
            $item->save();
        }

        return response()->json($inventaris);
    }

    public function destroy($id)
    {
        Inventaris::destroy($id);

        // Recalculate saldo semua baris urut ID ascending
        $runningSaldo = 0;
        $allInventaris = Inventaris::orderBy('id', 'asc')->get();
        foreach ($allInventaris as $item) {
            $runningSaldo += $item->pemasukan - $item->pengeluaran;
            $item->saldo = $runningSaldo;
            $item->save();
        }

        return response()->json(['message'=>'Data berhasil dihapus']);
    }
}
