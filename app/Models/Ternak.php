<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\JenisTernak;
use App\Models\Kamar;

class Ternak extends Model
{
     protected $table = 'ternak';
    protected $primaryKey = 'id_ternak';
    public $timestamps = false;

    protected $fillable = [
        'jenis_kelamin', // ENUM jantan,betina
        'usia', //int (bulan)
        'berat', // int (kg)
        'harga', // int
        'status_ternak', // ENUM('sehat','sakit','hamil', 'mati')
        'status_jual', //ENUM('tidak dijual','siap jual','booking','terjual')
        'last_update', // Date
        'last_monitor', // Date
        'id_kamar',
        'id_jenis_ternak',
    ];

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar', 'id_kamar');
    }

    public function jenis_ternak()
    {
        return $this->belongsTo(JenisTernak::class, 'id_jenis_ternak', 'id_jenis_ternak');
    }
}

