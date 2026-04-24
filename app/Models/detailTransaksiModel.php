<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detailTransaksiModel extends Model
{
    protected $table = 'detail_transaksi';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    protected $fillable = [
        'sub_jumlah',
        'sub_total',
        'id_ternak',
        'id_transaksi',
    ];

    public function ternak()
    {
        return $this->belongsTo(ternakModel::class, 'id_ternak', 'id_ternak');
    }

    public function transaksi()
    {
        return $this->belongsTo(transaksiModel::class, 'id_transaksi', 'id_transaksi');
    }
}
