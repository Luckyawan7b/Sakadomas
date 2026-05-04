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
        return view('auth.register', ['title' => 'Register | SMART-SAKA'], compact('kecamatan'));
        // return view('pages.auth.signup', ['title' => 'Register | SMART-SAKA'], compact('kecamatan'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:4|max:255|unique:akun,username|alpha_num',
            'email'    => 'required|email|max:100|unique:akun,email',
            'password' => ['required', 'string', 'min:6', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
            'nama'     => 'required|string|min:3|max:255',
            'alamat'   => 'required|string|min:5',
            'no_hp'    => 'required|string|min:10|max:15',
            'id_desa'  => 'required|integer|exists:desa,id_desa'
        ], [
            'username.alpha_num' => 'Username hanya boleh berisi huruf dan angka tanpa spasi atau simbol.',
            'username.min'       => 'Username minimal harus terdiri dari 4 karakter.',
            'email.unique'       => 'Email sudah terdaftar.',
            'email.email'        => 'Email tidak sesuai format.',
            'password.regex'     => 'Password harus mengandung setidaknya 1 huruf besar, 1 huruf kecil, dan 1 angka.',
            'password.min'      => 'Password minimal harus terdiri dari 6 karakter.',
            'nama.min'           => 'Nama lengkap minimal harus terdiri dari 3 karakter.',
            'alamat.min'         => 'Alamat minimal harus terdiri dari 5 karakter.',
            'no_hp.min'          => 'Nomor HP minimal harus terdiri dari 10 angka.'
        ]);

        akunModel::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'nama'     => $request->nama,
            'alamat'   => $request->alamat,
            'no_hp'    => $request->no_hp,
            'email'    => $request->email,
            'role'     => 'pelanggan',
            'id_desa'  => $request->id_desa,
        ]);

        return back()->with('success', 'Akun Anda berhasil dibuat. Silakan menuju halaman login untuk masuk.');
    }

    public function showLogin()
    {
        return view('auth.login',['title' => 'Login | SMART-SAKA']);
        // return view('pages.auth.signin',['title' => 'Login | SMART-SAKA']);
    }

   public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $input = $request->input('login');

        $fieldType = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = akunModel::where($fieldType, $input)->first();

        if (!$user) {
            return back()->withErrors([
                'login' => 'Email/Username tidak terdaftar.',
            ])->onlyInput('login');
        }

        $credentials = [
            $fieldType => $input,
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/dashboard');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'password' => 'Password yang Anda masukkan salah.',
        ])->onlyInput('login');
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
                          ->where('role', 'pelanggan')
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
            'nama'     => 'required|string|min:3|max:255',
            'username' => 'required|string|min:4|max:255|alpha_num|unique:akun,username,' . $id . ',id_akun',
            'email'    => 'nullable|email|max:100|unique:akun,email,' . $id . ',id_akun',
            'no_hp'    => 'required|string|min:10|max:15',
            'id_desa'  => 'required|integer|exists:desa,id_desa',
            'alamat'   => 'required|string|min:5',
        ], [
            'username.alpha_num' => 'Username hanya boleh berisi huruf dan angka tanpa spasi atau simbol.',
            'username.min'       => 'Username minimal harus 4 karakter.',
            'nama.min'           => 'Nama minimal harus 3 karakter.',
            'alamat.min'         => 'Alamat minimal harus 5 karakter.',
            'no_hp.min'          => 'Nomor HP minimal harus 10 angka.'
        ]);

        $akun = akunModel::findOrFail($id);
        $akun->update([
            'nama'     => $request->nama,
            'username' => $request->username,
            'email'    => $request->email,
            'no_hp'    => $request->no_hp,
            'id_desa'  => $request->id_desa,
            'alamat'   => $request->alamat,
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
            'nama'     => 'required|string|min:3|max:255',
            'username' => 'required|string|min:4|max:255|alpha_num|unique:akun,username,' . $user->id_akun . ',id_akun',
            'email'    => 'nullable|email|max:100|unique:akun,email,' . $user->id_akun . ',id_akun',
            'no_hp'    => 'required|string|min:10|max:15',
            'id_desa'  => 'required|integer|exists:desa,id_desa',
            'alamat'   => 'required|string|min:5',
        ], [
            'username.alpha_num' => 'Username hanya boleh berisi huruf dan angka tanpa spasi atau simbol.',
            'username.min'       => 'Username minimal harus 4 karakter.',
            'nama.min'           => 'Nama minimal harus 3 karakter.',
            'alamat.min'         => 'Alamat minimal harus 5 karakter.',
            'no_hp.min'          => 'Nomor telepon minimal harus 10 angka.',
            'no_hp.max'          => 'Nomor telepon maksimal 15 angka.'
        ]);

        $akun = akunModel::findOrFail($user->id_akun);
        $akun->update([
            'nama'     => $request->nama,
            'username' => $request->username,
            'email'    => $request->email,
            'no_hp'    => $request->no_hp,
            'id_desa'  => $request->id_desa,
            'alamat'   => $request->alamat,
        ]);

        return back()->with('success', 'Profil Anda berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:4|max:255|unique:akun,username|alpha_num',
            'email'    => 'nullable|email|max:100|unique:akun,email',
            // 'password' => 'required|string|min:6',
            'password' => ['required', 'string', 'min:6', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
            'nama'     => 'required|string|min:3|max:255',
            'alamat'   => 'required|string|min:5',
            'no_hp'    => 'required|string|min:10|max:15',
            'id_desa'  => 'required|integer|exists:desa,id_desa'
        ], [
            'username.alpha_num' => 'Username hanya boleh berisi huruf dan angka tanpa spasi atau simbol.',
            'username.min'       => 'Username minimal harus 4 karakter.',
            'password.regex'     => 'Password harus mengandung setidaknya 1 huruf besar, 1 huruf kecil, dan 1 angka.',
            'nama.min'           => 'Nama minimal harus 3 karakter.',
            'alamat.min'         => 'Alamat minimal harus 5 karakter.',
            'no_hp.min'          => 'Nomor HP minimal harus 10 angka.'
        ]);

        akunModel::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'nama'     => $request->nama,
            'alamat'   => $request->alamat,
            'no_hp'    => $request->no_hp,
            'email'    => $request->email,
            'role'     => 'pelanggan',
            'id_desa'  => $request->id_desa,
        ]);

        return back()->with('success', 'Akun pengguna baru berhasil ditambahkan.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required|string',
            // 'password_baru' => 'required|string|min:6|confirmed',
            'password_baru' => ['required', 'string', 'min:6', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ], [
            'password_baru.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password_baru.min' => 'Password baru minimal harus 6 karakter.',
            'password_baru.regex' => 'Password baru harus mengandung setidaknya 1 huruf besar, 1 huruf kecil, dan 1 angka.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama yang Anda masukkan salah.']);
        }

        $akun = akunModel::findOrFail($user->id_akun);
        $akun->update([
            'password' => Hash::make($request->password_baru)
        ]);

        return back()->with('success', 'Password Anda berhasil diperbarui!');
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password_baru' => ['required', 'string', 'min:6', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ], [
            'password_baru.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password_baru.min' => 'Password baru minimal harus 6 karakter.',
            'password_baru.regex' => 'Password baru harus mengandung setidaknya 1 huruf besar, 1 huruf kecil, dan 1 angka.'
        ]);

        $akun = akunModel::findOrFail($id);
        $akun->update([
            'password' => Hash::make($request->password_baru)
        ]);

        return back()->with('success', 'Password untuk pengguna ' . $akun->nama . ' berhasil direset.');
    }

    public function showForgotPassword()
    {
        // return view('pages.lupapassword', ['title' => 'Lupa Password']);
        return view('auth.forgot-password', ['title' => 'Lupa Password']);
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
        // return view('pages.resetpassword', [
        return view('auth.reset-password', [
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
            // 'password' => 'required|string|min:6|confirmed',
            'password' => ['required', 'string', 'min:6', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ], [
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.regex' => 'Password harus mengandung setidaknya 1 huruf besar, 1 huruf kecil, dan 1 angka.'
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
