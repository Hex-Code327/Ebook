<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan daftar user
    public function index()
    {
        $users = User::paginate(10); // Halaman daftar user dengan pagination
        return view('admin.users.index', compact('users'));
    }

    // Menampilkan form tambah user
    public function create()
    {
        return view('admin.users.create');
    }

    // Menyimpan user baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:user,admin',
            'is_paid' => 'required|boolean', // Pastikan untuk menggunakan 'is_paid'
        ]);

        $validated['password'] = Hash::make($validated['password']); // Enkripsi password

        User::create($validated); // Simpan user baru

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    // Menampilkan form edit user
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Mengupdate user
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin',
            'is_paid' => 'required|boolean', // Ganti 'account_type' menjadi 'is_paid'
        ]);

        $user->update($validated); // Update data user

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    // Menghapus user
    public function destroy(User $user)
    {
        $user->delete(); // Hapus user

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    // Toggle status premium user (menggunakan 'is_paid' untuk toggle)
    public function togglePremium($id)
    {
        $user = User::findOrFail($id);
        $user->is_paid = !$user->is_paid; // Toggle status 'is_paid'
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Status premium user berhasil diubah.');
    }
}
