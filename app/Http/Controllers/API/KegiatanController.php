<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class KegiatanController extends Controller
{
    // =====================
    // LIST KEGIATAN
    // =====================
    public function index(Request $request)
    {
        $query = Kegiatan::query();

        // FILTER TANGGAL BULAN
        if ($request->bulan) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        // FILTER TAHUN
        if ($request->tahun) {
            $query->whereYear('tanggal', $request->tahun);
        }

        // SEARCH JUDUL
        if ($request->search) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $sort = $request->get('sort', 'desc');

        $data = $query->orderBy('tanggal', $sort)->orderBy('id', $sort)->get();

        return response()->json($data);
    }

    // =====================
    // SIMPAN KEGIATAN
    // =====================
    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'     => 'required|string|max:255',
            'tanggal'   => 'required|date',
            'deskripsi' => 'required|string',
            'foto'      => 'required|image|max:2048'
        ]);

        $foto = $request->file('foto')->store('kegiatan', 'public');

        $kegiatan = Kegiatan::create([
            'judul'     => $data['judul'],
            'tanggal'   => $data['tanggal'],
            'deskripsi' => $data['deskripsi'],
            'foto'      => $foto,
        ]);

        return response()->json($kegiatan, 201);
    }

    // =====================
    // UPDATE KEGIATAN
    // =====================
    public function update(Request $request, $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        $data = $request->validate([
            'judul'     => 'required|string|max:255',
            'tanggal'   => 'required|date',
            'deskripsi' => 'required|string',
            'foto'      => 'nullable|image|max:2048'
        ]);

        $kegiatan->judul     = $data['judul'];
        $kegiatan->tanggal   = $data['tanggal'];
        $kegiatan->deskripsi = $data['deskripsi'];

        if ($request->hasFile('foto')) {
            // Hapus foto lama
            Storage::disk('public')->delete($kegiatan->foto);
            $kegiatan->foto = $request->file('foto')->store('kegiatan', 'public');
        }

        $kegiatan->save();

        return response()->json($kegiatan);
    }

    // =====================
    // DELETE KEGIATAN
    // =====================
    public function destroy($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        Storage::disk('public')->delete($kegiatan->foto);
        $kegiatan->delete();

        return response()->json(['message' => 'Kegiatan deleted']);
    }
}
