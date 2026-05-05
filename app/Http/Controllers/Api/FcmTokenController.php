<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FcmToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FcmTokenController extends Controller
{
    /**
     * Register FCM token.
     * Menggunakan updateOrCreate agar:
     * - Token yang sama tidak tersimpan duplikat
     * - Jika token sudah ada tapi milik akun lain (ganti user), diupdate ke akun saat ini
     */
    public function register(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        FcmToken::updateOrCreate(
            ['token' => $request->token],          // Cari berdasarkan token
            ['id_akun' => Auth::id()]               // Update/set id_akun-nya
        );

        return response()->json(['message' => 'Token registered successfully.']);
    }

    /**
     * Remove FCM token saat user menonaktifkan notifikasi.
     */
    public function remove(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        FcmToken::where('token', $request->token)
            ->where('id_akun', Auth::id())
            ->delete();

        return response()->json(['message' => 'Token removed successfully.']);
    }
}
