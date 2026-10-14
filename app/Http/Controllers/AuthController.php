<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLoginForm()
    {
        return view('login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Proses login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|min:6',
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user && $this->passwordMatches($request->password, $user->password)) {
            if (!$this->isHashedPassword($user->password)) {
                $user->password = Hash::make($request->password);
                $user->save();
            }

            Auth::login($user);
            $request->session()->regenerate();
            return redirect('/dashboard')->with('success', 'Selamat, Anda berhasil login!');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'username' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    private function passwordMatches($password, $storedPassword)
    {
        if ($this->isHashedPassword($storedPassword)) {
            return Hash::check($password, $storedPassword);
        }

        return hash_equals($storedPassword, $password);
    }

    private function isHashedPassword($password)
    {
        return preg_match('/^\$(2y|2b|argon2)/', $password) === 1;
    }


    // Upload foto profil
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'foto_profil.image'    => 'File harus berupa gambar.',
            'foto_profil.mimes'    => 'Format yang didukung: JPEG, PNG, WebP.',
            'foto_profil.max'      => 'Ukuran foto maksimal 2 MB.',
        ]);

        $user = Auth::user();

        // Hapus foto lama jika ada
        if ($user->foto_profil) {
            $oldPath = public_path('uploads/' . $user->foto_profil);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $file      = $request->file('foto_profil');
        $filename  = time() . '_' . $user->id_user . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/foto-profil'), $filename);

        $user->foto_profil = 'foto-profil/' . $filename;
        $user->save();

        return back()->with('profile_success', 'Foto profil berhasil diperbarui.');
    }

    // Hapus foto profil
    public function deletePhoto(Request $request)
    {
        $user = Auth::user();

        if ($user->foto_profil) {
            $oldPath = public_path('uploads/' . $user->foto_profil);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $user->foto_profil = null;
        $user->save();

        return back()->with('profile_success', 'Foto profil berhasil dihapus.');
    }

    // Update profil (username & email)
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id_user . ',id_user',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id_user . ',id_user',
        ]);

        $user->username = $request->username;
        $user->email    = $request->email;
        $user->save();

        return back()->with('profile_success', 'Profil berhasil diperbarui.');
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed',
        ]);

        if (!$this->passwordMatches($request->current_password, $user->password)) {
            return back()->with('profile_error', 'Password saat ini tidak sesuai.');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('profile_success', 'Password berhasil diperbarui.');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda berhasil keluar dari aplikasi.');
    }
}
