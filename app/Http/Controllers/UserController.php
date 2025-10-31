<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LembagaPaud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua user
     */
    public function index()
    {
        $users = User::with('lembagaPaud')->orderBy('created_at', 'desc')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Form tambah user baru
     */
    public function create()
    {
        $lembaga = LembagaPaud::all();
        return view('users.create', compact('lembaga'));
    }

    /**
     * Simpan user baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6',
            'role'       => 'required|in:super_admin,lembaga',
            'lembaga_id' => 'nullable|exists:lembaga_paud,id',
        ]);

        User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => $request->role,
            'lembaga_id' => $request->lembaga_id,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Form edit user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $lembaga = LembagaPaud::all();
        return view('users.edit', compact('user', 'lembaga'));
    }

    /**
     * Update data user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'role'       => 'required|in:super_admin,lembaga',
            'lembaga_id' => 'nullable|exists:lembaga_paud,id',
            'password'   => 'nullable|min:6',
        ]);

        $user->update([
            'name'       => $request->name,
            'email'      => $request->email,
            'role'       => $request->role,
            'lembaga_id' => $request->lembaga_id,
            'password'   => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function editAccount(Request $request): View
    {
        return view('backend.account', [
            'user' => $request->user(),
        ]);
    }

    public function updateAccount(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Hapus user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
