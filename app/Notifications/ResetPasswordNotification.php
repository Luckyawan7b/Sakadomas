<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    // 1. Tangkap token dari proses reset
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    // 2. Rangkai tampilan dan isi pesan Email di sini
    public function toMail($notifiable)
    {
        // Generate URL untuk tombol reset (mengarahkan ke route yang sudah kita buat)
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
        $linkGambar = 'https://i.postimg.cc/Fs1fm7NW/logo-sakadomas.png';

        return (new MailMessage)
            ->subject('Reset Password - SMART-SAKA') // Subjek Email
            ->greeting('Halo, ' . $notifiable->nama . '!') // Sapaan
            ->line(new HtmlString('<div style="text-align: center; margin: 20px 0;"><img src="' . $linkGambar . '" alt="Header SMART-SAKA" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></div>'))
            ->line('Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda di sistem SMART-SAKA.')
            ->line('Password SMART-SAKA anda dapat direset dengan mengklik tombol di bawah ini, jika Anda tidak pernah meminta reset password, abaikan saja email ini.')
            ->action('Reset Password', $url) // Teks Tombol & URL
            // ->line('Jika Anda tidak pernah meminta reset password, abaikan saja email ini.')
            // ->line('Link reset password ini akan kadaluarsa dalam 60 menit.')
            ->salutation(new HtmlString('Salam interaksi 🙏, <br> SMART-SAKA'));
    }
}
