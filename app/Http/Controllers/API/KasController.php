<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kas;
use App\Models\IuranPayment;
use App\Services\IuranKasSyncService; // ✅ tambah
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
        $kas = Kas::findOrFail($id);

        $subjek = trim((string) $kas->subjek);

        $bulan = null;
        $tahun = null;

        if (stripos($subjek, 'Iuran bulan ') === 0) {
            $rest = trim(substr($subjek, strlen('Iuran bulan '))); // "Februari 2026"
            $parts = preg_split('/\s+/', $rest);

            $tahunParsed = (int) end($parts);
            array_pop($parts);

            $bulanNama = strtolower(trim(implode(' ', $parts)));

            $mapBulan = [
                'januari' => 1,
                'februari' => 2,
                'maret' => 3,
                'april' => 4,
                'mei' => 5,
                'juni' => 6,
                'juli' => 7,
                'agustus' => 8,
                'september' => 9,
                'oktober' => 10,
                'november' => 11,
                'desember' => 12,
            ];

            if ($tahunParsed >= 2000 && $tahunParsed <= 2100 && isset($mapBulan[$bulanNama])) {
                $bulan = $mapBulan[$bulanNama];
                $tahun = $tahunParsed;

                // ✅ hapus semua iuran payment pada bulan+tahun tsb (semua anggota)
                IuranPayment::where('bulan', $bulan)
                    ->where('tahun', $tahun)
                    ->delete();
            }
        }

        // hapus kasnya
        $kas->delete();

        // ✅ pastikan kas iuran bulan tsb konsisten (harusnya hilang karena total=0)
        if ($bulan !== null && $tahun !== null) {
            IuranKasSyncService::syncMonthlyKas((int)$bulan, (int)$tahun);
        }

        return response()->json(['message' => 'deleted']);
    }

    public function pdf(Request $request)
    {
        $query = Kas::query();

        if ($request->filled('search')) {
            $query->where('subjek', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

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
