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
        // Urut berdasarkan ID ascending supaya tampilannya konsisten
        $kas = Kas::orderBy('id', 'desc')->get();
        return view('pages.kas', ['pageTitle'=>'Kas','kas'=>$kas]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'subjek' => 'required',
            'pemasukan' => 'nullable|numeric',
            'pengeluaran' => 'nullable|numeric'
        ]);

        $kas = Kas::create([
            'tanggal' => $data['tanggal'],
            'subjek' => $data['subjek'],
            'pemasukan' => $data['pemasukan'] ?? 0,
            'pengeluaran' => $data['pengeluaran'] ?? 0,
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

        $kas->update([
            'tanggal' => $data['tanggal'],
            'subjek' => $data['subjek'],
            'pemasukan' => $data['pemasukan'] ?? 0,
            'pengeluaran' => $data['pengeluaran'] ?? 0,
        ]);

        return response()->json($kas);
    }


    public function destroy($id)
    {
        Kas::destroy($id);
        return response()->json(['message'=>'Data berhasil dihapus']);
    }

    public function pdf(Request $request)
    {
        $query = Kas::query();

        // SEARCH
        if ($request->filled('search')) {
            $query->where('subjek', 'like', '%' . $request->search . '%');
        }

        // TAHUN
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        // BULAN
        if ($request->filled('bulan')) {
            // bulan dari JS = 0–11 → +1
            $query->whereMonth('tanggal', $request->bulan + 1);
        }

        $data = $query->orderBy('tanggal')->get();

        return PDF::loadView('pdf.pdf', [
        'data'  => $data,
        'jenis' => 'kas'
    ])->download('laporan-kas.pdf');
    }

}
