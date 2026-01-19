<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class HakAksesController extends Controller
{
    /**
     * Ambil semua user
     * Bisa filter nama, email, username
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $keyword = strtolower($request->search);
            $query->where(function($q) use ($keyword) {
                $q->whereRaw('LOWER(nama) LIKE ?', ["%{$keyword}%"])
                  ->orWhereRaw('LOWER(email) LIKE ?', ["%{$keyword}%"])
                  ->orWhereRaw('LOWER(username) LIKE ?', ["%{$keyword}%"]);
            });
        }

        $sort = $request->get('sort', 'desc'); // default desc
        $query->orderBy('id', $sort);

        return response()->json($query->get());
    }

    /**
     * Tambah user baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'username' => 'required|string|max:100|unique:users,username',
            'password' => 'required|string|min:6',
            'role'     => 'required|string|max:50'
        ]);

        $user = User::create([
            'nama'     => $validated['nama'],
            'email'    => $validated['email'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan',
            'user'    => $user
        ]);
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => ['required','email',Rule::unique('users','email')->ignore($id)],
            'username' => ['required','string','max:100',Rule::unique('users','username')->ignore($id)],
            'password' => 'nullable|string|min:6',
            'role'     => 'required|string|max:50'
        ]);

        $data = [
            'nama'     => $validated['nama'],
            'email'    => $validated['email'],
            'username' => $validated['username'],
            'role'     => $validated['role']
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diperbarui',
            'user'    => $user
        ]);
    }

    /**
     * Hapus user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus'
        ]);
    }
}
