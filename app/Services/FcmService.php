<?php

namespace App\Services;

use App\Models\FcmToken;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Exception\MessagingException;

class FcmService
{
    protected Messaging $messaging;

    public function __construct()
    {
        $this->messaging = app('firebase.messaging');
    }

    /**
     * Kirim notifikasi ke satu user berdasarkan id_akun
     */
    public function sendToUser(int $idAkun, string $title, string $body, array $data = []): void
    {
        $tokens = FcmToken::where('id_akun', $idAkun)->pluck('token')->toArray();

        if (empty($tokens)) {
            Log::info("FCM: No tokens found for user #{$idAkun}, skipping notification.");
            return;
        }

        $this->sendToTokens($tokens, $title, $body, $data);
    }

    /**
     * Kirim notifikasi ke semua admin
     */
    public function sendToAllAdmins(string $title, string $body, array $data = []): void
    {
        $tokens = FcmToken::whereHas('akun', fn($q) => $q->where('role', 'admin'))
            ->pluck('token')->toArray();

        if (empty($tokens)) {
            Log::info("FCM: No admin tokens found, skipping notification.");
            return;
        }

        $this->sendToTokens($tokens, $title, $body, $data);
    }

    /**
     * Kirim notifikasi ke array of tokens.
     *
     * PENTING — Menggunakan DATA-ONLY message (tanpa field `notification`).
     *
     * Kenapa? FCM message yang mengandung field `notification` menyebabkan
     * browser menampilkan notifikasi sistem DUA KALI:
     *   1. Otomatis oleh browser via Web Push API (icon Chrome default)
     *   2. Oleh Firebase Service Worker via onBackgroundMessage (icon custom)
     *
     * Dengan data-only, hanya SW yang bertanggung jawab menampilkan notifikasi
     * → satu sumber kebenaran, zero duplikat, full kontrol tampilan.
     */
    private function sendToTokens(array $tokens, string $title, string $body, array $data = []): void
    {
        try {
            // Semua nilai harus string (FCM data payload requirement)
            $payload = array_map('strval', array_merge($data, [
                'title'        => $title,
                'body'         => $body,
                'icon'         => url('/Favicon.ico'),
                'badge'        => url('/Favicon.ico'),
                'click_action' => $data['url'] ?? url('/dashboard'),
            ]));

            // Hapus key 'url' dari data jika sudah dipindah ke click_action
            unset($payload['url']);

            $message = CloudMessage::new()->withData($payload);

            $report = $this->messaging->sendMulticast($message, $tokens);

            // Bersihkan token yang invalid/expired
            if ($report->hasFailures()) {
                foreach ($report->failures()->getItems() as $failure) {
                    $failedToken = $failure->target()->value();
                    Log::warning("FCM: Failed to send to token: {$failedToken} — " . $failure->error()->getMessage());

                    if ($this->isInvalidTokenError($failure->error()->getMessage())) {
                        FcmToken::where('token', $failedToken)->delete();
                        Log::info("FCM: Removed invalid token: {$failedToken}");
                    }
                }
            }

            $successCount = $report->successes()->count();
            $failureCount = $report->failures()->count();
            Log::info("FCM: Sent notification '{$title}' — Success: {$successCount}, Failed: {$failureCount}");

        } catch (MessagingException $e) {
            Log::error("FCM MessagingException: " . $e->getMessage());
        } catch (\Throwable $e) {
            Log::error("FCM Error: " . $e->getMessage());
        }
    }

    /**
     * Cek apakah error menandakan token sudah invalid/expired
     */
    private function isInvalidTokenError(string $errorMessage): bool
    {
        $invalidPatterns = [
            'not-found',
            'invalid-registration-token',
            'registration-token-not-registered',
            'UNREGISTERED',
            'INVALID_ARGUMENT',
        ];

        foreach ($invalidPatterns as $pattern) {
            if (stripos($errorMessage, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }
}
