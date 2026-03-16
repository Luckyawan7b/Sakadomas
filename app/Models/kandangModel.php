<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kandangModel extends Model
{
    protected $table = 'kandang';
    protected $primaryKey = 'id_kandang';
    public $timestamps = false;

    protected $fillable = [
        'nomor_kandang',
        'kapasitas',
    ];
}
