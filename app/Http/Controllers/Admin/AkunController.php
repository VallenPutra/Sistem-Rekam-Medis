<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role')->orderBy('name')->get();
        return view('admin.akun.index', compact('users'));
    }

    public function create()
    {
        return view('admin.akun.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6|confirmed',
            'role'      => 'required|in:admin,dokter',
            'no_hp'     => 'nullable|string|max:15',
            'spesialis' => 'nullable|string|max:100',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'no_hp'     => $request->no_hp,
            'spesialis' => $request->spesialis,
            'aktif'     => true,
        ]);

        return redirect()->route('admin.akun.index')->with('success', 'Akun berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return view('admin.akun.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'role'      => 'required|in:admin,dokter',
            'no_hp'     => 'nullable|string|max:15',
            'spesialis' => 'nullable|string|max:100',
            'password'  => 'nullable|min:6|confirmed',
        ]);

        $data = $request->except(['password', 'password_confirmation', '_token', '_method']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('admin.akun.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function toggleAktif(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menonaktifkan akun sendiri.');
        }
        $user->update(['aktif' => !$user->aktif]);
        return back()->with('success', 'Status akun berhasil diubah.');
    }

    /**
     * TAMBAHAN: Fungsi Hapus (Soft Delete) Akun Pengguna / Dokter
     */
    public function destroy(User $user)
    {
        // Cegah admin menghapus dirinya sendiri yang sedang login
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri yang sedang digunakan.');
        }

        // Menjalankan soft delete karena model User sudah pakai 'use SoftDeletes'
        $user->delete();

        return redirect()->route('admin.akun.index')->with('success', 'Akun berhasil dihapus.');
    }
}