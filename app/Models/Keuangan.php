<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    protected $table = 'keuangan';
    protected $primaryKey = 'id_keuangan';
    public $timestamps = false;

    protected $fillable = [
        'ket',
        'tanggal',
        'nominal',
        'jenis_keuangan',
        'id_transaksi',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }
}

