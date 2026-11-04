<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Daftar semua pengguna (kecuali diri sendiri).
     */
    public function index(Request $request)
    {
        $query = User::where('id_user', '!=', Auth::id());

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('role')->orderBy('username')->get();

        return view('users.index', [
            'users'    => $users,
            'roleFilter' => $request->input('role', ''),
            'search'   => $request->input('q', ''),
            'totalUsers'   => User::where('role', 'user')->count(),
            'totalKaryawan' => User::where('role', 'karyawan')->count(),
            'totalAdmin'    => User::where('role', 'admin')->count(),
        ]);
    }

    /**
     * Simpan pengguna baru oleh admin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:users,username',
            'email'    => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:user,karyawan,admin',
        ], [
            'username.unique' => 'Username sudah digunakan.',
            'email.unique'    => 'Email sudah digunakan.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('users.index')
            ->with('success', "Akun \"{$request->username}\" berhasil dibuat.");
    }

    /**
     * Update data pengguna.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:users,username,' . $user->id_user . ',id_user',
            'email'    => 'required|email|max:100|unique:users,email,' . $user->id_user . ',id_user',
            'role'     => 'required|in:user,karyawan,admin',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'username.unique' => 'Username sudah digunakan.',
            'email.unique'    => 'Email sudah digunakan.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user->username = $request->username;
        $user->email    = $request->email;
        $user->role     = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')
            ->with('success', "Akun \"{$user->username}\" berhasil diperbarui.");
    }

    /**
     * Hapus pengguna.
     */
    public function destroy(User $user)
    {
        // Jangan hapus diri sendiri
        if ($user->id_user === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $username = $user->username;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "Akun \"{$username}\" berhasil dihapus.");
    }
}
