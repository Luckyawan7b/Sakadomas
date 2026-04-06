<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Models\akunModel;
use App\Models\kecamatanModel;
use App\Models\desaModel;

class akunController extends Controller
{
    public function getKecamatan()
    {
        $kecamatan = kecamatanModel::all();
        return response()->json($kecamatan);
    }

    public function getDesaByKecamatan($id_kecamatan)
    {
        $desa = desaModel::where('id_kecamatan', $id_kecamatan)->get();
        return response()->json($desa);
    }

    public function showRegister()
    {
        $kecamatan = kecamatanModel::all();
        return view('pages.auth.signup', ['title' => 'Sign Up'], compact('kecamatan'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:akun,username|alpha_num',
            'email'    => 'nullable|email|max:100|unique:akun,email',
            'password' => 'required|string|min:6',
            'nama'     => 'required|string|max:255',
            'alamat'   => 'required|string',
            'no_hp'    => 'required|string|max:15',
            'id_desa'  => 'required|integer|exists:desa,id_desa'
        ], [
            // Pesan error kustom untuk alpha_num
            'username.alpha_num' => 'Username hanya boleh berisi huruf dan angka tanpa spasi atau simbol.'
        ]);

        akunModel::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'nama'     => $request->nama,
            'alamat'   => $request->alamat,
            'no_hp'    => $request->no_hp,
            'email'    => $request->email,
            'role'     => 'user',
            'id_desa'  => $request->id_desa,
        ]);

        return back()->with('success', 'Akun Anda berhasil dibuat. Silakan menuju halaman login untuk masuk.');
    }

    public function showLogin()
    {
        return view('pages.auth.signin');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
    // IKI GET ALL
    public function index(Request $request)
    {
        // 1. Buat kerangka Query dasar (Hanya ambil yang rolenya 'user')
        $query = akunModel::with('desa.kecamatan')
                          ->where('role', 'user')
                          ->orderBy('id_akun', 'desc');

        // 2. Jika ada inputan pencarian di URL (?q=...)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('nama', 'LIKE', "%{$q}%")
                  ->orWhere('username', 'LIKE', "%{$q}%")
                  ->orWhere('email', 'LIKE', "%{$q}%")
                  ->orWhere('no_hp', 'LIKE', "%{$q}%");
            });
        }

        // 3. Eksekusi Query dengan Pagination (10 data per halaman)
        $data_akun = $query->paginate(10);

        // Ambil data untuk modal tambah/edit
        $kecamatan = \App\Models\kecamatanModel::all();
        $desa = \App\Models\desaModel::all();

        return view('pages.akun', compact('data_akun', 'kecamatan', 'desa'));
    }
    // IKI GET BY ID
    public function show($id)
    {
        $akun = akunModel::findOrFail($id);
        return view('pages.akun', compact('akun'));
    }

    public function edit($id)
    {
        $akun = akunModel::findOrFail($id);
        return view('pages.akun', compact('akun'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|alpha_num|unique:akun,username,' . $id . ',id_akun',
            'email' => 'nullable|email|max:100|unique:akun,email,' . $id . ',id_akun',
            'no_hp' => 'required|string|max:15',
            'id_desa' => 'required|integer|exists:desa,id_desa',
            'alamat' => 'required|string',
        ], [
            'username.alpha_num' => 'Username hanya boleh berisi huruf dan angka tanpa spasi atau simbol.'
        ]);

        $akun = akunModel::findOrFail($id);
        $akun->update([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'id_desa' => $request->id_desa,
            'alamat' => $request->alamat,
        ]);

        return back()->with('success', 'Data akun berhasil diperbarui.');
    }


    public function profile()
    {
        return view('pages.profile', [
            'title' => 'Profile'
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|alpha_num|unique:akun,username,' . $user->id_akun . ',id_akun',
            'email' => 'nullable|email|max:100|unique:akun,email,' . $user->id_akun . ',id_akun',
            'no_hp' => 'required|string|max:15',
            'id_desa' => 'required|integer|exists:desa,id_desa',
            'alamat' => 'required|string',
        ], [
            'username.alpha_num' => 'Username hanya boleh berisi huruf dan angka tanpa spasi atau simbol.'
        ]);

        $akun = \App\Models\akunModel::findOrFail($user->id_akun);
        $akun->update([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'id_desa' => $request->id_desa,
            'alamat' => $request->alamat,
        ]);

        return back()->with('success', 'Profil Anda berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:akun,username|alpha_num',
            'email'    => 'nullable|email|max:100|unique:akun,email',
            'password' => 'required|string|min:6',
            'nama'     => 'required|string|max:255',
            'alamat'   => 'required|string',
            'no_hp'    => 'required|string|max:15',
            'id_desa'  => 'required|integer|exists:desa,id_desa'
        ], [
            'username.alpha_num' => 'Username hanya boleh berisi huruf dan angka tanpa spasi atau simbol.'
        ]);

        akunModel::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'nama'     => $request->nama,
            'alamat'   => $request->alamat,
            'no_hp'    => $request->no_hp,
            'email'    => $request->email,
            'role'     => 'user',
            'id_desa'  => $request->id_desa,
        ]);

        return back()->with('success', 'Akun pengguna baru berhasil ditambahkan.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required|string',
            'password_baru' => 'required|string|min:6|confirmed',
        ], [
            'password_baru.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password_baru.min' => 'Password baru minimal harus 6 karakter.'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama yang Anda masukkan salah.']);
        }

        $akun = \App\Models\akunModel::findOrFail($user->id_akun);
        $akun->update([
            'password' => Hash::make($request->password_baru)
        ]);

        return back()->with('success', 'Password Anda berhasil diperbarui!');
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password_baru' => 'required|string|min:6|confirmed',
        ], [
            'password_baru.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password_baru.min' => 'Password baru minimal harus 6 karakter.'
        ]);

        $akun = akunModel::findOrFail($id);
        $akun->update([
            'password' => Hash::make($request->password_baru)
        ]);

        return back()->with('success', 'Password untuk pengguna ' . $akun->nama . ' berhasil direset.');
    }

    public function showForgotPassword()
    {
        return view('pages.lupapassword', ['title' => 'Lupa Password']);
    }

    // Mengirim Link Reset ke Email
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:akun,email'], [
            'email.exists' => 'Email ini tidak terdaftar di sistem kami.'
        ]);

        // Mengirim email bawaan Laravel
        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Kami telah mengirimkan link reset password ke email Anda!');
        }

        return back()->withErrors(['email' => 'Gagal mengirim link reset password. Coba lagi nanti.']);
    }

    // Menampilkan Halaman Ganti Password Baru
    public function showResetPassword(Request $request, $token)
    {
        return view('pages.resetpassword', [
            'title' => 'Reset Password',
            'token' => $token,
            'email' => $request->email
        ]);
    }

    //  Perubahan Password
    public function submitResetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:akun,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min' => 'Password minimal harus 6 karakter.'
        ]);

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect('/login')->with('success', 'Password Anda berhasil direset! Silakan login dengan password baru.');
        }

        return back()->withErrors(['email' => 'Token reset password tidak valid atau sudah kadaluarsa.']);
    }
}
