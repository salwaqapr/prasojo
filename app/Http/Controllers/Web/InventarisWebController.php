<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InventarisWebController extends Controller
{
    public function index()
    {
        // Urut berdasarkan ID ascending supaya tampilannya konsisten
        $inventaris = Inventaris::orderBy('id', 'desc')->get();
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

        $inventaris = Inventaris::create([
            'tanggal' => $data['tanggal'],
            'subjek' => $data['subjek'],
            'pemasukan' => $data['pemasukan'] ?? 0,
            'pengeluaran' => $data['pengeluaran'] ?? 0,
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

        $inventaris->update([
            'tanggal' => $data['tanggal'],
            'subjek' => $data['subjek'],
            'pemasukan' => $data['pemasukan'] ?? 0,
            'pengeluaran' => $data['pengeluaran'] ?? 0,
        ]);

        return response()->json($inventaris);
    }


    public function destroy($id)
    {
        Inventaris::destroy($id);
        return response()->json(['message'=>'Data berhasil dihapus']);
    }

    public function pdf()
    {
        $data = Inventaris::orderBy('id')->get();

        return Pdf::loadView('pdf.pdf', [
            'data'  => $data,
            'jenis' => 'inventaris'
        ])->download('laporan-inventaris.pdf');
    }
}
