<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\IuranMember;
use App\Models\IuranPayment; // ✅ tambah
use App\Services\IuranKasSyncService; // ✅ tambah
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IuranMemberController extends Controller
{
  public function index(Request $request)
  {
    $q = IuranMember::query();

    if ($request->search) {
      $s = strtolower($request->search);
      $q->whereRaw('LOWER(nama) like ?', ["%{$s}%"])
        ->orWhereRaw('LOWER(kode) like ?', ["%{$s}%"]);
    }

    return response()->json(
      $q->orderBy('kode', 'asc')->get()
    );
  }

  public function store(Request $request)
  {
    $data = $request->validate([
      'nama' => 'required|string|max:255',
    ]);

    $last = IuranMember::orderBy('id', 'desc')->first();
    $nextNumber = $last ? ($last->id + 1) : 1;
    $kode = 'A-' . str_pad((string)$nextNumber, 3, '0', STR_PAD_LEFT);

    $member = IuranMember::create([
      'kode' => $kode,
      'nama' => $data['nama'],
    ]);

    return response()->json($member, 201);
  }

  public function update(Request $request, $id)
  {
    $member = IuranMember::findOrFail($id);

    $data = $request->validate([
      'nama' => 'required|string|max:255',
    ]);

    $member->update($data);

    return response()->json($member);
  }

  public function destroy($id)
  {
    $member = IuranMember::findOrFail($id);

    // ✅ ambil semua (bulan,tahun) yang terdampak sebelum delete
    $pairs = IuranPayment::where('member_id', $member->id)
      ->select('bulan', 'tahun')
      ->distinct()
      ->get();

    // hapus member (payments akan ikut terhapus karena cascade)
    $member->delete();

    // ✅ sync kas untuk semua bulan/tahun yang tadi punya payment
    foreach ($pairs as $p) {
      IuranKasSyncService::syncMonthlyKas((int)$p->bulan, (int)$p->tahun);
    }

    return response()->json(['message' => 'deleted']);
  }
}
