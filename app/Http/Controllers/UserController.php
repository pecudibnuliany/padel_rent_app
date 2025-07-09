<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
        ]);
        $user->update($request->all());
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function index()
    {
        $users = User::where('role', 'user')->get(); // Mengambil hanya user dengan role 'user'
        return view('admin.users.index', compact('users'));
    }


    public function show($id)
    {
        // Logika untuk menampilkan user dengan ID tertentu
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function destroy($id)
    {
        // Cari user berdasarkan ID atau gagal jika tidak ditemukan
        $user = User::findOrFail($id);

        // Hapus user
        $user->delete();

        // Redirect ke halaman daftar user dengan pesan sukses
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

}

