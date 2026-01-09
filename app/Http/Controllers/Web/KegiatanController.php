<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KegiatanController extends Controller
{
    // ADMIN (BLADE)
    public function index()
    {
        $kegiatan = Kegiatan::orderBy('id', 'desc')->get();
        return view('pages.kegiatan', compact('kegiatan'), ['pageTitle'=>'Kegiatan']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'     => 'required',
            'tanggal'   => 'required|date',
            'deskripsi' => 'required',
            'foto'      => 'required|image|max:2048'
        ]);

        $foto = $request->file('foto')->store('kegiatan', 'public');

        Kegiatan::create([
            'judul'     => $request->judul,
            'tanggal'   => $request->tanggal,
            'deskripsi' => $request->deskripsi,
            'foto'      => $foto
        ]);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        $kegiatan->judul     = $request->judul;
        $kegiatan->tanggal   = $request->tanggal;
        $kegiatan->deskripsi = $request->deskripsi;

        if ($request->hasFile('foto')) {
            Storage::disk('public')->delete($kegiatan->foto);
            $kegiatan->foto = $request->file('foto')->store('kegiatan', 'public');
        }

        $kegiatan->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        Storage::disk('public')->delete($kegiatan->foto);
        $kegiatan->delete();

        return response()->json(['success' => true]);
    }

    // API (REACT)
    public function apiIndex()
    {
        return response()->json(
            Kegiatan::orderBy('tanggal', 'desc')->get()
        );
    }
}
