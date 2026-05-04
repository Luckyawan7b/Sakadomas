<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Desa;
use App\Notifications\ResetPasswordNotification;


class Akun extends Authenticatable
{
    use Notifiable;
    protected $table = 'akun';
    protected $primaryKey = 'id_akun';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'nama',
        'alamat',
        'no_hp',
        'email',
        'role',
        'id_desa',
    ];

    protected $hidden = [
        'password',
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'id_desa', 'id_desa');
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}

