<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kegiatan::query();

        if ($request->bulan) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->tahun) {
            $query->whereYear('tanggal', $request->tahun);
        }

        if ($request->search) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $sort = $request->get('sort', 'desc');

        $data = $query->orderBy('tanggal', $sort)->orderBy('id', $sort)->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'     => 'required|string|max:255',
            'tanggal'   => 'required|date',
            'deskripsi' => 'required|string',
            'foto'      => 'required|image|max:2048'
        ]);

        $file = $request->file('foto');

        // ✅ simpan file pakai nama unik (aman)
        $path = $file->store('kegiatan', 'public');

        // ✅ simpan nama asli buat UI
        $originalName = $file->getClientOriginalName();

        $kegiatan = Kegiatan::create([
            'judul'         => $data['judul'],
            'tanggal'       => $data['tanggal'],
            'deskripsi'     => $data['deskripsi'],
            'foto'          => $path,
            'foto_original' => $originalName,
        ]);

        return response()->json($kegiatan, 201);
    }

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
            // hapus foto lama kalau ada
            if ($kegiatan->foto) {
                Storage::disk('public')->delete($kegiatan->foto);
            }

            $file = $request->file('foto');
            $path = $file->store('kegiatan', 'public');
            $originalName = $file->getClientOriginalName();

            $kegiatan->foto = $path;
            $kegiatan->foto_original = $originalName;
        }

        $kegiatan->save();

        return response()->json($kegiatan);
    }

    public function destroy($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        if ($kegiatan->foto) {
            Storage::disk('public')->delete($kegiatan->foto);
        }

        $kegiatan->delete();

        return response()->json(['message' => 'Kegiatan deleted']);
    }
}
