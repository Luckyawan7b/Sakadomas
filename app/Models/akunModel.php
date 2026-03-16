<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\desaModel;


class akunModel extends Authenticatable
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
        return $this->belongsTo(desaModel::class, 'id_desa', 'id_desa');
    }

    public function getAuthPassword()
    {
        return $this->password;
    }
}
