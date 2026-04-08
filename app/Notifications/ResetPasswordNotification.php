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
        // $linkGambar = 'https://i.postimg.cc/Fs1fm7NW/logo-sakadomas.png';
        $linkGambar = 'https://i.postimg.cc/vmQ89wSd/1.png';

        $linkGambar1 = 'https://i.postimg.cc/W3pZhjSV/112131.jpg';
        // $linkGambar2 = 'https://i.postimg.cc/dth82sFL/Whats_App_Image_2026_04_08_at_17_30_23.jpg';

        // return (new MailMessage)
        //     ->subject('Reset Password - SMART-SAKA') // Subjek Email
        //     // ->line(new HtmlString('<div style="text-align: center; margin: 20px 0;"><img src="' . $linkGambar . '" alt="Header SMART-SAKA" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></div>'))
        //     // ->greeting(new HtmlString('<div style="text-align: center; margin: 20px 0;"><img src="' . $linkGambar . '" alt="Header SMART-SAKA" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></div>'))
        //     ->greeting(new HtmlString('<div style="text-align: center; margin: 20px 0;"><img src="' . $linkGambar . '" style="max-width:100%; border-radius:8px;"></div><p style="font-weight: bold; font-size: 20px; color:#000;">Halo, ' . $notifiable->nama . '!</p>'))
        //     ->line('Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda di sistem SMART-SAKA.')
        //     ->line('Password SMART-SAKA anda dapat direset dengan mengklik tombol di bawah ini, jika Anda tidak pernah meminta reset password, abaikan saja email ini.')
        //     ->action('Reset Password', $url) // Teks Tombol & URL
        //     // ->line('Jika Anda tidak pernah meminta reset password, abaikan saja email ini.')
        //     // ->line('Link reset password ini akan kadaluarsa dalam 60 menit.')
        //     ->salutation(new HtmlString('Salam interaksi 🙏, <br> SMART-SAKA'));

        return (new MailMessage)
            ->subject('Apresiasi atas Kontribusi dan Performa Tim') // Subjek Email
            ->greeting('Dear, HoshiController!') // Sapaan
            ->line(new HtmlString('<div style="text-align: center; margin: 20px 0;"><img src="' . $linkGambar1 . '" alt="Header SMART-SAKA" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></div>'))
            ->line('Saya ingin menyampaikan apresiasi atas kontribusi luar biasa yang Anda berikan dalam permainan Mobile Legends terakhir. Performa Anda yang mampu mengangkat dan membawa tim menuju kemenangan merupakan pencapaian yang sangat membanggakan.')
            ->line(new HtmlString('Perjalanan yang telah dilalui—dari fase penuh tantangan hingga menjadi salah satu pilar utama tim—menunjukkan perkembangan yang signifikan dan dedikasi yang patut dihargai. Kami berharap performa positif ini dapat terus dipertahankan dan ditingkatkan ke depannya.'))
            ->line(new HtmlString('Terima kasih atas kerja keras dan kontribusinya untuk tim.'))
            // ->line(new HtmlString('<div style="text-align: center; margin: 20px 0;"><img src="' . $linkGambar2 . '" alt="Header SMART-SAKA" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></div>'))
            // ->line('Perjalanan yang telah dilalui—dari fase penuh tantangan hingga menjadi salah satu pilar utama tim—menunjukkan perkembangan yang signifikan dan dedikasi yang patut dihargai. Kami berharap performa positif ini dapat terus dipertahankan dan ditingkatkan ke depannya.')
            ->salutation(new HtmlString('Shanghai Moonton Technology Co.,Ltd.'));


    }
}
