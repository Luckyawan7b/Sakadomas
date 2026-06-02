<?php

namespace App\Models;
use App\Models\Kandang;
use App\Models\Ternak;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $table = 'kamar';
    protected $primaryKey = 'id_kamar';
    public $timestamps = false;

    protected $fillable = [
        'nomor_kamar', //TINYINT NOT NULL
        'kapasitas',  //TINYINT NOT NULL
        // 'status', // ENUM('terisi','kosong','penuh','karantina')
        'id_kandang'
    ];

    public function kandang()
    {
        return $this->belongsTo(Kandang::class, 'id_kandang', 'id_kandang');
    }
    public function ternak(){
        return $this->hasMany(Ternak::class, 'id_kamar', 'id_kamar');
    }


}

