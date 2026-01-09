<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HakAksesController extends Controller
{
    public function index()
    {
        return view('pages.hakAkses', [
            'pageTitle' => 'Hak Akses',
            'users' => User::orderBy('id', 'desc')->get()
        ]);
    }

    // TAMBAH USER
    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'username' => 'required|string|max:100|unique:users,username',
            'password' => 'required|string|min:6',
            'role'     => 'required|string|max:50'
        ],
        [
            'email.email' => 'Email harus mengandung @gmail.com',
            'password.min' => 'Password minimal 6 karakter',
        ]
    );

        User::create([
            'nama'      => $request->nama,
            'email'     => $request->email,
            'username'  => $request->username,
            'password'  => Hash::make($request->password), // ✅ HASH
            'role'      => $request->role,
            'is_active' => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan'
        ]);
    }


    // UPDATE USER
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'username' => 'required|string|max:100|unique:users,username,' . $id,
            'password' => 'nullable|string|min:6', // 🔥 BOLEH KOSONG
            'role'     => 'required|string|max:50'
        ],
        [
            'email.email' => 'Email harus mengandung @gmail.com',
            'password.min' => 'Password minimal 6 karakter',
        ]
    );

        $data = [
            'nama'     => $request->nama,
            'email'    => $request->email,
            'username' => $request->username,
            'role'     => $request->role
        ];

        // 🔐 HASH HANYA JIKA PASSWORD DIISI
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diperbarui'
        ]);
    }

    // HAPUS USER
    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus'
        ]);
    }
}
