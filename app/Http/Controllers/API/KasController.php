<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KasController extends Controller
{
    public function index(Request $request)
    {
        $query = Kas::query();

        if ($request->bulan) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->tahun) {
            $query->whereYear('tanggal', $request->tahun);
        }

        // SORT tanggal (default DESC)
        $sort = $request->get('sort', 'desc');

        return response()->json(
            $query
                ->orderBy('tanggal', $sort)
                ->orderBy('ID', $sort)
                ->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'subjek' => 'required',
            'pemasukan' => 'nullable|numeric',
            'pengeluaran' => 'nullable|numeric',
        ]);

        return Kas::create([
            'tanggal' => $data['tanggal'],
            'subjek' => $data['subjek'],
            'pemasukan' => $data['pemasukan'] ?? 0,
            'pengeluaran' => $data['pengeluaran'] ?? 0,
        ]);
    }

    public function update(Request $request, $id)
    {
        $kas = Kas::findOrFail($id);

        $data = $request->validate([
            'tanggal' => 'required|date',
            'subjek' => 'required',
            'pemasukan' => 'nullable|numeric',
            'pengeluaran' => 'nullable|numeric',
        ]);

        $kas->update($data);
        return $kas;
    }

    public function destroy($id)
    {
        Kas::destroy($id);
        return response()->json(['message' => 'deleted']);
    }

    public function pdf(Request $request)
    {
        $query = Kas::query();

        // SEARCH
        if ($request->filled('search')) {
            $query->where('subjek', 'like', '%' . $request->search . '%');
        }

        // BULAN (React sudah 1–12)
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        // TAHUN
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $data = $query
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

        return Pdf::loadView('pdf.pdf', [
            'data'  => $data,
            'jenis' => 'kas'
        ])->download('laporan-kas.pdf');
    }
}
