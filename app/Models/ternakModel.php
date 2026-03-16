<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\jenisTernakModel;
use App\Models\kamarModel;

class ternakModel extends Model
{
     protected $table = 'ternak';
    protected $primaryKey = 'id_ternak';
    public $timestamps = false;

    protected $fillable = [
        'jenis_kelamin', // ENUM jantan,betina
        'usia', //int (bulan)
        'berat', // int (kg)
        'harga', // int
        'status_ternak', // ENUM('sehat','sakit','hamil')
        'status_jual', //ENUM('tidak dijual','siap jual','booking','terjual')
        'last_update', // Date
        'id_kamar',
        'id_jenis_ternak',
    ];

    public function kamar()
    {
        return $this->belongsTo(kamarModel::class, 'id_kamar', 'id_kamar');
    }

    public function jenis_ternak()
    {
        return $this->belongsTo(jenisTernakModel::class, 'id_jenis_ternak', 'id_jenis_ternak');
    }
}
