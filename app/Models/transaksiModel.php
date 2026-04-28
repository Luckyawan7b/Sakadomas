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
        'tgl_transaksi',
        'total_jumlah',
        'total_harga',
        'metode_pembayaran',
        'bukti_pembayaran', //URL
        'kurir',
        'status',
        'id_akun',
        'no_kurir',
        'id_jenis_ternak',
        'jenis_kelamin_pesanan',
        'tgl_dikirim',
        'batas_survei',
        'is_survei',
    ];

    protected $casts = [
        'is_survei' => 'boolean',
        'tgl_dikirim' => 'datetime',
    ];

    public function akun()
    {
        return $this->belongsTo(akunModel::class, 'id_akun', 'id_akun');
    }

    public function jenisTernak()
    {
        return $this->belongsTo(jenisTernakModel::class, 'id_jenis_ternak', 'id_jenis_ternak');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(detailTransaksiModel::class, 'id_transaksi', 'id_transaksi');
    }

    public function survei()
    {
        return $this->hasMany(surveiModel::class, 'id_transaksi', 'id_transaksi');
    }

    public function keuangan()
    {
        return $this->hasMany(keuanganModel::class, 'id_transaksi', 'id_transaksi');
    }
}
