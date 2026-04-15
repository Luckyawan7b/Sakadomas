<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class transaksiModel extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';
    public $timestamps = false;

    protected $fillable = [
        'id_ternak',
        'tgl_transaksi',
        'total_jumlah',
        'total_harga',
        'metode_pembayaran',
        'bukti_pembayaran', //URL
        'kurir',
        'status',
        'id_akun',
        'no_kurir'
    ];

    public function akun()
    {
        return $this->belongsTo(akunModel::class, 'id_akun', 'id_akun');
    }

    public function ternak()
    {
        return $this->belongsTo(ternakModel::class, 'id_ternak', 'id_ternak');
    }
}
