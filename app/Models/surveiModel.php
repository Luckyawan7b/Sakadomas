<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\akunModel;
use App\Models\transaksiModel;

class surveiModel extends Model
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
        return $this->belongsTo(akunModel::class, 'id_akun', 'id_akun');
    }

    public function transaksi()
    {
        return $this->belongsTo(transaksiModel::class, 'id_transaksi', 'id_transaksi');
    }
}
