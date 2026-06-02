<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Akun;
use App\Models\Transaksi;

class Survei extends Model
{
    protected $table = 'survei';
    protected $primaryKey = 'id_survei';
    public $timestamps = false;

    protected $fillable = [
        'tgl_survei',
        'status',
        'ket',
        'ket_admin',
        'id_akun',
        'id_transaksi',
    ];

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun', 'id_akun');
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }
}

