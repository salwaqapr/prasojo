<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InventarisController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventaris::query();

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
                ->orderBy('id', $sort)
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

        return Inventaris::create([
            'tanggal' => $data['tanggal'],
            'subjek' => $data['subjek'],
            'pemasukan' => $data['pemasukan'] ?? 0,
            'pengeluaran' => $data['pengeluaran'] ?? 0,
        ]);
    }

    public function update(Request $request, $id)
    {
        $inventaris = Inventaris::findOrFail($id);

        $data = $request->validate([
            'tanggal' => 'required|date',
            'subjek' => 'required',
            'pemasukan' => 'nullable|numeric',
            'pengeluaran' => 'nullable|numeric',
        ]);

        $inventaris->update($data);
        return $inventaris;
    }

    public function destroy($id)
    {
        Inventaris::destroy($id);
        return response()->json(['message' => 'deleted']);
    }

    public function pdf(Request $request)
    {
        $query = Inventaris::query();

        // SEARCH
        if ($request->filled('search')) {
            $query->where('subjek', 'like', '%' . $request->search . '%');
        }

        // BULAN
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
            'jenis' => 'inventaris'
        ])->download('laporan-inventaris.pdf');
    }
}
