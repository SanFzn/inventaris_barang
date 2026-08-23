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
            'nama_lengkap' => 'required|string',
            'password' => 'required|min:6',
        ]);

        $user = User::where('nama_lengkap', $request->nama_lengkap)->first();

        if ($user && $this->passwordMatches($request->password, $user->password)) {
            if (!$this->isHashedPassword($user->password)) {
                $user->password = Hash::make($request->password);
                $user->save();
            }

            Auth::login($user);
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        return back()->withErrors([
            'nama_lengkap' => 'Nama lengkap atau password salah.',
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
            'nama_lengkap' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'karyawan',
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


    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}