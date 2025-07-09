<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Method untuk menampilkan halaman login
    public function showLoginForm()
    {
        // Cek apakah user sudah login
        if (Auth::check()) {
            // Redirect ke halaman dashboard atau halaman lain sesuai role
            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('index');
        }

        // Jika belum login, tampilkan halaman login
        return view('auth.login');
    }

    // Method untuk login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Cek role user setelah login
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect('/');
        }

        return back()->withErrors(['email' => 'Email atau password salah']);
    }

    // Method untuk menampilkan halaman register
    public function showRegisterForm()
    {
        // Cek apakah user sudah login
        if (Auth::check()) {
            // Redirect ke halaman dashboard atau halaman lain sesuai role
            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('index'); // Halaman untuk user biasa
        }

        // Jika belum login, tampilkan halaman register
        return view('auth.register');
    }


    // Method untuk register
    public function register(Request $request)
    {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('register')
                            ->withErrors($validator)
                            ->withInput();
        }

        // Membuat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Redirect ke halaman login setelah registrasi
        return redirect()->route('login')->with('status', 'Registration successful! Please login.');
    }

    // Method untuk logout
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
