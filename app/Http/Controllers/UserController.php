<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Tampilkan daftar user (hanya role 'user'), gunakan pagination
    public function index()
    {
        $users = User::where('role', 'user')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // Tampilkan form edit user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // Update data user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
        ]);
        $user->update($validated);
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    // Tampilkan detail user
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    // Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}

