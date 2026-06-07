<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FcmToken;

class NotifikasiController extends Controller
{
    /**
     * Simpan FCM token dari browser user
     */
    public function registerToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'device_info' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        // Upsert: update jika token sudah ada, atau buat baru
        FcmToken::updateOrCreate(
            ['token' => $request->token],
            [
                'id_akun' => $user->id_akun,
                'device_info' => $request->device_info ?? $this->detectDeviceInfo($request),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'FCM token berhasil disimpan.',
        ]);
    }

    /**
     * Hapus FCM token (saat user logout atau menonaktifkan notifikasi)
     */
    public function removeToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        FcmToken::where('token', $request->token)
            ->where('id_akun', Auth::user()->id_akun)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'FCM token berhasil dihapus.',
        ]);
    }

    /**
     * Deteksi info perangkat dari User-Agent
     */
    private function detectDeviceInfo(Request $request): string
    {
        $userAgent = $request->userAgent() ?? 'Unknown';

        // Deteksi browser
        $browser = 'Unknown Browser';
        if (str_contains($userAgent, 'Chrome') && !str_contains($userAgent, 'Edg')) {
            $browser = 'Chrome';
        } elseif (str_contains($userAgent, 'Firefox')) {
            $browser = 'Firefox';
        } elseif (str_contains($userAgent, 'Edg')) {
            $browser = 'Edge';
        } elseif (str_contains($userAgent, 'Safari') && !str_contains($userAgent, 'Chrome')) {
            $browser = 'Safari';
        }

        // Deteksi OS
        $os = 'Unknown OS';
        if (str_contains($userAgent, 'Windows')) {
            $os = 'Windows';
        } elseif (str_contains($userAgent, 'Mac')) {
            $os = 'macOS';
        } elseif (str_contains($userAgent, 'Linux')) {
            $os = 'Linux';
        } elseif (str_contains($userAgent, 'Android')) {
            $os = 'Android';
        } elseif (str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad')) {
            $os = 'iOS';
        }

        return "{$browser} - {$os}";
    }
}
