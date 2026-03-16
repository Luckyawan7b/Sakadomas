<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class desaModel extends Model
{
    protected $table = 'desa';
    protected $primaryKey = 'id_desa';
    public $timestamps = false;

    protected $fillable = [
        'nama_desa',
        'id_kecamatan',
    ];

    public function kecamatan()
    {
        return $this->belongsTo(kecamatanModel::class, 'id_kecamatan', 'id_kecamatan');
    }
}
